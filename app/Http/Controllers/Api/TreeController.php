<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tree;
use Illuminate\Http\Request;

class TreeController extends Controller
{
    /**
     * Display a listing of tree nodes.
     */
    public function index()
    {
        return response()->json(Tree::all());
    }

    /**
     * Store a newly created tree node.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_id' => 'nullable|integer|exists:trees,id',
            'item_type' => 'required|string|max:50',
            'item_id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        if (empty($data['parent_id'])) {
            $data['depth'] = 0;
        } else {
            $parent = Tree::findOrFail($data['parent_id']);
            $data['depth'] = $parent->depth + 1;
        }

        // Temporary placeholder for path
        $data['path'] = '';

        $node = Tree::create($data);

        // Compute materialized path
        if ($node->parent_id) {
            $node->path = $parent->path . '.' . $node->id;
        } else {
            $node->path = (string) $node->id;
        }
        $node->save();

        return response()->json($node, 201);
    }
}