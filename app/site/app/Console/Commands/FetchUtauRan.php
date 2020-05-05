<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Services\HtmlScrapService;
use App\Repositories\NicoRankRepository;
use App\Repositories\NicoItemRepository;
use Carbon\Carbon;
use Arr;

class FetchUtauRan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fetchUtauRan {fileName?} {rankDate?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ニコ動スクレイピング utau ranking';

    private $htmlScrapService;
    private $nicoRankRepository;
    private $nicoItemRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        HtmlScrapService $htmlScrapService,
        NicoRankRepository $nicoRankRepository,
        NicoItemRepository $nicoItemRepository
    ) {
        parent::__construct();
        $this->htmlScrapService = $htmlScrapService;
        $this->nicoRankRepository = $nicoRankRepository;
        $this->nicoItemRepository = $nicoItemRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "start\n";
        list($html, $rankDate) = $this->htmlAndRankDate();

        // HTMLの読み込み
        $this->htmlScrapService->parseHTML($html);
        $result = $this->htmlScrapService->getUtauJson();

        foreach ($result as $record) {
            $videoId = Arr::get($record, 'video_id');
            $nicoItem = $this->nicoItemRepository->getByVideoIdAndNicoMylistId($videoId, null);
            if ($nicoItem->isEmpty()) {
                if ($dateStr = $record['published_at']) {
                    $record['published_at'] = Carbon::createFromFormat('Y/m/d H:i', $dateStr);
                }
                $nicoItem = $this->nicoItemRepository->insertSingle($record);
            } else {
                $nicoItem = $nicoItem->first();
            }
            $record['nico_item_id'] = $nicoItem->id;
            $record['rank_date'] = $rankDate;
            $record['kind'] = config('const.nicoRankKind.utau');
            $this->nicoRankRepository->insertSingle($record);
        }

        echo "end\n";
    }

    private function htmlAndRankDate()
    {
        $fileName = $this->argument('fileName');
        $rankDate = $this->argument('rankDate');
        if ($fileName) {
            // 引数のファイル名とランク日付を使う
            $filePath = config('const.nicoHtmlDir') . '/' . $fileName;
            if (\Storage::disk('local')->missing($filePath)) {
                $this->error('NOT file exists.');
                exit;
            } else if (!$rankDate) {
                $this->error('REQUIRE rank date. (ex:"2020-10-01")');
                exit;
            }
            $html = \Storage::disk('local')->get($filePath);
            $rankDate = Carbon::parse($rankDate);
        } else {
            // getで当日分を取得
            $context = stream_context_create(array(
                'http' => ['ignore_errors' => true]
            ));
            $html = file_get_contents(config('const.rankingFetchUrl.utau'), false, $context);
            preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
            $statusCode = $matches[1];
            if ($statusCode != '200') {
                \Log::error('fetch error');
            }
            $rankDate = Carbon::today();
        }

        return [$html, $rankDate];
    }

}
