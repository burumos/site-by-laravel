<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NicoItem extends Model
{
    protected $fillable = [
        'id',
        'title',
        'description',
        'nico_mylist_id',
        'video_time',
        'video_id',
        'image_src',
        'published_at',
        'created_at',
    ];

    protected $dates = [
        'published_at',
    ];

    public function nicoMylist()
    {
        return $this->belongsTo('App\Models\NicoMylist');
    }
}
