<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeContentController extends Controller
{
    public function getContent()
    {
        $content = HomeContent::all()->groupBy('section');
        return response()->json($content);
    }

    public function updateContent(Request $request)
    {
        $request->validate([
            'section' => 'required|string',
            'key' => 'required|string',
            'content' => 'required',
            'type' => 'required|string'
        ]);

        $content = HomeContent::updateOrCreate(
            ['section' => $request->section, 'key' => $request->key],
            [
                'content' => $request->content,
                'type' => $request->type
            ]
        );

        // Clear the cache so the changes are reflected immediately
        Cache::forget('home_content');

        return response()->json([
            'success' => true,
            'content' => $content
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $request->file('image')->store('home-images', 'public');

        return response()->json([
            'success' => true,
            'path' => asset('storage/' . $imagePath)
        ]);
    }
} 