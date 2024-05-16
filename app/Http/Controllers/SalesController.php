<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Account;
use App\Models\Item;
use App\Models\Outward;
use App\Models\OutwardDetail;
use App\Models\SaleBook;
use App\Models\AccountLedger;
use App\Models\AccountType;
use App\Models\DcDetail;

use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $req){

        //dd($req->all());
        $dcs = DcDetail::with(['account', 'item'])->where('gp_no',$req->gp_no)->get();

        $data = array(
            'title'     => 'Sale Book',
            'accounts'  =>Account::where('parent_id',8)->latest()->get(),
            'items'     => Item::latest()->where('type','sale')->get(),
            'sales'     => SaleBook::with(['account'])->latest()->get(),
            'outwards'  => Outward::with(['item','account'])
                                ->when(isset($req->parent_id), function($query) use ($req){
                                    $query->where('account_id', hashids_decode($req->parent_id));
                                })
                                ->when(isset($req->item_id), function($query) use ($req){
                                    $query->where('item_id', hashids_decode($req->item_id));
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
        
        return view('admin.sales_book.add_sale')->with($data);
    }


    public function allSales(){
        
        $data = array(
            'title' => 'All sales',
            'account_types' => AccountType::whereNull('parent_id')->get(), 
            'accounts'  => Account::where('parent_id',8)->latest()->get(),
            'items'     => Item::latest()->where('type','sale')->get(),
            'sales'     => SaleBook::with(['account','item'])->latest()->get(),

        );
        // dd($data['sales'][0]);
        return view('admin.sales_book.all_sales')->with($data);
    }
    
    public function store(Request $req){
       
        for ($x = 0; $x < count($req->item_id); $x++) {

            $update_outward = Outward::findOrFail(hashids_decode($req->sale_id));
            $update_outward->date = $req->sale_date;
            $update_outward->gp_no = $req->gp_no;

            $update_outward->item_id = hashids_decode($req->item_id[$x]);
            $update_outward->account_id = hashids_decode($req->account_name);
            $update_outward->sub_dealer_name = $req->sub_dealer_name;
            $update_outward->vehicle_no = $req->vehicle_no;
            $update_outward->no_of_begs = $req->bags;
            $update_outward->fare = $req->fare;
            
            $update_outward->sale_status = "completed";
            $update_outward->save();

            $sale = new SaleBook();
            $sale->date            = $req->sale_date;
            $sale->gp_no           = $req->gp_no;
            $sale->item_id           = hashids_decode($req->item_id[$x]);
            $sale->account_id      = hashids_decode($req->account_name);
            $sale->sub_dealer_name = $req->sub_dealer_name;
            $sale->vehicle_no      = $req->vehicle_no;
            $sale->bilty_no      = $update_outward->bilty_no;

            $sale->bag_rate        =  $req->rate ;
            $sale->no_of_bags       = $req->bags;
            $sale->commission      = $req->commission;
            $sale->discount        = $req->discount;
            $sale->fare              = $req->fare;
            $sale->sale_ammount        = $req->sale_ammount;
            $sale->net_ammount        = (float)$req->net_value;
            $sale->remarks              = $req->remarks;
            $sale->save();


            //Account Ledger
            $accountledger = new AccountLedger();
            $item = Item::findOrFail(hashids_decode($req->item_id[$x]));
            $item_name = $item->name;
            $account = Account::findOrFail(hashids_decode($req->account_name));
            $account_name = $account->name;
            
            $id    = SaleBook::find($sale->id);
            
            $accountledger->account_id = hashids_decode($req->account_name);
            $accountledger->sale_id          = $id->id;
            $accountledger->item_id      = hashids_decode($req->item_id[$x]);
            $accountledger->vehicle_no      = $req->vehicle_no;
            $accountledger->no_of_bags      = $req->bags;
            $accountledger->fare            = $req->fare;
            $accountledger->rate            = $req->rate;
            $accountledger->purchase_id      = 0;
            $accountledger->cash_id          = 0;
            $accountledger->debit            = $req->net_value ;
            $accountledger->credit           = 0 ;
            $accountledger->description      = 'Vehicle #'. $req->vehicle_no .', DC # '.$req->gp_no.','.$req->no_of_begs .'Bags'. ''.$item_name.',  Account #'.'['.$account->id.']'.$account->name.',  Fare '.$req->fare;
            $accountledger->save();

        }

        return response()->json([
            'success' => 'Sale added successfully',
            'redirect'  => route('admin.sales.index'),
        ]);


    }

    //Outward Editing
    public function edit($id){
        //dd($id);
        $data = array(
            'accounts'  => Account::where('parent_id',8)->latest()->get(),
            'items'     => Item::latest()->where('type','sale')->get(),
            'sales'     => SaleBook::with(['account'])->latest()->get(),
            'dcs' => DcDetail::with(['account', 'item'])->latest()->get(),
            'outwards'  => Outward::with(['item', 'account'])->latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->get(), 

            'edit_sale' => Outward::with([ 'item','account'])->where('id',$id)->get(),
            // 'edit_sale' => Outward::with(['item', 'account'])->where('id',hashids_decode($id))->latest()->get(),
            // 'item_detail' => OutwardDetail::with(['item',])->where('outward_id',hashids_decode($id))->latest()->get(),
            // 'item_count' => OutwardDetail::with(['item',])->where('outward_id',hashids_decode($id))->latest()->count(),
            'is_update' => true,
        );
        //dd($data['edit_sale']);
        return view('admin.sales_book.add_sale')->with($data);
    }

    public function delete($id){

        SaleBook::destroy(hashids_decode($id));

        $ac_id = AccountLedger::where('sale_id',hashids_decode($id))->latest()->get();
        
        AccountLedger::destroy($ac_id[0]->id);

        return response()->json([
            'success'   => 'Sale delted successfully',
            'reload'    => true,
        ]);
    }

    public function accountDetails($id){
        $account = Account::findOrFail(hashids_decode($id));
        return response()->json([
            'account'   => $account
        ]);
    }

    public function itemDetails($id){

        $item = Item::findOrFail(hashids_decode($id));
        return response()->json([
            'item'   => $item
        ]);
    }

    public function migrateToSale($id){
        
        $outward = Outward::with(['account', 'item'])->findOrFail(hashids_decode($id));
        
        $update_outward = Outward::findOrFail(hashids_decode($id));
        $update_outward->sale_status = "completed";
        $update_outward->save();

        $net_value  = 0;
        $net_value += $outward->item->price * $outward->no_of_begs;

        $comimission = ($outward->account->commission * $net_value)/100;
        $discount    = $outward->account->discount * $outward->no_of_begs;
        $total       = ($net_value - ($discount + $comimission))-$outward->fare;

        $sale = new SaleBook();
        $sale->date            = $outward->date;
        $sale->gp_no           = $outward->gp_no;
        $sale->item_id         = $outward->id;
        $sale->account_id      = $outward->account_id;
        $sale->sub_dealer_name = $outward->sub_dealer_name;
        $sale->vehicle_no      = $outward->vehicle_no;
        $sale->bag_rate        = $outward->item->price;
        $sale->no_of_bags      = $outward->no_of_begs;
        $sale->commission      = $comimission;
        $sale->discount        = $discount;
        $sale->fare            = $outward->fare;
        $sale->sale_ammount    = $net_value;
        $sale->net_ammount     = $total;
        $sale->remarks         = $outward->remarks;
        $sale->bilty_no        = $outward->bilty_no;
        $sale->save();

        //Account Ledger
        $accountledger = new AccountLedger();
        $item = Item::findOrFail($outward->item->id);
        $item_name = $item->name;
        $account = Account::findOrFail($outward->account_id);
        $account_name = $account->name;
        
        $id    = SaleBook::find($sale->id);
        
        $accountledger->account_id = $outward->account_id;
        $accountledger->sale_id          = $id->id;
        $accountledger->item_id      = $outward->item->id;
        $accountledger->vehicle_no      = $outward->vehicle_no;
        $accountledger->no_of_bags      = $outward->no_of_begs;
        $accountledger->fare            = $outward->fare;
        $accountledger->rate            = $req->rate;
        $accountledger->purchase_id      = 0;
        $accountledger->cash_id          = 0;
        $accountledger->debit            = $req->net_value ;
        $accountledger->credit           = 0 ;
        $accountledger->description      = 'Vehicle #'. $outward->vehicle_no .', DC # '.$outward->gp_no.','.$outward->no_of_begs .'Bags'. ''.$item_name.',  Account #'.'['.$account->id.']'.$account->name.',  Fare '.$outward->fare;
        $accountledger->save();

        
        return response()->json([
            'success'   => 'Sales Posted successfully',
            'reload'    => true,
        ]);
    }

    //Sales Editing
    public function editSale($id){
        $data = array(
            'title'     => 'Edit sale',
            'sales'     => SaleBook::with(['outwardDetail.item'])->latest()->get(),
            'edit_sale' => SaleBook::findOrfail(hashids_decode($id)),
            'account_types' => AccountType::whereNull('parent_id')->get(), 
            'accounts'  => Account::where('parent_id',8)->latest()->get(),
            'items'     => Item::latest()->where('type','sale')->get(),
            
            'is_update' => true
        );

        //dd($data['edit_sale']->item_id);
        return view('admin.sales_book.all_sales')->with($data);
    }

    public function updateSale(Request $req){
        //dd($req->all());
        $sale = SaleBook::findOrFail(hashids_decode($req->sale_book_id));
        
        $total_amount           = $req->bag_rate * $req->no_of_bags;
        $commission             = $req->commission;
        $discount               = $req->discount;
        $sale->date             = $req->date;
        $sale->gp_no            = $req->gp_no;
        $sale->vehicle_no       = $req->vehicle_no;
        $sale->account_id       = hashids_decode($req->account_id);
        $sale->item_id       = hashids_decode($req->item_id);
        $sale->sub_dealer_name  = $req->sub_dealer_name;
        $sale->no_of_bags       = $req->no_of_bags;
        $sale->bag_rate         = $req->bag_rate;
        $sale->fare             = $req->fare;
        $sale->sale_ammount       = ($total_amount -$req->commission)-$req->discount;
        $sale->net_ammount       = $req->net_value;
        $sale->save();


        //Account Ledger
        $accountledger = AccountLedger::where('sale_id',hashids_decode($req->sale_book_id))->latest()->get();

        $item = Item::findOrFail(hashids_decode($req->item_id));
        //dd($item);
        $item_name = $item->name;
        $account = Account::findOrFail(hashids_decode($req->account_id));
        $account_name = $account->name;
        
        $accountledger->account_id = hashids_decode($req->account_id);
        $accountledger->sale_id          = hashids_decode($req->sale_book_id);
        $accountledger->item_id      = hashids_decode($req->item_id);
        $accountledger->vehicle_no      = $req->vehicle_no;
        $accountledger->no_of_bags      = $req->no_of_bags;
        $accountledger->fare            = $req->fare;
        $accountledger->rate            = $req->bag_rate;
        $accountledger->purchase_id      = 0;
        $accountledger->cash_id          = 0;
        $accountledger->debit            = $req->net_value ;
        $accountledger->credit           = 0 ;
        $accountledger->description      = 'Vehicle #'. $req->vehicle_no .', DC # '.$req->gp_no.','.$req->no_of_begs .'Bags'. ''.$item_name.',  Account #'.'['.$account->id.']'.$account->name.',  Fare '.$req->fare;
        $accountledger->save();



        return response()->json([
            'success'   => 'Sale book updated successfully',
            'redirect'  => route('admin.sales.all_sales')
        ]);
    }

    public function deleteSale($id){
        SaleBook::destroy(hashids_decode($id));
        
        $ac_id = AccountLedger::where('sale_id',hashids_decode($id))->latest()->get();
        
        AccountLedger::destroy($ac_id[0]->id);
        
        return response()->json([
            'success'   => 'Sale deleted successfully',
            'reload'    => true
        ]);
    }
}