<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostCategoryController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────────

    public function index(): View
    {
        $categories = PostCategory::with('parent')
                                  ->withCount('posts')
                                  ->orderBy('sort_order')
                                  ->orderBy('name_ar')
                                  ->get();

        $roots = PostCategory::roots()->orderBy('sort_order')->orderBy('name_ar')->get();

        return view('admin.categories.index', compact('categories', 'roots'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar'        => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'name_nl'        => 'nullable|string|max:255',
            'name_de'        => 'nullable|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:post_categories,slug',
            'parent_id'      => 'nullable|exists:post_categories,id',
            'sort_order'     => 'nullable|integer|min:0',
            'description_ar' => 'nullable|string|max:2000',
            'description_en' => 'nullable|string|max:2000',
        ]);

        $validated['slug'] = $this->resolveSlug(
            $request->input('slug'),
            $request->input('name_ar')
        );

        PostCategory::create($validated);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'تم إنشاء التصنيف بنجاح.');
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(PostCategory $category): View
    {
        $roots = PostCategory::roots()
                             ->where('id', '!=', $category->id)
                             ->orderBy('sort_order')
                             ->orderBy('name_ar')
                             ->get();

        return view('admin.categories.edit', compact('category', 'roots'));
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, PostCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar'        => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'name_nl'        => 'nullable|string|max:255',
            'name_de'        => 'nullable|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:post_categories,slug,' . $category->id,
            'parent_id'      => 'nullable|exists:post_categories,id',
            'sort_order'     => 'nullable|integer|min:0',
            'description_ar' => 'nullable|string|max:2000',
            'description_en' => 'nullable|string|max:2000',
        ]);

        // Prevent a category from becoming its own parent
        if ((string) ($validated['parent_id'] ?? '') === (string) $category->id) {
            $validated['parent_id'] = null;
        }

        $validated['slug'] = $this->resolveSlug(
            $request->input('slug'),
            $request->input('name_ar'),
            $category->id
        );

        $category->update($validated);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'تم تحديث التصنيف بنجاح.');
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    public function destroy(PostCategory $category): RedirectResponse
    {
        // Re-parent children to avoid orphans
        PostCategory::where('parent_id', $category->id)
                    ->update(['parent_id' => $category->parent_id]);

        // Detach posts
        $category->posts()->update(['category_id' => null]);

        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'تم حذف التصنيف بنجاح.');
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function resolveSlug(?string $slug, ?string $nameAr, ?int $excludeId = null): string
    {
        $base = $slug ? Str::slug($slug) : Str::slug($nameAr ?? 'category');

        if (! $base) {
            $base = 'category-' . time();
        }

        $candidate = $base;
        $counter   = 1;

        while (true) {
            $exists = PostCategory::where('slug', $candidate)
                                  ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                                  ->exists();

            if (! $exists) {
                break;
            }

            $candidate = $base . '-' . $counter++;
        }

        return $candidate;
    }
}
