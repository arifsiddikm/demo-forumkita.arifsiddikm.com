<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    /**
     * CKEditor image upload endpoint.
     * PENTING: Kembalikan URL relatif agar tidak terikat port/domain tertentu.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $file = $request->file('upload');
        $name = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('thread-images/' . date('Y/m'), $name, 'public');

        // Kembalikan URL relatif — CKEditor akan resolve sendiri berdasarkan domain saat ini
        // Ini fix masalah port mismatch (misal: APP_URL=:8000 tapi user buka di :8003)
        $relativeUrl = '/storage/' . $path;

        return response()->json([
            'url' => $relativeUrl,
        ]);
    }

    /**
     * Thread thumbnail upload.
     */
    public function uploadThumbnail(Request $request)
    {
        $request->validate([
            'thumbnail' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:3072'],
        ]);

        $file = $request->file('thumbnail');
        $name = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('thread-thumbnails/' . date('Y/m'), $name, 'public');

        return response()->json([
            'url'  => '/storage/' . $path,
            'path' => $path,
        ]);
    }
}
