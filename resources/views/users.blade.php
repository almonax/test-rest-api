@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <section class="row-section">
                    <div class="container">
                        <div class="row">
                            <h3 class="text-center"><span>Users</span></h3>
                        </div>
                        <div class="col-md-10 offset-md-1 row-block">
                            <ul id="userList">
                                @include('_users-list', ['users' => $users])
                            </ul>
                        </div>
                    </div>
                </section>

                <div class="row">
                    <div class="col-md-6 offset-md-3 text-center">
                        <div class="media-right align-self-center">
                            <button id="getNextUsers" class="btn btn-default contact-link">Show next</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
