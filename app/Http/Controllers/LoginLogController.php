<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;

class LoginLogController extends Controller
{
    public function index()
    {
        // Ambil semua data login logs, dengan informasi user terkait
        $logs = LoginLog::with('user')->latest()->paginate(10);

        return view('login_logs.index', compact('logs'));
    }
}
