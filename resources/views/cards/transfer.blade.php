@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <p class="float-left"><a href="{{ route('home') }}">Account ({{$user->name}})</a></p>
                        <p class="float-right">Balance : {{$card->amount}}  </p>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('send-money') }}">
                            @csrf
                            <input type="hidden" name="card_id" value="{{$card->id}}">
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
                                <label for="cards" class="col-md-4 col-form-label text-md-right">Enter card number</label>

                                <div class="col-md-6">
                                    <input id="cards" type="number" class="form-control typeahead @error('card') is-invalid @enderror" name="card" required autocomplete="off">

                                    @error('card')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $('#cards').typeahead({
                                            source: function (query, result) {
                                                $.ajax({
                                                    url: '{{route('get-cards')}}',
                                                    data: 'query=' + query + '&_token={{csrf_token()}}',
                                                    dataType: "json",
                                                    type: "POST",
                                                    success: function (data) {
                                                        result($.map(data, function (item) {
                                                            return item;
                                                        }));
                                                    }
                                                });
                                            }
                                        });
                                    });
                                </script>

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
                                    <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
