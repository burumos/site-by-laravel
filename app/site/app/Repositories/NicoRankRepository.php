<?php

namespace App\Repositories;

use App\Models\NicoRank;

class NicoRankRepository
{
    private $nicoRank;

    public function __construct(NicoRank $nicoRank)
    {
        $this->nicoRank = $nicoRank;
    }

    public function insertSingle($item)
    {
        $itemModel = new NicoRank();
        $itemModel->fill($item);

        $itemModel->save();
        return $itemModel;
    }

    public function fetchRankDate()
    {
        return $this->nicoRank
            ->select('kind', 'rank_date')
            ->groupBy('kind', 'rank_date')
            ->get();
    }

    public function fetchRankingItem($kind, $date)
    {
        return $this->nicoRank
            ->select('*')
            ->where('kind', $kind)
            ->where('rank_date', $date)
            ->orderBy('rank')
            ->with('nicoItem')
            ->get();
    }

}