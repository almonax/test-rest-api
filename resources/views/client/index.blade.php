@extends('layouts.client-layout')

@section('content')
    <div class="container">
        <div class="col-md-12" id="content-wrapper">
            <div class="row" style="opacity: 1; transform: translateY(0px);">
                <div class="col-lg-12">

                    <div class="clearfix">
                        <h1 class="pull-left">Users</h1>

                        <div class="pull-right top-page-ui">
                            <a href="{{ route('clientCreate') }}" class="btn btn-primary pull-right">
                                <i class="fa fa-plus-circle fa-lg"></i> Add user
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-box clearfix">
                                <div class="table-responsive">
                                    <table id="userList" class="table user-list">
                                        <thead>
                                            <tr>
                                                <th><span>User</span></th>
                                                <th><span>Created</span></th>
                                                <th><span>Email</span></th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @include('client._users-list', ['users' => $users])
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pull-right top-page-ui">
                                    <button id="getNextUsers" class="btn btn-primary pull-right">
                                        <i class="fa fa-search-plus fa-lg"></i> Show next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
