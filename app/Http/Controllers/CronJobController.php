<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\CronJobsTrait;
use Illuminate\Http\Request;

class CronJobController extends Controller
{
    use CronJobsTrait;

    public function index(Request $request){
        if(method_exists($this, $request->method)){
            return call_user_func(array($this, $request->method));
        }
        die('Method not found');
    }
}