<?php

namespace App\Repositories;

use App\Models\NicoMylist;

class NicoMylistRepository
{
    private $nicoMylist;

    public function __construct(NicoMylist $nicoMylist)
    {
        $this->nicoMylist = $nicoMylist;
    }



    public function insertSingle($mylist)
    {
        $mylistModel = new NicoMylist();
        $mylistModel->fill($mylist);

        $mylistModel->save();
        return $mylistModel;
    }


    public function insert($mylists, $isMultiple=false)
    {
        if ($isMultiple) {
            $result = [];
            foreach ($myslits as $mylist) {
                $result[] = self::insertSingle($mylist);
            }
            return $result;
        }
        return self::insertSingle($mylist);
    }

    public function getByUserId($userId)
    {
        $result = $this->nicoMylist
                ->select('*')
                ->where('user_id', $userId)
                ->get();
        return $result;
    }

    public function getWithItemsByUserId($userId)
    {
        $result = $this->nicoMylist
                ->select('*')
                ->where('user_id', $userId)
                ->with('nicoItems')
                ->get();
        return $result;
    }

    public function getWithItemsByIds($ids)
    {
        $result = $this->nicoMylist
                ->select('*')
                ->whereIn('id', $ids)
                ->with('nicoItems')
                ->get();

        return $result;
    }

}
