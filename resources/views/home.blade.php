@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <p class="float-left">Account ({{$user->name}})</p>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <ul class="list-unstyled components text-center">
                            <li>
                                <a class="btn-primary btn btn-sm col-md-5" href="{{route('cards')}}">My cards</a>
                            </li>
                            <li>
                                <a class="btn-primary btn btn-sm col-md-5" href="{{route('cash-service')}}">Replenish / Cash out money</a>
                            </li>
                        </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
