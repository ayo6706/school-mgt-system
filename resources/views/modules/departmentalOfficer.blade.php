@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="row main-header"><h3>Departmental Officer</h3></div>
        </div>
    </div>
    <department-officer-home-component></department-officer-home-component>
@endsection
