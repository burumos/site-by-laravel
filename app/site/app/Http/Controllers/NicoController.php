<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\NicoMylistRepository;
use App\Repositories\NicoItemRepository;

class NicoController extends Controller
{
    private $nicoMylistRepository;
    private $nicoItemRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        NicoMylistRepository $nicoMylistRepository,
        NicoItemRepository $nicoItemRepository
    )
    {
        $this->middleware('auth');
        $this->nicoMylistRepository = $nicoMylistRepository;
        $this->nicoItemRepository = $nicoItemRepository;
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
}
