<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Consumption;
use App\Models\SaleBook;
use App\Models\PurchaseBook;
use App\Models\Item;
use App\Models\Inward;
use App\Models\Outward;
use App\Models\Formulation;
use App\Models\Account;
use App\Models\Staff;
use Carbon\Carbon;


class DetailViewController extends Controller
{
    public function showMap(){
        $label = ['Shirts', 'T-Shirt', 'Pants', 'Coat', 'Kurta', 'Pajama'];
        $price = ['10', '5', '100', '90', '50', '30'];
        return view('showMap',['labels' => $label, 'prices' => $price]);
    }

    public function active_item(){

        $data = [
         
            'active_item' => Item::where('status','1')->latest()->get(),
        ];
//dd($data['active_item']);
        return view('admin.detailview.active_item',$data);
    }
}
