<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Display a listing of topics.
     */
    public function index()
    {
        return response()->json(Topic::all());
    }

    /**
     * Store a newly created topic in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $topic = Topic::create($data);

        return response()->json($topic, 201);
    }
}