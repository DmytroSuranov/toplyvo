@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <p class="float-left"><a href="{{ route('home') }}">Account ({{$user->name}})</a></p>
                        @if($errors->has('msg'))
                            <p class="invalid-feedback" role="alert" style="display: inline-block;">
                                <strong>{{ $errors->get('msg')[0] }}</strong>
                            </p>
                        @endif

                        @if(session()->has('success'))
                            <p class="valid-feedback " role="alert" style="display: inline-block;">
                                <strong>{{ session()->get('success') }}</strong>
                            </p>
                        @endif
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Cards Number</th>
                                <th scope="col">Current Amount</th>
                                <th scope="col">Creation date</th>
                                <th scope="col"></th>
                                <th scope="col"><a href="{{route('add-card')}}" class="btn-outline-primary btn-sm btn">Add
                                        card</a></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($user->cards->count() > 0)
                                @foreach($user->cards as $key => $card)
                                    @php
                                        $count = ++$key;
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $count }}</th>
                                        <td>
                                            {{$card->card_number}}
                                            <a data-toggle="collapse" href="#cardInfo{{$count}}" role="button" aria-expanded="false" aria-controls="cardInfo{{$count}}">
                                                (view history)
                                            </a>
                                            <div class="collapse col-12" id="cardInfo{{$count}}">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>Amount</th>
                                                        <th>Date of transtaction</th>
                                                    </tr>
                                                    @foreach($card->history as $item)
                                                        <tr>
                                                            <th>{{$item->types[$item->type]}}
                                                                @if($item->type > 2)
                                                                    {{$item->recipient_card_number}}
                                                                @endif
                                                            </th>
                                                            <th>{{$item->amount}}</th>
                                                            <th>{{$item->created_at}}</th>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </td>
                                        <td>{{$card->amount}}</td>
                                        <td>{{$card->date}}</td>
                                        <td>
                                            <form  action="{{ route('transfer-money') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="card_id" value="{{$card->id}}">
                                                <button type="submit" class="btn btn-outline-success btn-sm">Send money</button>
                                            </form>
                                        </td>
                                        <td><a href="{{ route('delete-card', ['card' => $card->id]) }}" class="btn-outline-warning btn-sm btn">Delete</a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>You don't have any cards</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
