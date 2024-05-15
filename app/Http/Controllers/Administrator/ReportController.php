<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\PurchaseBook;
use App\Models\AccountLedger;
use App\Models\OutwardDetail;
use App\Models\SaleBook;
use App\Models\Inward;
use App\Models\Outward;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function itemReport(Request $req){
        $data = array(
            'title' => 'Item report',
            'items' => Item::latest()->get(),
            'purchases'  => PurchaseBook::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get()
        );
        return view('admin.report.item_report')->with($data);
    }

    public function itemReportPdf(Request $req){
        //dd($req->from_date);
        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
  
        $days = $fromDate->diffInDays($toDate);

        $data = array(
            'purchases'  => PurchaseBook::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
                                       
        );
        //dd($data['to_date']);
        $pdf = Pdf::loadView('admin.report.item_pdf', $data);
        return $pdf->download('item_report.pdf');
    }

    public function itemReportPrint(Request $req){

        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
        $days = $fromDate->diffInDays($toDate);
        
        $data = array(
            'purchases'  => PurchaseBook::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::find(hashids_decode($req->item_id)),
                                       
        );
        return view('admin.report.item_print')->with($data);
    }

    public function accountReport(Request $req){
        $data = array(
            'title' => 'Account report',
            'acounts' => Account::latest()->get(),
            'account_ledger'  => AccountLedger::when(isset($req->account_id), function($query) use ($req){
                                            $query->where('account_id', hashids_decode($req->account_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('created_at', '>=', $req->from_date)->whereDate('created_at', '<=', $req->to_date);
                                        })->latest()->get()
        );
        return view('admin.report.account_report')->with($data);
    }

    public function accountReportPdf(Request $req){
        //dd($req->from_date);
        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
  
        $days = $fromDate->diffInDays($toDate);

        $data = array(
            'account_ledger'  => AccountLedger::when(isset($req->account_id), function($query) use ($req){
                                            $query->where('account_id', hashids_decode($req->account_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('created_at', '>=', $req->from_date)->whereDate('created_at', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'account_name' =>  Account::findOrFail(hashids_decode($req->account_id)),
                                       
        );
        //dd($data['to_date']);
        $pdf = Pdf::loadView('admin.report.account_pdf', $data);
        return $pdf->download('account_report.pdf');
    }

    public function inwardReport(Request $req){
        $data = array(
            'title' => 'Inward Ledger',
            'items' => Item::latest()->get(),
            'accounts'  => Account::latest()->get(),
            'inward'  => Inward::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->account_id), function($query) use ($req){
                                            $query->where('account_id', hashids_decode($req->account_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get()
        );
        return view('admin.report.inward_report')->with($data);
    }

    public function inwardReportPdf(Request $req){
        
          //dd($req->from_date);
          $toDate = Carbon::parse($req->from_date);
          $fromDate = Carbon::parse($req->to_date);
    
          $days = $fromDate->diffInDays($toDate);
  
        $data = array(
            'inward'  => Inward::when(isset($req->item_id), function($query) use ($req){
                            $query->where('item_id', hashids_decode($req->item_id));
                        })->when(isset($req->account_id), function($query) use ($req){
                            $query->where('account_id', hashids_decode($req->account_id));
                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
        );
        $pdf = Pdf::loadView('admin.report.inward-pdf', $data);
        return $pdf->download('inward_report.pdf');
    }

    public function inwardReportPrint(Request $req){
        
        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
  
        $days = $fromDate->diffInDays($toDate);

      $data = array(
          'inward'  => Inward::when(isset($req->item_id), function($query) use ($req){
                          $query->where('item_id', hashids_decode($req->item_id));
                      })->when(isset($req->account_id), function($query) use ($req){
                          $query->where('account_id', hashids_decode($req->account_id));
                      })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                          $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                      })->latest()->get(),
          'to_date' => $req->to_date,
          'from_date' => $req->from_date,
          'days' => $days,
          'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
      );
      //window.print(view('admin.report.inward_print')->with($data));
      //$pdf = Pdf::loadView('admin.report.inward-print', $data);
      return view('admin.report.inward_print')->with($data);
  }

    public function outwardReport(Request $req){
        $data = array(
            'title' => 'Outward Ledger',
            'items' => Item::latest()->get(),
            'outward'  => Outward::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get()
        );
        return view('admin.report.outward_report')->with($data);
    }


    public function outwardReportPdf(Request $req){
        $data = array(
            'outward'  => Outward::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
        );
        $pdf = Pdf::loadView('admin.report.outward-pdf', $data);
        return $pdf->download('outward_report.pdf');
    }

    public function outwardReportPrint(Request $req){
        $data = array(
            'outward'  => Outward::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => @$days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
        );
        // $pdf = Pdf::loadView('admin.report.outward-pdf', $data);
        // return $pdf->download('outward_report.pdf');
        return view('admin.report.outward_print')->with($data);
    }

    public function purchaseBookReport(Request $req){
        $data = array(
            'title' => 'Purchase Book Report',
            'items' => Item::latest()->get(),
            'accounts'  => Account::latest()->get(),
            'purchases'  => PurchaseBook::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->when(isset($req->account_id), function($query) use ($req){
                                            $query->where('account_id', hashids_decode($req->account_id));
                                        })->latest()->get()
        );
        return view('admin.report.purchase_book_report')->with($data);
    }


    public function PurchaseReportPdf(Request $req){
        //dd($req->from_date);
        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
  
        $days = $fromDate->diffInDays($toDate);

        $data = array(
            'purchases'  => PurchaseBook::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->account_id), function($query) use ($req){
                                            $query->where('account_id', hashids_decode($req->account_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
            'account_name' =>  Account::findOrFail(hashids_decode($req->account_id)),

                                       
        );
        //dd($data['to_date']);
        $pdf = Pdf::loadView('admin.report.purchase_pdf', $data);
        return $pdf->download('purchase_book_report.pdf');
    }

    public function saleBookReport(Request $req){
        $data = array(
            'title'     => 'Sale Book Report',
            'items'     => Item::latest()->get(),
            'accounts'  => Account::latest()->get(),
            'items'     => Item::latest()->get(),
            
            'outwards'  => Outward::with(['outardDetails.item', 'account'])->latest()->get(),
            'sales' => SaleBook::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->when(isset($req->account_id), function($query) use ($req){
                                            $query->where('account_id', hashids_decode($req->account_id));
                                        })->latest()->get()
        );
        return view('admin.report.sale_book_report')->with($data);
    }

    public function SaleReportPdf(Request $req){
        //dd($req->from_date);
        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
  
        $days = $fromDate->diffInDays($toDate);

        $data = array(
            'purchases'  => SaleBook::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->account_id), function($query) use ($req){
                                            $query->where('account_id', hashids_decode($req->account_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
            'account_name' =>  Account::findOrFail(hashids_decode($req->account_id)),

                                       
        );
        //dd($data['to_date']);
        $pdf = Pdf::loadView('admin.report.sale_pdf', $data);
        return $pdf->download('sale_book_report.pdf');
    }

    public function SaleReportPrint(Request $req){
        //dd($req->from_date);
        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
  
        $days = $fromDate->diffInDays($toDate);

        $data = array(
            'purchases'  => SaleBook::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->account_id), function($query) use ($req){
                                            $query->where('account_id', hashids_decode($req->account_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
            'account_name' =>  Account::findOrFail(hashids_decode($req->account_id)),

                                       
        );
        //dd($data['to_date']);
        $pdf = Pdf::loadView('admin.report.sale_pdf', $data);
        return $pdf->download('sale_book_report.pdf');
    }
}
