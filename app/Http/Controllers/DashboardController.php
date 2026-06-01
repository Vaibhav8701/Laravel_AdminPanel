<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Post;
use App\Models\User;
use App\Models\Role;
use App\Models\Module;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // $postsCount = Post::count();
        $usersCount = User::count();
        $rolesCount = Role::count();
        $modulesCount = Module::count();

        return view('dashboard', compact( 'usersCount', 'rolesCount', 'modulesCount'));
    }
}
