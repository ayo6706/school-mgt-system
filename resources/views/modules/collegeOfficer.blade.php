@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="row main-header"><h3>College Officer</h3></div>
            <college-officer-home-component></college-officer-home-component>
        </div>
    </div>
@endsection
