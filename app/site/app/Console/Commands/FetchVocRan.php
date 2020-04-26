<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use IvoPetkov\HTML5DOMDocument;

class FetchVocRan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fetchVocRan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ニコ動スクレイピング';

    // private
    private $rootDom;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "start\n";
        // HTMLの読み込み

        
        echo "end\n";
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
     * parse
     **/
    private function parseHTML($file) {
        $html = file_get_contents('/root/site/utau.html');
        $dom = new HTML5DOMDocument();
        $dom->loadHTML($html);
        $this->rootDom = $dom;
    }

    /**
     * utau
     **/
    private function fetchUtau() {
        $nicoItems = $this->findByAttr('data-nicoad-video');
        $count = 1;
        return array_map(function($item) use ($count) {
            return [
                'rank': $count++,
                'title': trim(corrent($this->findByAttr('title', $item))->getAttribute('title')),
                'uploadDate': $item->querySelector('.time')->innerHtml,
                'description': trim($this->findByAttr('title', $item)[1]->getAttribute('title')),
                'videoId': trim($item->getAttribute('data-video-id'))
                'link': 'https://www.nicovideo.jp' . $item->querySelector('a')->getAttribute('href'),
                'thumbnailImage': $item->querySelector('img')->getAttribute('data-original')
            ];
        }, $nicoItems);

        // var counter = 1;
        // var rankingObjs = records.map(function (e) {
        //   return {
        //     rank: counter++,
        //     title: dataGet(e.find('title'), [0, 'text'], '').trim(),
        //     uploadDate: dataGet(e.find('class', 'time'), [0, 'text'], ''),
        //     description: dataGet(e.find('title'), [1, 'text'], '').trim(),
        //     videoId: dataGet(e, ['attributes', 'data-video-id'], '').trim(),
        //     link: getUrlOrigin('https://www.nicovideo.jp' + dataGet(e.find('tagName', 'a'), [0, 'attributes', 'href'], '')),
        //     thumbnailImage: dataGet(e.find('tagName', 'img'), [0, 'attributes', 'data-original'], ''),
        //   };
    }

    /**
     * vocalo
     **/
    private function fetchVoc() {
        // var records = root.find('class', 'RankingMainVideo');
        // var rankingObjs = records.map(function (e) {
        //   return {
        //     rank: dataGet(e.find('class', 'RankingRowRank'), [0, 'text'], '').trim(),
        //     title: dataGet(e.find('class', 'RankingMainVideo-title'), [0, 'text'], '').trim(),
        //     uploadDate: dataGet(e.find('class', 'RankingMainVideo-uploaded'), [0, 'text'], '').replace('投稿', '').trim(),
        //     description: dataGet(e.find('class', 'RankingMainVideo-description'), [0, 'text'], '').trim(),
        //     videoId: dataGet(e, ['attributes', 'data-video-id'], '').trim(),
        //     link: 'https://www.nicovideo.jp' + dataGet(e.find('tagName', 'a'), [0, 'attributes', 'href'], ''),
        //     thumbnailImage: dataGet(e.find('class', 'Thumbnail-image'), [0, 'attributes', 'data-background-image'], ''),
        //   };
        
    }
}
