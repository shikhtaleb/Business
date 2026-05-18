<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = Post::with(['category', 'author'])
                     ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $posts      = $query->paginate(15)->withQueryString();
        $categories = PostCategory::orderBy('name_ar')->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create(): View
    {
        $categories = PostCategory::orderBy('sort_order')->orderBy('name_ar')->get();

        return view('admin.posts.create', compact('categories'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePost($request);

        $validated['author_id'] = auth()->id();
        $validated['slug']      = $this->resolveSlug($request->input('slug'), $request->input('title_ar'));

        // Handle scheduled → set published_at automatically if not supplied
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $post = Post::create($validated);

        $this->syncTags($post, $request->input('tags_input', ''));

        return redirect()->route('admin.posts.index')
                         ->with('success', 'تم إنشاء المقال بنجاح.');
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(Post $post): View
    {
        $categories = PostCategory::orderBy('sort_order')->orderBy('name_ar')->get();
        $tagsList   = $post->tags->pluck('name_ar')->implode(', ');

        return view('admin.posts.edit', compact('post', 'categories', 'tagsList'));
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $this->validatePost($request, $post->id);

        $validated['slug'] = $this->resolveSlug($request->input('slug'), $request->input('title_ar'), $post->id);

        if ($validated['status'] === 'published' && empty($validated['published_at']) && ! $post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        $this->syncTags($post, $request->input('tags_input', ''));

        return redirect()->route('admin.posts.edit', $post)
                         ->with('success', 'تم تحديث المقال بنجاح.');
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    public function destroy(Post $post): RedirectResponse
    {
        $user = auth()->user();

        if ((int) $post->author_id !== (int) $user->id && ! $user->hasRole('super_admin')) {
            return redirect()->route('admin.posts.index')
                             ->with('error', 'ليس لديك صلاحية حذف هذا المقال.');
        }

        $post->tags()->detach();
        $post->delete();

        return redirect()->route('admin.posts.index')
                         ->with('success', 'تم حذف المقال بنجاح.');
    }

    // ── Publish (toggle) ──────────────────────────────────────────────────────

    public function publish(Post $post): RedirectResponse
    {
        if ($post->status === 'published') {
            $post->update(['status' => 'draft', 'published_at' => null]);
            $message = 'تم إلغاء نشر المقال.';
        } else {
            $post->update(['status' => 'published', 'published_at' => now()]);
            $message = 'تم نشر المقال بنجاح.';
        }

        return back()->with('success', $message);
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function validatePost(Request $request, ?int $postId = null): array
    {
        $slugRule = $postId
            ? 'nullable|string|max:255|unique:posts,slug,' . $postId
            : 'nullable|string|max:255|unique:posts,slug';

        return $request->validate([
            'slug'           => $slugRule,
            'title_ar'       => 'required|string|max:255',
            'title_en'       => 'nullable|string|max:255',
            'title_nl'       => 'nullable|string|max:255',
            'title_de'       => 'nullable|string|max:255',
            'excerpt_ar'     => 'nullable|string|max:1000',
            'excerpt_en'     => 'nullable|string|max:1000',
            'excerpt_nl'     => 'nullable|string|max:1000',
            'excerpt_de'     => 'nullable|string|max:1000',
            'body_ar'        => 'nullable|string',
            'body_en'        => 'nullable|string',
            'body_nl'        => 'nullable|string',
            'body_de'        => 'nullable|string',
            'category_id'    => 'nullable|exists:post_categories,id',
            'featured_image' => 'nullable|string|max:500',
            'status'         => 'required|in:draft,published,scheduled',
            'published_at'   => 'nullable|date',
            'is_featured'    => 'nullable|boolean',
            'seo_title'      => 'nullable|string|max:255',
            'seo_desc'       => 'nullable|string|max:500',
            'lang_locked'    => 'nullable|boolean',
            'tags_input'     => 'nullable|string|max:1000',
        ]);
    }

    /**
     * Generate a unique slug from the provided value or from the Arabic title.
     */
    private function resolveSlug(?string $slug, ?string $titleAr, ?int $excludeId = null): string
    {
        $base = $slug ? Str::slug($slug) : Str::slug($titleAr ?? 'post');

        if (! $base) {
            $base = 'post-' . time();
        }

        $candidate = $base;
        $counter   = 1;

        while (true) {
            $exists = Post::where('slug', $candidate)
                          ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                          ->exists();

            if (! $exists) {
                break;
            }

            $candidate = $base . '-' . $counter++;
        }

        return $candidate;
    }

    /**
     * Sync tags from a comma-separated string, creating new ones as needed.
     */
    private function syncTags(Post $post, string $rawInput): void
    {
        $tagIds = [];

        $names = array_filter(array_map('trim', explode(',', $rawInput)));

        foreach ($names as $name) {
            if ($name === '') {
                continue;
            }

            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name_ar' => $name, 'name_en' => $name]
            );

            $tagIds[] = $tag->id;
        }

        $post->tags()->sync($tagIds);
    }
}
