<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumptionRequest;
use App\Models\Consumption;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsumptionController extends Controller
{
    public function index(Request $req){
        $data = array(
            'title'         => 'Consumption',
            'items'         => Item::latest()->get(),
            'consumptions'  => Consumption::with(['item'])
                                ->when(isset($req->item_id), function($query) use ($req){
                                    $query->where('item_id', hashids_decode($req->item_id));
                                })
                                ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                    $query->where('date', [$req->from_date, $req->to_date]);
                                })
                                ->latest()->get(),
        );
        return view('admin.consumption.index')->with($data);
    }

    public function store(ConsumptionRequest $req){

        if(isset($req->consumption_id) && !empty($req->consumption_id)){
            $consumption = Consumption::findOrFail(hashids_decode($req->consumption_id));
            $msg        = 'Consumption updated successfully';
        }else{
            $consumption = new Consumption();
            $msg        = 'Consumption added successfully';
        }

        $consumption->item_id  = hashids_decode($req->item_id);
        $consumption->qunantity = $req->quantity;
        $consumption->date     = date('Y-m-d', strtotime($req->date));
        $consumption->save();
        
        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.consumptions.index'),
        ]);

    }

    public function edit($id){
        $data = array(
            'title' => 'Edit Consumption',
            'edit_consumption'  => Consumption::findOrFail(hashids_decode($id)),
            'items'         => Item::latest()->get(),
            'consumptions'  => Consumption::with(['item'])->latest()->get(),
            'is_update'         => true
        );
        return view('admin.consumption.index')->with($data);
    }

    public function delete($id){
        Consumption::destroy(hashids_decode($id));

        return response()->json([
            'success'   => 'Consumption deleted successfuly',
            'reload'    => true
        ]);
    }
}
