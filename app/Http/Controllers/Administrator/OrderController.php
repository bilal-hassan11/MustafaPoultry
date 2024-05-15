<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Account;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $data = array(
            'title'         => 'Orders',
            'items'         => Item::latest()->get(),
            'accounts'      => Account::latest()->get(),
            'orders'        => Order::with(['account'])->latest()->get(),
        );
        return view('admin.order.index')->with($data);
    }

    public function store(OrderRequest $req){

        if(isset($req->order_id) && !empty($req->order_id)){
            $order = Order::findOrFail(hashids_decode($req->order_id));
            $msg        = 'Order updated successfully';
        }else{
            $order = new Order();
            $msg        = 'Order added successfully';
        }

        $order->item_id  = hashids_decode($req->item_id);
        $order->account_id = hashids_decode($req->account_id);
        $order->qunantity = $req->quantity;
        $order->date     = date('Y-m-d', strtotime($req->date));
        $order->remarks  = $req->remarks;
        $order->save();
        
        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.orders.index'),
        ]);

    }

    public function edit($id){
        $data = array(
            'title' => 'Edit Order',
            'edit_order'  => Order::findOrFail(hashids_decode($id)),
            'items'             => Item::latest()->get(),
            'orders'      => Order::with(['item'])->latest()->get(),
            'is_update'         => true,
            'accounts'          => Account::latest()->get(),

        );
        return view('admin.order.index')->with($data);
    }

    public function delete($id){
        Order::destroy(hashids_decode($id));

        return response()->json([
            'success'   => 'Consumption deleted successfuly',
            'reload'    => true
        ]);
    }
}
