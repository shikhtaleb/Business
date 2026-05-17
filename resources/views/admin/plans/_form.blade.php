@if ($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li class="text-sm text-red-700">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Slug --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-red-500">*</span></label>
    <input type="text" name="slug" value="{{ old('slug', $plan->slug ?? '') }}" required
           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm font-mono focus:outline-none focus:ring-2 focus:border-transparent"
           placeholder="basic / pro / enterprise">
    <p class="mt-1 text-xs text-gray-400">معرّف فريد بالإنجليزية بدون مسافات</p>
</div>

{{-- Names --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم بالعربية <span class="text-red-500">*</span></label>
        <input type="text" name="name_ar" value="{{ old('name_ar', $plan->name_ar ?? '') }}" required dir="rtl"
               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم بالإنجليزية <span class="text-red-500">*</span></label>
        <input type="text" name="name_en" value="{{ old('name_en', $plan->name_en ?? '') }}" required
               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم بالهولندية</label>
        <input type="text" name="name_nl" value="{{ old('name_nl', $plan->name_nl ?? '') }}"
               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم بالألمانية</label>
        <input type="text" name="name_de" value="{{ old('name_de', $plan->name_de ?? '') }}"
               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2">
    </div>
</div>

{{-- Descriptions --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">الوصف بالعربية</label>
        <textarea name="description_ar" rows="2" dir="rtl"
                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 resize-none">{{ old('description_ar', $plan->description_ar ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">الوصف بالإنجليزية</label>
        <textarea name="description_en" rows="2"
                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 resize-none">{{ old('description_en', $plan->description_en ?? '') }}</textarea>
    </div>
</div>

{{-- Prices --}}
<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">السعر الشهري <span class="text-red-500">*</span></label>
        <input type="number" name="price_monthly" min="0" step="0.01"
               value="{{ old('price_monthly', $plan->price_monthly ?? '0') }}" required
               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">السعر السنوي <span class="text-red-500">*</span></label>
        <input type="number" name="price_yearly" min="0" step="0.01"
               value="{{ old('price_yearly', $plan->price_yearly ?? '0') }}" required
               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">العملة</label>
        <select name="currency" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2">
            @foreach(['SAR' => 'SAR — ريال', 'USD' => 'USD — دولار', 'EUR' => 'EUR — يورو', 'AED' => 'AED — درهم'] as $code => $label)
                <option value="{{ $code }}" {{ old('currency', $plan->currency ?? 'SAR') === $code ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Features --}}
<div class="grid grid-cols-2 gap-4">
    @foreach(['ar' => 'مزايا الخطة (عربي)', 'en' => 'مزايا الخطة (إنجليزي)'] as $lang => $label)
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>
            <textarea name="features_{{ $lang }}" rows="5" dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}"
                      class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 resize-none font-mono"
                      placeholder="ميزة واحدة في كل سطر">{{ old("features_{$lang}", isset($plan) ? implode("\n", $plan->getFeatures($lang)) : '') }}</textarea>
        </div>
    @endforeach
</div>

{{-- Options --}}
<div class="flex items-center gap-6">
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $plan->is_popular ?? false) ? 'checked' : '' }}
               class="w-4 h-4 rounded border-gray-300" style="accent-color:#FF8528;">
        <span class="text-sm text-gray-700">★ {{ __('admin.popular') }}</span>
    </label>
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}
               class="w-4 h-4 rounded border-gray-300" style="accent-color:#FF8528;">
        <span class="text-sm text-gray-700">{{ __('admin.active') }}</span>
    </label>
</div>
