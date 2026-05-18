<div x-show="mediaPicker.open" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background:rgba(0,0,0,.55)"
     @click.self="mediaPicker.open = false">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[80vh] flex flex-col overflow-hidden"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900 text-sm">مكتبة الوسائط</h3>
            <button type="button" @click="mediaPicker.open = false"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-4">
            <div x-show="mediaPicker.loading" class="flex items-center justify-center py-12 text-gray-400 text-sm">
                <svg class="w-5 h-5 animate-spin me-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                جاري التحميل...
            </div>
            <div x-show="!mediaPicker.loading && mediaPicker.images.length === 0" class="flex flex-col items-center justify-center py-12 text-gray-400 text-sm">
                <p>لا توجد صور في المكتبة</p>
                <a href="{{ route('admin.media.index') }}" target="_blank" class="mt-2 text-xs underline" style="color:#FF8528;">رفع صور من هنا</a>
            </div>
            <div x-show="!mediaPicker.loading && mediaPicker.images.length > 0" class="grid grid-cols-4 gap-3">
                <template x-for="img in mediaPicker.images" :key="img.id">
                    <button type="button" @click="pickImage(img.url)"
                            class="group relative aspect-square rounded-xl overflow-hidden border-2 border-transparent hover:border-orange-400 transition-all">
                        <img :src="img.url" :alt="img.original_name" class="w-full h-full object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                            <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
