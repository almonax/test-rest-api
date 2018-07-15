@extends('layouts.client-layout')

@section('content')
    <div class="container">
        <div class="col-md-12" id="content-wrapper">
            <div class="row">
                <div class="col-lg-12">

                    <div class="clearfix mb-3">
                        <h1 class="pull-left">Update user</h1>
                        <div class="pull-right top-page-ui">
                            <a href="{{ route('clientIndex') }}" class="btn btn-primary pull-right">
                                <i class="fa fa-chevron-circle-left fa-lg"></i> Go back
                            </a>
                        </div>
                    </div>

                    @include('client._errors-alert', ['errors' => $errors])

                    @if($response->success)
                        @include('client._form', ['user' => $response->data])
                    @else
                        <div class="alert alert-danger" role="alert">
                            {{ $response->message }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection