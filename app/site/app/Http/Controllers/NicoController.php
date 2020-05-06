<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\NicoMylistRepository;
use App\Repositories\NicoItemRepository;
use App\Repositories\NicoRankRepository;

class NicoController extends Controller
{
    private $nicoMylistRepository;
    private $nicoItemRepository;
    private $nicoRankRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        NicoMylistRepository $nicoMylistRepository,
        NicoItemRepository $nicoItemRepository,
        NicoRankRepository $nicoRankRepository
    )
    {
        $this->middleware('auth');
        $this->nicoMylistRepository = $nicoMylistRepository;
        $this->nicoItemRepository = $nicoItemRepository;
        $this->nicoRankRepository = $nicoRankRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!in_array(Auth::user()->email, \Constant::get('nico-emails'))) {
            return redirect()->route('home');
        }

        $mylists = $this->nicoMylistRepository
                 ->getWithItemsByUserId(Auth::id())
                 ->map(function($mylist) {
                     $mylist['items'] = $mylist->nicoItems;
                     unset($mylist['nico_items']);
                     return $mylist;
                 });

        return view('nico', [
            'mylists' => $mylists,
        ]);
    }

    public function registerMylist(Request $request)
    {
        $registeredMylists = $this->nicoMylistRepository
                          ->getWithItemsByUserId(Auth::id())
                          ->keyBy('origin_id');
        $mylists = $request->input();
        $mylistIds = [];

        \DB::transaction(function () use($registeredMylists, $mylists, &$mylistIds) {
            foreach ($mylists as $mylist) {
                if ($registeredMylists->has((int)$mylist['origin_id'])) {
                    // mylist:
                    $mylistId = $registeredMylists[$mylist['origin_id']]['id'];
                    $mylistIds[] = $mylistId;

                    // nico item
                    $registeredItems = $registeredMylists[$mylist['origin_id']]->nicoItems->keyBy('video_id');
                    foreach ($mylist['items'] ?? [] as $item) {
                        if (isset($item['video_id']) && !$registeredItems->has($item['video_id'])) {
                            $item['nico_mylist_id'] = $mylistId;
                            $this->nicoItemRepository->insertSingle($item);
                        }
                    }
                } else {
                    // mylist: 全て登録
                    $mylist['user_id'] = Auth::id();
                    $result = $this->nicoMylistRepository->insertSingle($mylist);
                    $mylistId = $result['id'];
                    $mylistIds[] = $mylistId;

                    // item: 全て登録
                    foreach ($mylist['items'] ?? [] as $item) {
                        $item['nico_mylist_id'] = $mylistId;
                        $this->nicoItemRepository->insertSingle($item);
                    }
                }
            }
        });

        $registeredMylists = $this->nicoMylistRepository
                          ->getWithItemsByIds($mylistIds)
                          ->map(function($mylist) {
                              $mylist->items = $mylist->nicoItems;
                              unset($mylist['nicoItems']);
                              return $mylist;
                          });
        return $registeredMylists->toJson();
    }


    public function getImage($id)
    {
        $imageDir = \Constant::get('nico-image-dir');
        $filePath = $imageDir.$id;
        if (!Auth::check()
            || !\Storage::exists($imageDir.$id)) {
            $filePath = $imageDir.'not-found.jpg';
        }

        $file = \Storage::get($filePath);
        $mimeType = \Storage::mimeType($filePath);

        return response($file)
            ->header('Content-Type', $mimeType);
    }

    public function ranking()
    {
        if (!in_array(Auth::user()->email, \Constant::get('nico-emails'))) {
            return redirect()->route('home');
        }

        $records = $this->nicoRankRepository->fetchRankDate();
        $byRankDate = [];
        foreach ($records as $item) {
            $date = $item->rank_date->format('Y-m-d');
            $byRankDate[$item->kind][$date] = [];
        }
        $data = compact('byRankDate');

        return view('nicoRanking', $data);
    }

    public function fetchRanking(Request $request)
    {
        $kind = $request->input('kind');
        $date = $request->input('date');

        $rankingRecords = $this->nicoRankRepository->fetchRankingItem($kind, $date);
        $result = [];
        foreach ($rankingRecords as $record) {
            $result[] = [
                "description" => $record->nicoItem->description,
                "link" => 'https://www.nicovideo.jp/watch/' . $record->nicoItem->video_id,
                'thumbnailImage' => $record->nicoItem->image_src,
                'title' => $record->nicoItem->title,
                'uploadDate' => $record->nicoItem->published_at->format('Y/m/d h:i'),
                'videoId' => $record->nicoItem->video_id,
            ];
        }

        return response(json_encode($result));
    }
}
