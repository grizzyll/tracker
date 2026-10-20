<?php

namespace App\Http\Controllers;

use App\Services\CareerProgress;
use App\Services\ThesisProgress;
use App\Services\ToeflProgress;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $uid = $request->user()->id;

        return view('dashboard', ThesisProgress::for($uid) + [
            'toefl'  => ToeflProgress::for($uid),
            'career' => CareerProgress::for($uid),
        ]);
    }
}