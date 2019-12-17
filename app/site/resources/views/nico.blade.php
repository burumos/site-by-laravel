@extends('layouts.app')

@section('content')
  <div class="container nico-mylist-container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Nico</div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center">
      <div id="nico"></div>
    </div>
  </div>
  <span style="display:none;" id="json-data" data-mylists="{{$mylists->toJson()}}"></span>
@endsection

