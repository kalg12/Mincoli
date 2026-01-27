<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048', // 2MB max
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/blog', 'public');
            return response()->json(['url' => Storage::url($path)]);
        }

        return response()->json(['error' => 'No image uploaded'], 400);
    }
}
