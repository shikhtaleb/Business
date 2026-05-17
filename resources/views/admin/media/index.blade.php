@extends('layouts.admin')

@section('title', 'مكتبة الوسائط')
@section('page-title', 'مكتبة الوسائط')

@section('content')

    {{-- ── Upload Card ──────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6"
         x-data="{
             uploading: false,
             previewUrl: null,
             previewName: null,
             uploadError: null,

             async handleUpload(event) {
                 event.preventDefault();
                 const form = event.target;
                 const formData = new FormData(form);
                 this.uploading = true;
                 this.uploadError = null;
                 this.previewUrl = null;
                 this.previewName = null;

                 try {
                     const response = await fetch('{{ route('admin.media.upload') }}', {
                         method: 'POST',
                         headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                         body: formData,
                     });
                     const data = await response.json();
                     if (response.ok && data.url) {
                         this.previewUrl = data.url;
                         this.previewName = data.filename ?? 'تم الرفع';
                         form.reset();
                         setTimeout(() => window.location.reload(), 1500);
                     } else {
                         this.uploadError = data.message ?? 'فشل الرفع. حاول مرة أخرى.';
                     }
                 } catch (e) {
                     this.uploadError = 'خطأ في الشبكة. حاول مرة أخرى.';
                 } finally {
                     this.uploading = false;
                 }
             }
         }"
    >
        <h2 class="text-base font-semibold text-gray-800 mb-4">رفع ملف جديد</h2>

        <!-- Upload Error -->
        <div x-show="uploadError" x-cloak
             class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-red-700" x-text="uploadError"></p>
        </div>

        <!-- Upload Preview -->
        <div x-show="previewUrl" x-cloak
             class="mb-4 flex items-center gap-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
            <img :src="previewUrl" class="w-12 h-12 object-cover rounded-lg border border-green-200" alt="Preview">
            <div>
                <p class="text-sm font-medium text-green-800" x-text="previewName"></p>
                <p class="text-xs text-green-600">تم الرفع بنجاح! جارٍ التحديث...</p>
            </div>
        </div>

        <form @submit="handleUpload" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-wrap items-end gap-4">
                <!-- File Input -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الملف</label>
                    <input
                        type="file"
                        name="file"
                        required
                        class="w-full text-sm text-gray-500
                               file:me-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                               file:text-sm file:font-semibold file:text-white file:cursor-pointer
                               hover:file:opacity-90 transition-all"
                        style="--file-bg:#FF8528;"
                        accept="image/*,.svg,.ico"
                        onchange="this.style.cssText = ''"
                    >
                    <style>
                        input[type=file]::file-selector-button {
                            background-color: #FF8528;
                            color: white;
                            border: none;
                            padding: 0.5rem 1rem;
                            border-radius: 0.5rem;
                            font-size: 0.875rem;
                            font-weight: 600;
                            cursor: pointer;
                            transition: background-color 0.15s;
                        }
                        input[type=file]::file-selector-button:hover {
                            background-color: #E06800;
                        }
                    </style>
                </div>

                <!-- Type Selector -->
                <div class="w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">النوع</label>
                    <select name="type"
                        class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm text-gray-700
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow">
                        <option value="general">عام</option>
                        <option value="logo">شعار</option>
                        <option value="favicon">أيقونة الموقع</option>
                    </select>
                </div>

                <!-- Upload Button -->
                <button
                    type="submit"
                    :disabled="uploading"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold
                           shadow-md hover:shadow-lg transition-all duration-150 disabled:opacity-60 disabled:cursor-not-allowed"
                    style="background-color:#FF8528;"
                    onmouseover="if(!this.disabled) this.style.backgroundColor='#E06800'"
                    onmouseout="this.style.backgroundColor='#FF8528'"
                >
                    <svg x-show="!uploading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <svg x-show="uploading" x-cloak class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span x-text="uploading ? 'جارٍ الرفع...' : 'رفع'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- ── Media Grid ───────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-800">
                الملفات
                @if(isset($media) && method_exists($media, 'total'))
                    <span class="ms-2 text-sm font-normal text-gray-400">({{ $media->total() }} ملف)</span>
                @endif
            </h2>
        </div>

        <div class="p-5">
            @if(isset($media) && $media->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach ($media as $file)
                        @php
                            $ext = strtolower(pathinfo($file->filename ?? $file->path ?? '', PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp']);
                            $isSvg   = $ext === 'svg';
                            $isIco   = $ext === 'ico';
                            $sizeKb  = isset($file->size) ? round($file->size / 1024, 1) . ' KB' : '—';
                        @endphp
                        <div class="group relative bg-gray-50 border border-gray-100 rounded-xl overflow-hidden hover:border-gray-200 hover:shadow-sm transition-all">
                            <!-- Thumbnail -->
                            <div class="aspect-square flex items-center justify-center bg-gray-100 overflow-hidden">
                                @if ($isImage)
                                    <img
                                        src="{{ $file->url ?? asset('storage/'.$file->path) }}"
                                        alt="{{ $file->filename ?? '' }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >
                                @elseif ($isSvg)
                                    <div class="flex flex-col items-center gap-1 p-3">
                                        <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                        </svg>
                                        <span class="text-xs font-bold text-blue-400 uppercase">SVG</span>
                                    </div>
                                @elseif ($isIco)
                                    <div class="flex flex-col items-center gap-1 p-3">
                                        <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs font-bold text-yellow-500 uppercase">ICO</span>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center gap-1 p-3">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="text-xs font-bold text-gray-400 uppercase">{{ strtoupper($ext) ?: 'FILE' }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="px-2.5 py-2 border-t border-gray-100">
                                <p class="text-xs font-medium text-gray-700 truncate" title="{{ $file->filename ?? $file->path ?? '' }}">
                                    {{ $file->filename ?? basename($file->path ?? 'غير معروف') }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $sizeKb }}</p>
                            </div>

                            <!-- Delete Button -->
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form method="POST" action="{{ route('admin.media.destroy', $file->id) }}"
                                      onsubmit="return confirm('حذف هذا الملف نهائياً؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-500 text-white shadow-md hover:bg-red-600 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <!-- Copy URL button -->
                            <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button
                                    type="button"
                                    onclick="navigator.clipboard.writeText('{{ $file->url ?? asset('storage/'.($file->path ?? '')) }}').then(() => this.title = 'تم النسخ!')"
                                    title="نسخ الرابط"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-800/70 text-white shadow-md hover:bg-gray-900 transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(method_exists($media, 'links'))
                    <div class="mt-6">
                        {{ $media->links() }}
                    </div>
                @endif
            @else
                <div class="py-16 text-center">
                    <svg class="mx-auto w-14 h-14 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500 font-medium">لا توجد ملفات بعد</p>
                    <p class="text-sm text-gray-400 mt-1">ارفع أول ملف باستخدام النموذج أعلاه.</p>
                </div>
            @endif
        </div>
    </div>

@endsection
