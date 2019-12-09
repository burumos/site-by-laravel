<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\NicoItemRepository;

class FetchNicoImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:nico:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'nico_itemsにimage_srcを基に画像を取得する';


    private $nicoItemRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NicoItemRepository $nicoItemRepository)
    {
        parent::__construct();
        $this->nicoItemRepository = $nicoItemRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $imageDir = \Constant::get('nico-image-dir');
        $imageSources = $this->nicoItemRepository->getImageSrc();

        foreach ($imageSources as $videoId => $url) {
            $imagePath = $imageDir.$videoId;

            if (\Storage::exists($imagePath)) {
                continue;
            }

            $image = $this->fetch($url);
            if ($image) {
                echo "save ".$videoId."\n";
                \Storage::put($imagePath, $image);
            } else {
                \Log::error(['fail fetch nico image', 'video-id' => $videoId, 'url' =>$url]);
            }
            sleep(1);
        }
    }

    private function fetch($url)
    {
        $option = [
            CURLOPT_RETURNTRANSFER => true, //文字列として返す
            CURLOPT_TIMEOUT        => 3, // タイムアウト時間
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, $option);

        $result  = curl_exec($ch);
        $info    = curl_getinfo($ch);
        $errorNo = curl_errno($ch);

        // OK以外はエラー
        if ($errorNo !== CURLE_OK) {
            // 詳しくエラーハンドリングしたい場合はerrorNoで確認
            // タイムアウトの場合はCURLE_OPERATION_TIMEDOUT
            return false;
        }

        // 200以外のステータスコードは失敗
        if ($info['http_code'] !== 200) {
            return false;
        }

        return $result;
    }
}
