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

    public function fetchBy()
    {
        return $this->nicoRank
            ->select('*')
            ->where()
            ->get();
    }

}