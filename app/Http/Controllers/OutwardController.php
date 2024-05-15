<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\OutwardRequest;

use App\Models\Account;
use App\Models\Outward;
use App\Models\Inward;
use App\Models\Item;
use App\Models\PurchaseBook;
use App\Models\SaleBook;
use App\Models\OutwardDetail;
use Illuminate\Http\Request;

class OutwardController extends Controller
{
    public function index(){

        $data = Outward::with(['account', 'item'])->where('vehicle_status','pending')->latest()->get();
        return response()->json($data);
    }

    public function all_items(){
        $data = Item::where('type','sale')->latest()->get();
        return response()->json($data);
    }

    public function all_accounts(){
        $data = Account::latest()->get();
        return response()->json($data);
    }

    public function acc_id(){
       $productId = SaleBook::orderBy('id', 'DESC')->first();
        return response()->json($productId);
    }

    public function get_Account(Request $req){
        
        $acc = Account::findOrFail($req->id);
            
        return response()->json($acc);
        
    }

    public function get_item(Request $req){
        
        $acc = Item::findOrFail($req->id);
            
        return response()->json($acc);
        
    }



    public function inward_report(Request $req){
        $data = array(
            'title' => 'Inward ',
            'items' => Item::latest()->get(),
            'inward'  => Inward::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get()
        );
        return view('admin.all_inwards')->with($data);
    }

    public function outward_report(Request $req){
        $data = array(
            'title' => 'Inward ',
            'items' => Item::latest()->get(),
            'inward'  => Outward::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get()
        );
        return view('admin.all_outwards')->with($data);
    }
    
    public function purchase_accounts(){
        $data = Item::where('type','purchase')->latest()->get();
        return response()->json(
            [
                'list'=> $data,
            ]);
    }

    public function save(OutwardRequest $req){
        
        
        $outward = new Outward();
        
        //dd($req->all());
        $outward->date              = $req->date;
        $outward->account_id        = $req->account_name;
        $outward->sub_dealer_name   = $req->sub_dealer_name;
        $outward->vehicle_no        = $req->vehicle_no;
        $outward->vehicle_status    = "pending";
        $outward->no_of_begs        = $req->no_of_begs;
        $outward->fare              = $req->fare_value;
        $outward->bilty_no          = $req->bilty_no;
        $outward->gp_no             = $req->gp_no;
        $outward->company_weight    = 0;
        

        $outward->first_weight      = $req->tare_weight;
        $outward->second_weight     = 0;
        $outward->weight_difference = 0;
        $outward->driver_name       = $req->driver_name;
        $outward->driver_phone_no   = $req->driver_phone_no;
        $outward->driver_status     = $req->driver_status;
        $outward->remarks           = $req->remarks;
        $outward->save();

        $outward_details = array();
        
        foreach($req->item_name AS $key=>$item){
            $outward_details[$key] = array(
                'outward_id'    => $outward->id,
                'item_id'  => $item,
                'quantity'           => $req->item_quantity[$key],
                'created_at'        => date('y-m-d H:i:s'),
                'updated_at'        => date('y-m-d H:i:s'),
            );
        }

        OutwardDetail::insert($outward_details);
        
        
        //Item::find(hashids_decode($req->item_id))->increment('stock_qty', $req->no_of_begs);//increment item stock
        
        return response()->json("success");
        
    }

    public function edit_outward(OutwardRequest $req){
        
        //dd($req->item_name);
        $outward = Outward::findOrFail($req->outward_id);

        $net_amt = 0;
        foreach($req->item_name AS $key=>$item){
                
            $outward_item = Item::findOrFail($req->item_name);
            $item_rate = $outward_item[$key]->price;
            $item_qty = $req->item_quantity[$key];

            $item_amt = $item_rate * $item_qty; 
            $net_amt += $item_amt;
            //dd($net_amt);

        }
        //dd($net_amt);
        $outward->date              = $req->date;
        $outward->account_id        = $req->account_name;
        $outward->sub_dealer_name   = $req->sub_dealer_name;
        $outward->vehicle_no        = $req->vehicle_no;
        $outward->vehicle_status    = "completed";
        $outward->no_of_begs        = $req->no_of_begs;
        $outward->fare              = $req->fare_value;
        $outward->bilty_no          = $req->bilty_no;
        $outward->gp_no             = $req->gp_no;
        $outward->first_weight      = $req->tare_weight;
        $outward->second_weight     = $req->gross_weight;
        $outward->company_weight    = $outward->second_weight - $outward->first_weight;
        $outward->weight_difference = $outward->second_weight - $outward->first_weight;;
        $outward->driver_name       = $req->driver_name;
        $outward->driver_phone_no   = $req->driver_phone_no;
        $outward->driver_status     = $req->driver_status;
        $outward->remarks           = $req->remarks;
        $outward->save();

        //Item::find(hashids_decode($req->item_id))->increment('stock_qty', $req->no_of_begs);//increment item stock
        
        return response()->json("success");
        
    }
    
    public function store(PurchaseRequest $req){
        
        if(check_empty($req->purchase_id)){
            $purchase = PurchaseBook::findOrFail(hashids_decode($req->purchase_id));
            $msg      = 'Purchase udpated successfully';
        }else{
            $purchase = new PurchaseBook();
            $msg      = 'Purchase added successfully';
        }
        // dd($req->all());
        $purchase->date              = $req->purchase_date;
        $purchase->vehicle_no        = $req->vehicle_no;
        $purchase->bilty_no          = $req->bilty_no;
        $purchase->pro_inv_no        = $req->prod_inv_no;
        $purchase->account_id        = hashids_decode($req->account_id);
        $purchase->item_id           = hashids_decode($req->item_id);
        $purchase->company_weight    = $req->company_weight;
        $purchase->party_weight      = $req->party_weight;
        $purchase->weight_difference = $req->weight_difference;
        $purchase->posted_weight     = $req->posted_weight;
        $purchase->bag_rate              = $req->rate;
        // $purchase->gross_ammount     = $req->gross_ammount;
        $purchase->fare              = $req->fare;
        // $purchase->others_charges    = $req->others_charges;
        $purchase->net_ammount       = $req->net_ammount;
        $purchase->remarks           = $req->remarks;
        $purchase->save();

        Item::find(hashids_decode($req->item_id))->increment('stock_qty', $req->company_weight);//increment item stock
        
        return response()->json([
            'success'   => $msg,
            'redirect'    => route('admin.purchases.index')
        ]);
        
    }

    public function edit(Request $req){
        
        $outward = Outward::with(['outardDetails'])->findOrFail($req->id);
        $outward_details = OutwardDetail::with(['item'])->where('outward_id',$req->id)->latest()->get();
        //dd($outward_details);
        return response()->json([
            'outward'   => $outward,
            'outward_detail'  => $outward_details,
        ]);
        
    }

    public function delete($id){
        PurchaseBook::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Purcahase deleted successfully',
            'reload'    => true
        ]);
    }
}
