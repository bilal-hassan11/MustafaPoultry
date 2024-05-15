<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $req){
        $data = array(
            'title' => 'Items',
            'items' => Item::latest()->where('type','sale')
                        ->when(isset($req->item_type), function($query) use ($req){
                            $query->where('type', $req->item_type);
                        })
                        ->when(isset($req->status), function($query) use ($req){
                            $query->where('status', $req->status);
                        })    
                        ->get(),
        );
        return view('admin.item.index')->with($data);
    }

    public function purchase_item(Request $req){
        $data = array(
            'title' => 'Purchase Items',
            'items' => Item::latest()->where('type','purchase')
                        ->when(isset($req->item_type), function($query) use ($req){
                            $query->where('type', $req->item_type);
                        })
                        ->when(isset($req->status), function($query) use ($req){
                            $query->where('status', $req->status);
                        })    
                        ->get(),
        );
        return view('admin.item.purchase_item')->with($data);
    }

    public function add(){
        $data = array(
            'title' => 'Add item',
            'categories'    => Category::where('status', 1)->latest()->get(),
        );
        return view('admin.item.add')->with($data);
    }

    public function store(ItemRequest $req){

        if(check_empty($req->item_id)){
            $item = Item::findOrFail(hashids_decode($req->item_id));
            $msg  = 'Item updated successfully';
        }else{
            $item = new Item;
            $msg  = 'Item added successfully';
        }

        $item->category_id = hashids_decode($req->category_id);
        $item->name        = $req->name;
        $item->type        = $req->type;
        $item->price       = $req->price;
        $item->stock_status= $req->stock_status;
        $item->status      = $req->item_status;
        $item->remarks     = $req->remarks;
        $item->save();

        return response()->json([
            'success'   => $msg,
            'redirect'    => route('admin.items.index'),
        ]);
    }

    public function edit($id){
        $data = array(
            'title'             => 'Edit item',
            'categories'        => Category::where('status', 1)->latest()->get(),
            'edit_item'         => Item::findOrFail(hashids_decode($id)),
            'is_update'         => true
        );
        return view('admin.item.add')->with($data);
    }

    public function delete($id){
        Item::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Item deleted successfully',
            'reload'    => true
        ]);
    }
}
