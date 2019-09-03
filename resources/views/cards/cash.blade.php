@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <p class="float-left"><a href="{{ route('home') }}">Account ({{$user->name}})</a></p>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('cash-service-execute') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="amount" class="col-md-4 col-form-label text-md-right">Enter amount</label>

                                <div class="col-md-6">
                                    <input id="amount" type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required autofocus>

                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="card" class="col-md-4 col-form-label text-md-right">Choose card</label>
                                <div class="col-md-6">
                                    <select id="card" name="card_id" class="form-control">
                                        @foreach($user->cards as $card)
                                            <option value="{{$card->id}}">{{$card->card_number}} ({{$card->amount}})</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="type1" class="col-md-4 col-form-label text-md-right">Replenish</label>
                                <div>
                                    <input style="vertical-align: bottom;" id="type1" checked type="radio" class="@error('type1') is-invalid @enderror" value="1" name="type" required>
                                </div>
                                <label for="type2" class="col-md-2 col-form-label text-md-right">Cash out Money</label>
                                <div>
                                    <input style="vertical-align: bottom;" id="type2" type="radio" class=" @error('type2') is-invalid @enderror" value="2" name="type" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="pin" class="col-md-4 col-form-label text-md-right">Enter PIN code</label>

                                <div class="col-md-3">
                                    <input id="pin" type="text" class="form-control @error('pin') is-invalid @enderror" name="pin" required>

                                    @error('pin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Do it</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
