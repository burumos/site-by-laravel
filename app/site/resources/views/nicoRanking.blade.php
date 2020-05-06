@extends('layouts.app')

@section('content')
  <div class="container nico-ranking-container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Nico Ranking</div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center">
      <div id="nico-ranking"></div>
    </div>
  </div>
  <script>
    const nicoRankingKind = @json(config('const.nicoRankKind'));
    const dateList = @json($byRankDate);
    const fetchRankingUrl = @json(route('fetch_nico_ranking'));
  </script>
@endsection

