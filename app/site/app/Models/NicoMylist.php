<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NicoMylist extends Model
{
    protected $fillable = [
        'origin_id',
        'name',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function nicoItems()
    {
        return $this->hasMany('App\Models\NicoItem');
    }

}
