<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashBook;
use App\Models\AccountLedger;
use App\Http\Requests\CashBookRequest;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\Expense;
use Carbon\Carbon;
use App\Traits\SendsWhatsAppMessages;
use Mpdf\Mpdf;
use App\Traits\GeneratePdfTrait;

class CashController extends Controller
{
    use SendsWhatsAppMessages;
    use GeneratePdfTrait;
    public function index(Request $req)
    {
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();


        $tot_get_cr = CashBook::sum('payment_ammount');
        $tot_get_dr = CashBook::sum('receipt_ammount');
        $c_ex  = Expense::sum('ammount');

        $tot = 0;
        $Tot_net_dr = $tot_get_dr +  $tot;
        $cash_in_hand = $Tot_net_dr - ($tot_get_cr + $c_ex);
        //dd($tot_get_cr);


        $month = date('Y-m-d');
        if (isset($req->cash_from_date) && isset($req->cash_to_date)) {

            $tot_cr = CashBook::when(isset($from_date, $to_date), function ($query) use ($req) {
                $query->whereBetween('entry_date', [$req->cash_from_date, $req->cash_to_date]);
            })->sum('receipt_ammount');
            $tot_dr = CashBook::when(isset($from_date, $to_date), function ($query) use ($req) {
                $query->whereBetween('entry_date', [$req->cash_from_date, $req->cash_to_date]);
            })->sum('payment_ammount');
            $tot_ex = Expense::when(isset($from_date, $to_date), function ($query) use ($req) {
                $query->whereBetween('entry_date', [$req->cash_from_date, $req->cash_to_date]);
            })->sum('ammount');
        } else {


            $tot_cr = CashBook::wheredate('entry_date', $month)->sum('receipt_ammount');
            $tot_dr = CashBook::wheredate('entry_date', $month)->sum('payment_ammount');
            $tot_ex = Expense::wheredate('date', $month)->sum('ammount');
        }


        $data = array(
            'title'     => 'Cash Book',
            'cash_in_hand' => $cash_in_hand,
            'tot_cr' => $tot_cr,
            'tot_dr' => $tot_dr,
            'tot_ex' => $tot_ex,

            'accounts'  => Account::latest()->get()->sortBy('name'),
            'cash' => CashBook::with(['account'])
                ->when(isset($req->acc_id), function ($query) use ($req) {
                    $query->where('account_id', hashids_decode($req->acc_id));
                })
                ->when(isset($req->status), function ($query) use ($req) {
                    $query->where('status', $req->status);
                })
                ->when(isset($req->from_date, $req->to_date), function ($query) use ($req) {
                    $query->whereBetween('entry_date', [$req->from_date, $req->to_date]);
                })
                ->latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->latest()->get(),

        );
        return view('admin.cash_book.add_cash')->with($data);
    }

    public function store(CashBookRequest $req)
    {
        //check if today cash in hand


        //Edit or save  Cash In Hand
        if (check_empty($req->cash_id)) {
            $cashbook = CashBook::findOrFail(hashids_decode($req->cash_id));
            $msg      = 'Cash Book udpated successfully';
        } else {
            $cashbook = new CashBook();
            $msg      = 'Cash Book added successfully';
        }

        // No

        // //check weather payment or receipt 
        if ($req->receipt_ammount == null) {
            $cashbook->receipt_ammount    = 0;
            $cashbook->payment_ammount    = $req->payment_ammount;
        } else {
            $cashbook->receipt_ammount    = $req->receipt_ammount;
            $cashbook->payment_ammount    = 0;
        }

        $cashbook->entry_date               = $req->date;
        $cashbook->bil_no             = $req->bil_no;
        $cashbook->account_id         = hashids_decode($req->account_id);
        $cashbook->narration          = $req->narration;
        $cashbook->status             = $req->status;
        $cashbook->remarks            = $req->remarks;
        $cashbook->save();

        //Account Ledger Work 
        $account_detail = AccountLedger::where('account_id', '=', hashids_decode($req->account_id))->latest()->get();
        if (check_empty($req->cash_id)) {
            if ($req->receipt_ammount == null) {

                //Payment Received

                $ac_id = AccountLedger::where('cash_id', hashids_decode($req->cash_id))->latest()->get();
                $accountledger = AccountLedger::findOrFail($ac_id[0]->id);

                $pay_ammount = $req->payment_ammount;
                $accountledger->account_id = hashids_decode($req->account_id);

                $accountledger->date               = $req->date;
                $accountledger->cash_id             = hashids_decode($req->cash_id);
                $accountledger->debit            = $pay_ammount;
                $accountledger->credit          = 0;
                $accountledger->description            = $req->narration;
                $accountledger->save();
            } else {
                $ac_id = AccountLedger::where('cash_id', hashids_decode($req->cash_id))->latest()->get();
                $accountledger = AccountLedger::findOrFail($ac_id[0]->id);


                $pay_ammount = $req->receipt_ammount;
                $accountledger->account_id = hashids_decode($req->account_id);

                $accountledger->date               = $req->date;
                $accountledger->cash_id          = hashids_decode($req->cash_id);
                $accountledger->debit            = 0;
                $accountledger->credit           = $pay_ammount;
                $accountledger->description      = $req->narration;
                $accountledger->save();
            }
        } else {

            //check Payment and Receipt  
            if ($req->receipt_ammount == null) {
                //Payment Received
                $accountledger = new AccountLedger();

                $pay_ammount = $req->payment_ammount;
                $id = CashBook::latest('created_at')->first();
                $accountledger->account_id = hashids_decode($req->account_id);

                $accountledger->date               = $req->date;
                $accountledger->cash_id          = $id->id;
                $accountledger->debit            = $pay_ammount;
                $accountledger->credit           = 0;
                $accountledger->description            = $req->narration;
                $accountledger->save();
            } else {
                $accountledger = new AccountLedger();

                $pay_ammount = $req->receipt_ammount;
                $id = CashBook::latest('created_at')->first();
                $accountledger->account_id = hashids_decode($req->account_id);
                $accountledger->date               = $req->date;
                $accountledger->cash_id          = $id->id;
                $accountledger->debit           = 0;
                $accountledger->credit           = $pay_ammount;
                $accountledger->description      = $req->narration;
                $accountledger->save();
            }
        }
        $voucher = CashBook::with('account')->find($cashbook->id);
        $htmlContent = view('admin.cash_book.voucher_pdf', compact('voucher'))->render();
        $pdfPath = $this->generatePdf($htmlContent, 'Voucher-' . $voucher->id);
        $this->sendWhatsAppMessage($cashbook->account->phone_no, 'Voucher', $pdfPath);

        return response()->json([
            'success'   => $msg,
            'redirect'    => route('admin.cash.index')
        ]);
    }


    public function edit($id)
    {


        $i = CashBook::findOrFail(hashids_decode($id));
        $month = date('Y-m-d');
        $tot_cr = CashBook::wheredate('created_at', $month)->sum('receipt_ammount');
        $tot_dr = CashBook::wheredate('created_at', $month)->sum('payment_ammount');
        $tot_ex = Expense::wheredate('created_at', $month)->sum('ammount');

        if ($i->status == "receipt") {

            $data = array(
                'title'     => 'Edit Cash Book',
                'tot_cr' => $tot_cr,
                'tot_dr' => $tot_dr,
                'tot_ex' => $tot_ex,

                'accounts'  => Account::latest()->get()->sortBy('name'),
                'cash' => CashBook::with(['account'])->latest()->get(),
                'account_types' => AccountType::whereNull('parent_id')->latest()->get(),
                'edit_receipt' => CashBook::findOrFail(hashids_decode($id)),
                'is_update_receipt'     => true
            );
        } else {

            $data = array(
                'title'     => 'Edit Cash Book',

                'tot_cr' => $tot_cr,
                'tot_dr' => $tot_dr,
                'accounts'  => Account::latest()->get()->sortBy('name'),
                'account_types' => AccountType::whereNull('parent_id')->latest()->get(),
                'cash' => CashBook::with(['account'])->latest()->get(),
                'edit_payment' => CashBook::findOrFail(hashids_decode($id)),
                'is_update_payment'     => true
            );
        }

        return view('admin.cash_book.add_cash')->with($data);
    }

    public function getParentAccounts($id)
    {
        $parents = Account::where('parent_id', hashids_decode($id))->get();
        $html   = "<option value=''>Select account</option>";

        foreach ($parents as $parent) {
            $html .= "<option value='{$parent->hashid}'>$parent->name</option>";
        }

        return response()->json([
            'html'  => $html
        ]);
    }

    public function show($id)
    {
        $voucher = CashBook::with('account')->find($id);
        if (request()->has('generate_pdf')) {
            $html = view('admin.cash_book.voucher_pdf', compact('voucher'))->render();
            $mpdf = new Mpdf([
                'format' => 'A4-P', 'margin_top' => 10,
                'margin_bottom' => 2,
                'margin_left' => 2,
                'margin_right' => 2,
            ]);
            $mpdf->SetAutoPageBreak(true, 15);
            $mpdf->SetHTMLFooter('<div style="text-align: right;">Page {PAGENO} of {nbpg}</div>');
            return generatePDFResponse($html, $mpdf);
        }
    }
}
