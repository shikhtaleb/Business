<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
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
        $menu->load(['rootItems.children']);

        return view('admin.menus.show', compact('menu'));
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

    // ── Menu Items ─────────────────────────────────────────────────────────────

    public function storeItem(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'label_ar'  => 'required|string|max:255',
            'label_en'  => 'nullable|string|max:255',
            'url'       => 'required|string|max:500',
            'target'    => 'nullable|in:_self,_blank',
            'icon'      => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'sort_order' => 'nullable|integer',
        ]);

        $menu->items()->create([
            ...$validated,
            'target'     => $validated['target'] ?? '_self',
            'sort_order' => $validated['sort_order'] ?? $menu->items()->count(),
        ]);

        return redirect()->route('admin.menus.show', $menu)
            ->with('success', 'تم إضافة العنصر بنجاح.');
    }

    public function updateItem(Request $request, Menu $menu, MenuItem $item): RedirectResponse
    {
        // Ensure item belongs to this menu
        abort_if($item->menu_id !== $menu->id, 403);

        $validated = $request->validate([
            'label_ar'  => 'required|string|max:255',
            'label_en'  => 'nullable|string|max:255',
            'url'       => 'required|string|max:500',
            'target'    => 'nullable|in:_self,_blank',
            'icon'      => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'sort_order' => 'nullable|integer',
        ]);

        $item->update([
            ...$validated,
            'target' => $validated['target'] ?? '_self',
        ]);

        return redirect()->route('admin.menus.show', $menu)
            ->with('success', 'تم تحديث العنصر بنجاح.');
    }

    public function destroyItem(Menu $menu, MenuItem $item): RedirectResponse
    {
        abort_if($item->menu_id !== $menu->id, 403);

        $item->delete();

        return redirect()->route('admin.menus.show', $menu)
            ->with('success', 'تم حذف العنصر بنجاح.');
    }

    public function reorderItems(Request $request, Menu $menu): JsonResponse
    {
        $request->validate([
            'items'            => 'required|array',
            'items.*.id'       => 'required|exists:menu_items,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->input('items') as $data) {
            MenuItem::where('id', $data['id'])
                ->where('menu_id', $menu->id)
                ->update(['sort_order' => $data['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    public function moveItemUp(Menu $menu, MenuItem $item): RedirectResponse
    {
        abort_if($item->menu_id !== $menu->id, 403);

        $previous = MenuItem::where('menu_id', $menu->id)
            ->where('parent_id', $item->parent_id)
            ->where('sort_order', '<', $item->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($previous) {
            [$item->sort_order, $previous->sort_order] = [$previous->sort_order, $item->sort_order];
            $item->save();
            $previous->save();
        }

        return redirect()->route('admin.menus.show', $menu);
    }

    public function moveItemDown(Menu $menu, MenuItem $item): RedirectResponse
    {
        abort_if($item->menu_id !== $menu->id, 403);

        $next = MenuItem::where('menu_id', $menu->id)
            ->where('parent_id', $item->parent_id)
            ->where('sort_order', '>', $item->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            [$item->sort_order, $next->sort_order] = [$next->sort_order, $item->sort_order];
            $item->save();
            $next->save();
        }

        return redirect()->route('admin.menus.show', $menu);
    }
}
