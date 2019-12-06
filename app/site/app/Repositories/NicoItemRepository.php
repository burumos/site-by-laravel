<?php

namespace App\Repositories;

use App\Models\NicoItem;

class NicoItemRepository
{
    private $nicoItem;

    public function __construct(NicoItem $nicoItem)
    {
        $this->nicoItem = $nicoItem;
    }



    public function insertSingle($item)
    {
        $itemModel = new NicoItem();
        $itemModel->fill($item);

        $itemModel->save();
        return $itemModel;
    }


    public function insert($items, $isMultiple=false)
    {
        if ($isMultiple) {
            $result = [];
            foreach ($items as $item) {
                $result[] = self::insertSingle($item);
            }
            return $result;
        }
        return self::insertSingle($items);
    }

}
