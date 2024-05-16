<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Account;
use App\Models\Inward;
use App\Models\DcDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Item;
use App\Models\PurchaseBook;
use App\Models\AccountLedger;
use App\Models\AccountType;
use Illuminate\Http\Request;

class DcController extends Controller
{
    public function index(Request $req){

        $gp_no = DcDetail::latest()->first();
        
        if($gp_no == null){
           
            $gp_no['gp_no'] = "HF-00";
        
        }else{

            $g = $gp_no['gp_no'];
        }

        $ac = explode("-",$gp_no['gp_no']);
        $p = "HF-0";
        $v = $ac[1]+ 1;
        $n = $p.$v;
        //dd($n);
        $data = array(
            'title'     => 'Dc Detail',
            'accounts'  => Account::latest()->get(),
            'gp_no' => $n,
            'items'     => Item::where('type','sale')->latest()->get(),
            'dcs' => DcDetail::with(['account', 'item'])->latest()->get(),
            'inwards'   => Inward::with(['account', 'item'])
                                    ->when(isset($req->parent_id), function($query) use ($req){
                                        $query->where('account_id', hashids_decode($req->parent_id));
                                    })
                                    ->when(isset($req->vehicle_no), function($query) use ($req){
                                        $query->where('vehicle_no', $req->vehicle_no);
                                    })
                                    ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                    })
                                    ->latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->get(), 

        );
        //dd($data['gp_no']);
        return view('admin.dc.index')->with($data);
    }

    

    public function store(Request $req){
        

        if(check_empty($req->dc_id)){
           
            $purchase = DcDetail::findOrFail(hashids_decode($req->dc_id));
            $msg      = 'Dc Detail udpated successfully';

        }else{
            
            $msg      = 'Dc Detail added successfully';

            for ($x = 0; $x < count($req->item_id); $x++) {
                
                if(isset($req->item_id[$x])){
                    $purchase = new DcDetail();
                    $purchase->date              = $req->dc_date;
                    $purchase->gp_no              = $req->gp_no;
                    $purchase->account_id        = hashids_decode($req->account_id);
                    $purchase->sub_dealar_name   = $req->sub_dealar_name;
                    $purchase->item_id           = $req->item_id[$x];
                    $purchase->no_of_bags        = $req->no_of_begs[$x];
                    $purchase->fare              = $req->fare;
                    $purchase->address              = $req->address;
    
                    $purchase->address           = $req->address;
                    $purchase->save();
                }
                
            }
        }
        
        return response()->json([
            'success'   => $msg,
            'redirect'    => route('admin.dcs.invoice',['gp_no'=>$req->gp_no])
        ]);
        
    }

    public function edit($gp_no){
        $data = array(
            'title'     => 'Purchase Book',
            'accounts'  => Account::latest()->get(),
            'items'     => Item::latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->latest()->get(), 
            'dcs' => DcDetail::with(['account', 'item'])->where('gp_no',$gp_no)->get(),
            'is_update'     => true
        );
        //dd($gp_no);
        return view('admin.dc.edit')->with($data);
    }


    public function invoice($gp_no){

        $data = array(
            'dcs' => DcDetail::with(['account', 'item'])->where('gp_no',$gp_no)->get(),
        );

        //dd($data);
        return view('admin.dc.invoice')->with($data);

        // $dcs = DcDetail::with(['account', 'item'])->where('gp_no',$gp_no)->get();

        // $pdf = Pdf::loadView('admin.dc.invoice', compact('dcs'));
        // return $pdf->download('invoice.pdf');
    }

    public function delete($id){
        PurchaseBook::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Purcahase deleted successfully',
            'reload'    => true
        ]);
    }

    
    public function migrateToPurchase($id){

        $inward   = Inward::findOrFail(hashids_decode($id));
        
        $item = Item::findOrFail($inward->item_id);
        $item_name = $item->name;
        $item_rate = $item->price;

        $purchase        = new PurchaseBook;
        $purchase_amount = $inward->item->price * $inward->	company_weight;
        $get_commission = ($inward->account->commission /100) *  $purchase_amount ;
        $get_discount = $inward->account->discount * $inward->no_of_bags;
        $get_net_discount = $get_commission +  $get_discount ;
        //dd($get_net_discount);

        //$commission      = $purchase_amount * $inward->account->commission;
        //$discount        = $inward->no_of_bags * $inward->account->discount;
        $purchase_amount -= $inward->fare;
        $purchase_amount -= $get_net_discount;
        
        $purchase->date              = $inward->date;
        $purchase->vehicle_no        = $inward->vehicle_no;
        $purchase->bilty_no          = $inward->bilty_no;
        $purchase->pro_inv_no        = 0;
        $purchase->commission        = $get_commission;
        $purchase->discount          = $get_discount;
        $purchase->account_id        = $inward->account_id;
        $purchase->item_id           = $inward->id;
        $purchase->company_weight    = $inward->company_weight;
        $purchase->party_weight      = $inward->party_weight;
        $purchase->weight_difference = $inward->weight_difference;
        $purchase->posted_weight     = $inward->posted_weight;
        $purchase->no_of_bags        = $inward->no_of_bags;
        $purchase->bag_rate          = $inward->rate;
        $purchase->fare              = $inward->fare;
        $purchase->net_ammount       = $purchase_amount;
        $purchase->remarks           = $inward->remarks;
        $purchase->save();

        //Account Ledger
        $accountledger = new AccountLedger();
       
        $account = Account::findOrFail($inward->account_id);
        $account_name = $account->name;

        $id = PurchaseBook::latest('created_at')->first();
        $accountledger->account_id = $inward->account_id;
        $accountledger->sale_id          = 0;
        $accountledger->purchase_id      = $id->id;
        $accountledger->cash_id          = 0;
        $accountledger->debit            = $purchase->net_ammount;
        $accountledger->credit           = 0;
        $accountledger->description      = 'Vehicle #'. $inward->vehicle_no . ', Bilty # '.$inward->bilty_no .',  Item:'.$item_name .',  Weight:'.$inward->company_weight.'kg'.',  Posted Weight:'.$inward->posted_weight.'kg'.',  Account #'.'['.$account->id.']'.$account->name;
        $accountledger->save();
    
        Item::find($inward->item_id)->increment('stock_qty', $inward->company_weight);//increment item stock

        return response()->json([
            'success'   => 'Inward data migrated to purchase book successfully',
            'reload'    => true
        ]);
    }

    public function allPurchase(){
        $data = array(
            'title' => 'All purchase',
            'purchases'  => PurchaseBook::with(['inward.item'])->latest()->get(),
        );
        // dd($data['purchases'][0]);
        return view('admin.purchase_book.all_purchase')->with($data);
    }

    public function editPurchase($id){
        $data = array(
            'title'         => 'Edit purchase',
            'purchases'     => PurchaseBook::with(['inward.item'])->latest()->get(),
            'edit_purchase' => PurchaseBook::findOrFail(hashids_decode($id)),
            'accounts'      => Account::latest()->get(),
            'is_update'     => true  
        );
        return view('admin.purchase_book.all_purchase')->with($data);
    }

    public function updatePurchase(Request $req){
        
        $sale = PurchaseBook::findOrFail(hashids_decode($req->purchase_book_id));
        $sale->date             = $req->date;
        $sale->pro_inv_no       = $req->pro_inv_no;
        $sale->vehicle_no       = $req->vehicle_no;
        $sale->account_id       = hashids_decode($req->account_id);
        // $sale->sub_dealer_name  = $req->sub_dealer_name;
        $sale->no_of_bags       = $req->no_of_bags;
        $sale->bag_rate         = $req->bag_rate;
        $sale->fare             = $req->fare;
        $sale->save();

        return response()->json([
            'success'   => 'purchase book updated successfully',
            'redirect'  => route('admin.sales.all_sales')
        ]);
    }
}