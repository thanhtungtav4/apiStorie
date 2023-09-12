<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CrawController extends Controller
{
    public function show(){
        return view('craw/index');
    }
}
