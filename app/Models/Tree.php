<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'path',
        'item_type',
        'item_id',
        'depth',
        'name',
    ];

    /**
     * Parent node relation.
     */
    public function parent()
    {
        return $this->belongsTo(Tree::class, 'parent_id');
    }

    /**
     * Child nodes relation.
     */
    public function children()
    {
        return $this->hasMany(Tree::class, 'parent_id');
    }

    /**
     * Descendant nodes based on materialized path.
     */
    public function descendants()
    {
        return $this->hasMany(Tree::class)->where('path', 'like', $this->path . '%');
    }

    /**
     * Polymorphic relation to the associated item.
     */
    public function item()
    {
        return $this->morphTo();
    }
}