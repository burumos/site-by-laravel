@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                  @if (session('status'))
                    <div class="alert alert-success" role="alert">
                      {{ session('status') }}
                    </div>
                  @endif

                  You are logged in!

                  @if ($user)
                    <div>name: {{ $user->name }}</div>
                    @if (in_array($user->email, \Constant::get('nico-emails')))
                      <div>
                        <a href="{{ url('nico') }}">nico</a>
                      </div>
                    @endif
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
