<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Http;
use Illuminate\Http\Request;

class CrawController extends Controller
{
    public function show(){
        return view('craw/index');
    }

    public function getListInterface(){
       $data =  Http::withHeaders([
            'Content-Type' => 'application/json'
       ])->get('http://api.noveltyt.net/api/v2/stories/list');
       dd($data);
    }
}
