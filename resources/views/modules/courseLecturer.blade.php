@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            {{--<div class="col-md-8">--}}
                {{--<div class="card">--}}
                    {{--<div class="card-header">Dashboard</div>--}}

                    {{--<div class="card-body">--}}
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
            <div class="row main-header"><h3>Course Lecturer</h3></div>
                            {{--<router-link to="/foo">Go to Foo</router-link>--}}
                            {{--<router-link to="/bar">Go to Bar</router-link>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
    <lecturer-home-component></lecturer-home-component>
@endsection
