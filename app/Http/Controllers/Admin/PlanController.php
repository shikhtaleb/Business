<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $this->validate($request);

        $plan = Plan::create($data);

        ActivityLog::record("Plan created: {$plan->name_en}", 'plans');

        return redirect()->route('admin.plans.index')
            ->with('success', __('admin.plan_created'));
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $this->validate($request, $plan->id);

        $plan->update($data);

        if ($request->boolean('is_popular')) {
            Plan::where('id', '!=', $plan->id)->update(['is_popular' => false]);
        }

        ActivityLog::record("Plan updated: {$plan->name_en}", 'plans');

        return back()->with('success', __('admin.plan_updated'));
    }

    public function destroy(Plan $plan)
    {
        ActivityLog::record("Plan deleted: {$plan->name_en}", 'plans');
        $plan->delete();
        return back()->with('success', __('admin.plan_deleted'));
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);

        foreach ($request->input('order') as $i => $id) {
            Plan::where('id', $id)->update(['sort_order' => $i]);
        }

        return response()->json(['ok' => true]);
    }

    private function validate(Request $request, ?int $ignoreId = null): array
    {
        $slugRule = 'required|string|max:100|unique:plans,slug' . ($ignoreId ? ",{$ignoreId}" : '');

        $request->validate([
            'slug'           => $slugRule,
            'name_ar'        => 'required|string|max:150',
            'name_en'        => 'required|string|max:150',
            'name_nl'        => 'nullable|string|max:150',
            'name_de'        => 'nullable|string|max:150',
            'description_ar' => 'nullable|string|max:500',
            'description_en' => 'nullable|string|max:500',
            'price_monthly'  => 'required|numeric|min:0',
            'price_yearly'   => 'required|numeric|min:0',
            'currency'       => 'required|string|max:10',
            'is_popular'     => 'nullable|boolean',
            'is_active'      => 'nullable|boolean',
            'sort_order'     => 'nullable|integer',
            'features_ar'    => 'nullable|string',
            'features_en'    => 'nullable|string',
        ]);

        // Parse features from textarea (one per line)
        $features = [];
        foreach (['ar', 'en', 'nl', 'de'] as $lang) {
            $raw = trim($request->input("features_{$lang}", ''));
            if ($raw) {
                $features[$lang] = array_filter(array_map('trim', explode("\n", $raw)));
            }
        }

        return [
            'slug'           => Str::slug($request->input('slug')),
            'name_ar'        => $request->input('name_ar'),
            'name_en'        => $request->input('name_en'),
            'name_nl'        => $request->input('name_nl'),
            'name_de'        => $request->input('name_de'),
            'description_ar' => $request->input('description_ar'),
            'description_en' => $request->input('description_en'),
            'price_monthly'  => $request->input('price_monthly'),
            'price_yearly'   => $request->input('price_yearly'),
            'currency'       => $request->input('currency', 'SAR'),
            'features'       => $features ?: null,
            'is_popular'     => $request->boolean('is_popular'),
            'is_active'      => $request->boolean('is_active', true),
            'sort_order'     => $request->input('sort_order', 0),
        ];
    }
}
