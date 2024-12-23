<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginLogController extends Controller
{
    public function index()
    {
        // Ambil semua data login logs, dengan informasi user terkait
        $logs = LoginLog::with('user')->latest()->paginate(10);
        if (Auth::user()->role == 'petugas') {
            $logs = LoginLog::with('user')->where('user_id', Auth::user()->id)->latest()->paginate(10);
        }
        return view('login_logs.index', compact('logs'));
    }
}
