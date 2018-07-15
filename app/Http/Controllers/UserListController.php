<?php

namespace App\Http\Controllers;

use App\User;

class UserListController extends Controller
{
    public function index()
    {
        $users = new User();
        return view('users', ['users' => $users->getUsers()]);
    }

    /**
     * Get users
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUsers()
    {
        $userModel = new User();
        $users = $userModel->getUsers();
        return response()->json([
            'body' => view('_users-list', ['users'=> $users])->render(),
            'totalPages' => $users->lastPage()
        ]);
    }
}
