<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\County;
class CountyController extends Controller
{
    function retrieve(){
        $counties = County::orderBy('name')->get();
        $content = json_encode([$counties]);
        return response($content, Response::HTTP_OK);
    }
}
