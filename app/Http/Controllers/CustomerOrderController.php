<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CustomerOrderRequest;
use App\Models\CustomerOrder;
use App\Models\Sku;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class CustomerOrderController extends Controller
{
    

    public function order(Request $req){

        $customers = CustomerOrder::selectRaw('
        customer_order.id AS customer_id,
        customer_order.name AS customer_name,
        customer_order.email AS customer_email,
        customer_order.phone_no AS customer_phone,
        GROUP_CONCAT(sku.name SEPARATOR "; ") AS sku_names,
        GROUP_CONCAT(order_detail.rate SEPARATOR "; ") AS rates
        ')
        ->join('order_detail', 'customer_order.id', '=', 'order_detail.customer_order_id')
        ->join('sku', 'order_detail.sku_id', '=', 'sku.id')
        ->groupBy('customer_order.id', 'customer_order.name', 'customer_order.email', 'customer_order.phone_no')
        ->get();

        $data = array(
            'title' => 'Add Customer Order',
            'customers' => $customers,
            
        );
        
        //dd($data);

        return view('admin.CustomerOrder.add_order')->with($data);
    }

    public function orderstore(Request $req){
        
        $validator = Validator::make($req->all(), [
            'name'          => ['required', 'string'],
            'email' => ['required', 'email'], // Separate each rule with a comma
            'phone' => ['required', 'regex:/^\(\d{3}\) \d{3}-\d{4}$/u'],
            'rate'          => ['required'],
            'quantity'      => ['required'],
            'sku.*'    => ['required', 'regex:/^[a-z][0-9]\.[a-z][0-9]\.[a-z][0-9]\.[a-z][0-9]$/i'],
            ], [
                'sku.*.required' => 'SKU is required',
                'sku.*.regex'    => 'SKU must have a certain pattern (e.g., a1.b2.c3.d4)',
            ]);

            $validator->after(function ($validator) use ($req) {
                $skus = $req->input('sku');
                $uniqueSkus = array_unique($skus);
                if (count($skus) !== count($uniqueSkus)) {
                    $validator->errors()->add('sku.*', 'Duplicate SKU found');
                }
            });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Return validation errors
        }else{
            $randomNumber = rand(1000, 9999);
       
            $cutomer_order = new CustomerOrder();
            $cutomer_order->name              = $req->name;
            $cutomer_order->email             = $req->email;
            $cutomer_order->phone_no             = $req->phone;
            $cutomer_order->order_no             = $randomNumber;
            $cutomer_order->save();
            $customerId = $cutomer_order->id;

            for ($x = 0; $x < count($req->sku); $x++) {
                
                $trimmedarray = explode('.', $req->sku[$x]);
               
                for ($y = 0; $y < count($trimmedarray); $y++) {

                    //dd($y);  
                    $existingSku = Sku::where('name', $trimmedarray[$y])->where('sequence_no',$y)->first();

                    if ($existingSku) {
                        $skuId = $existingSku->id; // SKU already exists, return its ID
                    } else {
                        // SKU doesn't exist, create a new record
                        $newSku = Sku::create(['name' => $trimmedarray[$y],'sequence_no'=>$y]);
                        $skuId = $newSku->id; // Return the ID of the newly created SKU
                    }

                   
                    $order_detail = new OrderDetail();
                    $order_detail->customer_order_id          = $customerId;
                    $order_detail->sku_id                     = $skuId;
                    $order_detail->rate                       = $req->rate[$x];
                    $order_detail->quantity                   = $req->quantity[$x];
                    $order_detail->save();
                
                }    
                
            }
        }
            

        return response()->json([
            'success'   => "Your Order Have been placed Successfully!",
            'redirect'    => route('admin.orders.order'),
        ]);
    }

  
}
