<?php

namespace App\Http\Controllers;

use App\Services\ThesisProgress;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard', ThesisProgress::for($request->user()->id));
    }
}