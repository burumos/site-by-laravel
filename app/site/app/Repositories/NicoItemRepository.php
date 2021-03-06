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

    public function getImageSrc()
    {
        $result = $this->nicoItem
                ->select('*')
                ->pluck('image_src', 'video_id');

        return $result;
    }

    public function getByVideoIdAndNicoMylistId($videoId, $mylistId)
    {
        return $this->nicoItem
            ->select('*')
            ->where('video_id', $videoId)
            ->where('nico_mylist_id', $mylistId)
            ->get();
    }

}
