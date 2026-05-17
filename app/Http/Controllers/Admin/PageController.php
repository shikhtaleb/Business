<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::with('author')
            ->orderBy('sort_order')
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar'    => 'required|string|max:255',
            'title_en'    => 'nullable|string|max:255',
            'title_nl'    => 'nullable|string|max:255',
            'title_de'    => 'nullable|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:pages,slug',
            'body_ar'     => 'nullable|string',
            'body_en'     => 'nullable|string',
            'body_nl'     => 'nullable|string',
            'body_de'     => 'nullable|string',
            'status'      => 'required|in:draft,published',
            'template'    => 'nullable|string|max:100',
            'show_in_nav' => 'nullable|boolean',
            'meta_title'  => 'nullable|string|max:255',
            'meta_desc'   => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer',
        ]);

        $slug = $validated['slug']
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title_ar']);

        // Ensure slug uniqueness
        $baseSlug = $slug;
        $counter  = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        Page::create([
            ...$validated,
            'slug'        => $slug,
            'show_in_nav' => $request->boolean('show_in_nav'),
            'template'    => $validated['template'] ?? 'default',
            'sort_order'  => $validated['sort_order'] ?? 0,
            'author_id'   => auth()->id(),
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'تم إنشاء الصفحة بنجاح.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar'    => 'required|string|max:255',
            'title_en'    => 'nullable|string|max:255',
            'title_nl'    => 'nullable|string|max:255',
            'title_de'    => 'nullable|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'body_ar'     => 'nullable|string',
            'body_en'     => 'nullable|string',
            'body_nl'     => 'nullable|string',
            'body_de'     => 'nullable|string',
            'status'      => 'required|in:draft,published',
            'template'    => 'nullable|string|max:100',
            'show_in_nav' => 'nullable|boolean',
            'meta_title'  => 'nullable|string|max:255',
            'meta_desc'   => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer',
        ]);

        $slug = $validated['slug']
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title_ar']);

        // Ensure slug uniqueness (excluding current page)
        $baseSlug = $slug;
        $counter  = 1;
        while (Page::where('slug', $slug)->where('id', '!=', $page->id)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $page->update([
            ...$validated,
            'slug'        => $slug,
            'show_in_nav' => $request->boolean('show_in_nav'),
            'template'    => $validated['template'] ?? 'default',
            'sort_order'  => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'تم تحديث الصفحة بنجاح.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'تم حذف الصفحة بنجاح.');
    }
}
