<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NicoRank extends Model
{
    protected $fillable = [
        'nico_item_id',
        'kind',
        'rank',
        'rank_date',
    ];

    protected $dates = [
        'rank_date',
    ];

    public function nicoItem()
    {
        return $this->belongsTo('App\Models\NicoItem');
    }
}
