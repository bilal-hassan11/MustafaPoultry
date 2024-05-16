<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Account;
use App\Models\Inward;
use App\Models\DcDetail;
use App\Models\OthersDc;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Item;
use App\Models\PurchaseBook;
use App\Models\AccountLedger;
use App\Models\AccountType;
use Illuminate\Http\Request;

class OthersDcController extends Controller
{
    public function index(Request $req){

        $gp_no = OthersDc::latest()->first();
        
        if($gp_no == null){
           
            $gp_no['gp_no'] = "DCO-00";
        
        }else{

            $g = $gp_no['gp_no'];
        }

        $ac = explode("-",$gp_no['gp_no']);
        $p = "DCO-0";
        $v = $ac[1]+ 1;
        $n = $p.$v;
        //dd($n);
        $data = array(
            'title'     => 'Others Dc Detail',
            'accounts'  => Account::latest()->get(),
            'gp_no' => $n,
            'items'     => Item::where('type','sale')->latest()->get(),
            'dcs' => OthersDc::with(['account', 'item'])->latest()->get(),
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
        return view('admin.othersdc.index')->with($data);
    }

    

    public function store(Request $req){
        
        if(check_empty($req->dc_id)){
           
            $dc = OthersDc::findOrFail(hashids_decode($req->dc_id));
            $msg      = 'Others Dc Detail udpated successfully';

            if(isset($req->item_id)){
                $dc->date              = $req->dc_date;
                $dc->gp_no              = $req->gp_no;
                $dc->account_id        = hashids_decode($req->account_id);
                $dc->vehicle_no   = $req->vehicle_no;
                $dc->item_id           = $req->item_id;
                $dc->item_type        = $req->item_type;
                $dc->type        = $req->type;
                $dc->rate              = $req->rate;
                $dc->net_ammount              = $req->net_ammount;
    
                $dc->remarks              = $req->remarks;
                $dc->save();
            }

            return response()->json([
                'success'   => $msg,
                'redirect'    => route('admin.othersdcs.index')
            ]);
            

        }else{

            $dc = new OthersDc();
            $msg      = 'Others Dc Detail added successfully';

            if(isset($req->item_id)){
                $dc->date              = $req->dc_date;
                $dc->gp_no              = $req->gp_no;
                $dc->account_id        = hashids_decode($req->account_id);
                $dc->vehicle_no   = $req->vehicle_no;
                $dc->item_id           = $req->item_id;
                $dc->item_type        = $req->item_type;
                $dc->type        = $req->type;
                $dc->rate              = $req->rate;
                $dc->net_ammount              = $req->net_ammount;
    
                $dc->remarks              = $req->remarks;
                $dc->save();
            }

            return response()->json([
                'success'   => $msg,
                'redirect'    => route('admin.othersdcs.invoice',['gp_no'=>$req->gp_no])
            ]);
            
        }

        

        
        
    }

    public function edit($gp_no){
        $data = array(
            'title'     => 'Purchase Book',
            'accounts'  => Account::latest()->get(),
            'items'     => Item::latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->latest()->get(), 
            'dcs' => OthersDc::with(['account', 'item'])->where('gp_no',$gp_no)->get(),
            'is_update'     => true
        );
        //dd($gp_no);
        return view('admin.othersdc.edit')->with($data);
    }


    public function invoice($gp_no){

        $data = array(
            'dcs' => OthersDc::with(['account', 'item'])->where('gp_no',$gp_no)->get(),
        );

        //dd($data);
        return view('admin.othersdc.invoice')->with($data);

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

    
}
