<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return match ($user->role) {
            'admin'     => view('dashboard.admin'),
            'moderator' => view('dashboard.moderator'),
            'officer'   => view('dashboard.officer'),
            'member'    => view('dashboard.member'),
            default     => view('dashboard.default'),
        };
    }
}
