<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::orderBy('created_at', 'desc')->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,svg,webp,ico,gif|max:2048',
        ]);

        $file          = $request->file('file');
        $year          = now()->format('Y');
        $month         = now()->format('m');
        $ext           = $file->getClientOriginalExtension();
        $uniqueName    = Str::uuid() . '.' . $ext;
        $relativePath  = "media/{$year}/{$month}/{$uniqueName}";

        Storage::disk('public')->putFileAs(
            "media/{$year}/{$month}",
            $file,
            $uniqueName
        );

        $allowedTypes = ['logo', 'favicon', 'general'];
        $type         = $request->input('type', 'general');
        if (!in_array($type, $allowedTypes)) {
            $type = 'general';
        }

        $url = Storage::disk('public')->url($relativePath);

        $media = Media::create([
            'filename'      => $uniqueName,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'disk'          => 'public',
            'path'          => $relativePath,
            'url'           => $url,
            'type'          => $type,
        ]);

        ActivityLog::record(
            "Media uploaded: {$media->original_name} (type={$type})",
            'media',
            ['id' => $media->id, 'type' => $type]
        );

        return response()->json([
            'ok'  => true,
            'url' => $url,
            'id'  => $media->id,
        ]);
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);

        Storage::disk('public')->delete($media->path);

        ActivityLog::record(
            "Media deleted: {$media->original_name}",
            'media',
            ['id' => $media->id]
        );

        $media->delete();

        return back()->with('success', 'File deleted successfully.');
    }
}
