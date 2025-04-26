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
    /**
     * Move a tree node under a new parent.
     */
    public function move(Request $request, $id)
    {
        $data = $request->validate([
            'parent_id' => 'nullable|integer|exists:trees,id',
        ]);
        $node = Tree::findOrFail($id);
        $oldPath = $node->path;

        // Determine new parent and depth
        if ($data['parent_id']) {
            $parent = Tree::findOrFail($data['parent_id']);
            $node->parent_id = $parent->id;
            $node->depth = $parent->depth + 1;
        } else {
            $node->parent_id = null;
            $node->depth = 0;
        }
        $node->save();

        // Compute new path for node
        $newPath = $node->parent_id ? ($parent->path . '.' . $node->id) : (string) $node->id;
        $node->path = $newPath;
        $node->save();

        // Update descendants
        $descendants = Tree::where('path', 'like', $oldPath . '.%')->get();
        foreach ($descendants as $desc) {
            $relative = substr($desc->path, strlen($oldPath) + 1);
            $desc->path = $newPath . '.' . $relative;
            $desc->depth = substr_count($desc->path, '.') ;
            $desc->save();
        }

        return response()->json($node);
    }
    /**
     * Delete a tree node and its descendants.
     */
    public function destroy($id)
    {
        $node = Tree::findOrFail($id);
        Tree::where('path', 'like', $node->path . '%')->delete();
        return response()->noContent();
    }
    /**
     * Get a subtree (node and descendants), optionally depth-limited.
     */
    public function subtree(Request $request, $id)
    {
        $node = Tree::findOrFail($id);
        $depthLimit = $request->query('depth');
        $baseDepth = $node->depth;

        $query = Tree::where('path', 'like', $node->path . '%');
        if (is_numeric($depthLimit)) {
            $maxDepth = $baseDepth + (int) $depthLimit;
            $query->where('depth', '<=', $maxDepth);
        }
        $subtree = $query->orderBy('path')->get();
        return response()->json($subtree);
    }
}