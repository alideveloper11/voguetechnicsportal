<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $asOfDate = now()->format('F j, Y');
        return view('admin.index', compact('asOfDate'));
    }
}
