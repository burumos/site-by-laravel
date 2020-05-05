<?php

namespace App\Http\Services;

use Illuminate\Support\Arr;
use IvoPetkov\HTML5DOMDocument;

class HtmlScrapService
{
    private $rootDom;

    /**
     * parse
     **/
    public function parseHTML($html) {
        $dom = new HTML5DOMDocument();
        $dom->loadHTML($html);
        $this->rootDom = $dom;
    }


    /**
     *  指定した属性を持つdomを全て取得
     */
    private function findByAttr($attrKey, $parent=null, $val=null) {
        if (!$parent) {
            $parent = $this->rootDom;
        }
        $allDom = $parent->querySelectorAll('*');
        $result = [];
        for ($i=0; $i < $allDom->length; $i++) {
            $dom = $allDom->item($i);
            $attrs = $dom->getAttributes();
            if (array_key_exists($attrKey, $attrs)
                && (is_null($val)
                    || $attrs[$attrKey] == $val)) {
                $result[] = $dom;
            }
        }
        return $result;
    }

    /**
     * utau
     **/
    public function getUtauJson() {
        $nicoItems = $this->findByAttr('data-nicoad-video');
        $count = 1;
        return array_map(function($item) use (&$count) {
            $result = [
                'rank' => $count++,
                'title' => current($this->findByAttr('title', $item))->getAttribute('title'),
                'published_at' => $item->querySelector('.time')->innerHTML,
                'description' => $this->findByAttr('title', $item)[1]->getAttribute('title'),
                'video_id' => $item->getAttribute('data-video-id'),
                'image_src' => $item->querySelector('img')->getAttribute('data-original'),
                'video_time' => $item->querySelector('.videoLength')->innerHTML,
            ];
            return array_map(function($val) {
                return trim($val);
            }, $result);
        }, $nicoItems);
    }

    /**
     * vocalo
     **/
    public function getVocaloJson() {
        $nicoItems = $this->rootDom->querySelectorAll('.RankingMainVideo');
        $result = [];
        for ($i=1; $i < $nicoItems->length; $i++) {
            $element = $nicoItems->item($i);
            $record = [
                'rank' => $element->querySelector('.RankingRowRank')->innerHTML,
                'title' => $element->querySelector('.RankingMainVideo-title')->innerHTML,
                'published_at' => str_replace('投稿', '', $element->querySelector('.RankingMainVideo-uploaded')->innerHTML),
                'description' => $element->querySelector('.RankingMainVideo-description')->innerHTML,
                'video_id' => $element->getAttribute('data-video-id'),
                'image_src' => $element->querySelector('.Thumbnail-image')->getAttribute('data-background-image'),
                'video_time' => $element->querySelector('.VideoLength')->innerHTML,
            ];
            $result[] = array_map(function($val) {
               return trim($val);
            },$record);
        }
        return $result;
    }
}
