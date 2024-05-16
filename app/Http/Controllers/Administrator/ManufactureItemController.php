<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManufactureItemRequest;
use App\Models\Item;
use App\Models\ManufactureItem;
use Illuminate\Http\Request;

class ManufactureItemController extends Controller
{
    public function index(){
        $c0 = ManufactureItem::where('item_id', 38)->sum('quantity');
        $c1 = ManufactureItem::where('item_id', 39)->sum('quantity');
        $c2 = ManufactureItem::where('item_id', 40)->sum('quantity');
        
        $data = array(
            'title'         => 'Manufacture item',
            'items'         => Item::where('type','sale')->latest()->get(),
            'c0'            => $c0,
            'c1'            => $c1,
            'c2'            => $c2,
            'manufactures'  => ManufactureItem::latest()->get(),
        );
        // dd($data['manufactures']);
        return view('admin.manufacture_item.index')->with($data);
    }

    public function store(ManufactureItemRequest $req){
        $validated = $req->validated();
        
        if(isset($validated['manufacture_id']) && !empty($validated['manufacture_id'])){
            $maufacture = ManufactureItem::findOrFail(hashids_decode($validated['manufacture_id']));
            $msg        = 'Manufacture item udpated successfully';
        }else{
            $maufacture = new ManufactureItem();
            $msg        = 'Manufacture item added successfully'; 
            
              Item::findOrFail(hashids_decode($validated['item_id']))->increment('stock_qty', $validated['quantity']);
            
        }

       
        
        $maufacture->item_id  = hashids_decode($validated['item_id']);
        $maufacture->date = $validated['date'];
        
        $maufacture->quantity = $validated['quantity'];
        $maufacture->dispatch = $validated['dispatch'];
        $maufacture->save();

        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.manufactures.index')
        ]);
    }

    public function edit($id){
        
        $c0 = ManufactureItem::where('item_id', 38)->sum('quantity');
        $c1 = ManufactureItem::where('item_id', 39)->sum('quantity');
        $c2 = ManufactureItem::where('item_id', 40)->sum('quantity');
        
        $data = array(
            'title'             => 'Edit manufacture item',
            'items'             => Item::latest()->get(),
             'c0'            => $c0,
            'c1'            => $c1,
            'c2'            => $c2,
            'manufactures'      => ManufactureItem::latest()->get(),
            'edit_manufacture'  => ManufactureItem::findOrFail(hashids_decode($id)),
            'is_update'         => true
        );
        return view('admin.manufacture_item.index')->with($data);
    }

    public function delete($id){
        $manufacture = ManufactureItem::findOrFail(hashids_decode($id));
        Item::findOrFail($manufacture->item_id)->decrement('stock_qty', $manufacture->quantity);
        $manufacture->delete();

        return response()->json([
            'success'   => 'Manufacture deleted successfully',
            'reload'    => true
        ]);
    }
}