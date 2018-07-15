@extends('layouts.client-layout')

@section('content')
    <div class="container">
        <div class="col-md-12" id="content-wrapper">
            <div class="row">
                <div class="col-lg-12">

                    <div class="clearfix">
                        <h1 class="pull-left">Create new user</h1>
                        <div class="pull-right top-page-ui">
                            <a href="{{ route('clientIndex') }}" class="btn btn-primary pull-right">
                                <i class="fa fa-chevron-circle-left fa-lg"></i> Go back
                            </a>
                        </div>
                    </div>

                    @include('client._errors-alert', ['errors' => $errors])

                    @include('client._form')

                    @isset($response->message)
                        <div class="alert alert-danger" role="alert">
                            {{ $response->message }}
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
@endsection