<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    public function config_cache(){
        Artisan::call('config:cache');
        $data = array(
            'title' => 'Config Cache',
            'output' => Artisan::output()
        );
        return view('admin.artisans.output', $data);
    }

    public function config_cache_clear(){
        Artisan::call('config:clear');
        $data = array(
            'title' => 'Config Cache Clear',
            'output' => Artisan::output()
        );
        return view('admin.artisans.output', $data);
    }
    
    public function route_cache(){
        Artisan::call('route:cache');
        $data = array(
            'title' => 'Route Cache',
            'output' => Artisan::output()
        );
        return view('admin.artisans.output', $data);
    }

    public function route_cache_clear(){
        Artisan::call('route:clear');
        $data = array(
            'title' => 'Route Cache Clear',
            'output' => Artisan::output()
        );
        return view('admin.artisans.output', $data);
    }
    
    public function cache_clear(){
        Artisan::call('cache:clear');
        $data = array(
            'title' => 'Cache Clear',
            'output' => Artisan::output()
        );
        return view('admin.artisans.output', $data);
    }

    public function route_list(){
        Artisan::call('route:list');
        $data = array(
            'title' => 'Route List',
            'output' => Artisan::output()
        );
        return view('admin.artisans.output', $data);
        return dump(Artisan::output());
    }

    public function migrate(){
        Artisan::call('migrate');
        $data = array(
            'title' => 'DB Migrate',
            'output' => Artisan::output()
        );
        return view('admin.artisans.output', $data);
    }
}