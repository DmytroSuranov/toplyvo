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
                        <form method="POST" action="{{ route('store-card') }}">
                            @csrf
                            <p class="text-center">Card creation</p>
                            <div class="form-group row">
                                <label for="code" class="col-md-4 col-form-label text-md-right">Enter PIN</label>

                                <div class="col-md-4">
                                    <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required autofocus>

                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-md-4 offset-4">
                                    <input type="submit" class="form-control btn-primary" name="submit" value="Save card" required autofocus>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
