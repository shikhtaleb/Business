<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\PostCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    // ── Menus ──────────────────────────────────────────────────────────────────

    public function index(): View
    {
        $menus = Menu::withCount('items')->orderBy('name')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function show(Menu $menu): View
    {
        $items = $menu->items()->orderBy('sort_order')->get();

        $pages = Page::where('status', 'published')
            ->orderBy('title_ar')
            ->get(['id', 'title_ar', 'title_en', 'slug']);

        $categories = PostCategory::orderBy('name_ar')->get(['id', 'name_ar', 'name_en', 'slug'])->map(function ($c) {
            return ['id' => $c->id, 'title_ar' => $c->name_ar, 'title_en' => $c->name_en, 'slug' => 'blog?category=' . $c->slug];
        });

        return view('admin.menus.show', compact('menu', 'items', 'pages', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|in:header,footer,custom',
        ]);

        $menu = Menu::create($validated);

        return redirect()->route('admin.menus.show', $menu)
            ->with('success', 'تم إنشاء القائمة بنجاح.');
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|in:header,footer,custom',
        ]);

        $menu->update($validated);

        return redirect()->route('admin.menus.show', $menu)
            ->with('success', 'تم تحديث القائمة بنجاح.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')
            ->with('success', 'تم حذف القائمة بنجاح.');
    }

    // ── Menu Items (JSON API) ──────────────────────────────────────────────────

    public function storeItem(Request $request, Menu $menu): JsonResponse
    {
        $validated = $request->validate([
            'label_ar'  => 'required|string|max:255',
            'label_en'  => 'nullable|string|max:255',
            'label_nl'  => 'nullable|string|max:255',
            'label_de'  => 'nullable|string|max:255',
            'url'       => 'required|string|max:500',
            'target'    => 'nullable|in:_self,_blank',
            'icon'      => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
        ]);

        $item = $menu->items()->create([
            ...$validated,
            'target'     => $validated['target'] ?? '_self',
            'sort_order' => $menu->items()->count(),
        ]);

        return response()->json(['item' => $item]);
    }

    public function updateItem(Request $request, Menu $menu, MenuItem $item): JsonResponse
    {
        abort_if($item->menu_id !== $menu->id, 403);

        $validated = $request->validate([
            'label_ar'  => 'required|string|max:255',
            'label_en'  => 'nullable|string|max:255',
            'label_nl'  => 'nullable|string|max:255',
            'label_de'  => 'nullable|string|max:255',
            'url'       => 'required|string|max:500',
            'target'    => 'nullable|in:_self,_blank',
            'icon'      => 'nullable|string|max:255',
        ]);

        $item->update([...$validated, 'target' => $validated['target'] ?? '_self']);

        return response()->json(['item' => $item]);
    }

    public function destroyItem(Menu $menu, MenuItem $item): JsonResponse
    {
        abort_if($item->menu_id !== $menu->id, 403);

        // Detach children to root
        MenuItem::where('parent_id', $item->id)->update(['parent_id' => null]);
        $item->delete();

        return response()->json(['success' => true]);
    }

    public function reorderItems(Request $request, Menu $menu): JsonResponse
    {
        $request->validate([
            'items'             => 'required|array',
            'items.*.id'        => 'required|exists:menu_items,id',
            'items.*.sort_order'=> 'required|integer',
            'items.*.parent_id' => 'nullable|integer',
        ]);

        foreach ($request->input('items') as $data) {
            MenuItem::where('id', $data['id'])
                ->where('menu_id', $menu->id)
                ->update([
                    'sort_order' => $data['sort_order'],
                    'parent_id'  => $data['parent_id'] ?? null,
                ]);
        }

        return response()->json(['success' => true]);
    }

    // Keep legacy redirect routes for backward compat
    public function moveItemUp(Menu $menu, MenuItem $item): RedirectResponse
    {
        abort_if($item->menu_id !== $menu->id, 403);

        $previous = MenuItem::where('menu_id', $menu->id)
            ->where('parent_id', $item->parent_id)
            ->where('sort_order', '<', $item->sort_order)
            ->orderByDesc('sort_order')->first();

        if ($previous) {
            [$item->sort_order, $previous->sort_order] = [$previous->sort_order, $item->sort_order];
            $item->save(); $previous->save();
        }

        return redirect()->route('admin.menus.show', $menu);
    }

    public function moveItemDown(Menu $menu, MenuItem $item): RedirectResponse
    {
        abort_if($item->menu_id !== $menu->id, 403);

        $next = MenuItem::where('menu_id', $menu->id)
            ->where('parent_id', $item->parent_id)
            ->where('sort_order', '>', $item->sort_order)
            ->orderBy('sort_order')->first();

        if ($next) {
            [$item->sort_order, $next->sort_order] = [$next->sort_order, $item->sort_order];
            $item->save(); $next->save();
        }

        return redirect()->route('admin.menus.show', $menu);
    }
}
