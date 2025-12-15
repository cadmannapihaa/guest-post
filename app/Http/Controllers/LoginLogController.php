<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;

class LoginLogController extends Controller
{
    public function index()
    {
        $logs = LoginLog::with('user')->latest()->paginate(30);
        return view('loginlogs.index', compact('logs'));
    }
}
