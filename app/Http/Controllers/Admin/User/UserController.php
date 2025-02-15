<?php 

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{

    public function index()
    {
        $users = User::withCount('orders')->get();
        return view('admin.users.index' , compact('users'));
    }
}