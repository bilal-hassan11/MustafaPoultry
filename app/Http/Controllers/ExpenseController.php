<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Account;
use App\Models\Item;
use App\Models\Expense;
use App\Models\AccountLedger;
use App\Models\ExpenseCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(){
       
        $data = array(
            'title'         => 'Expense Category',
            'categories'    => ExpenseCategory::latest()->get(),
        );
        return view('admin.expense.category')->with($data);
    }

    public function store(Request $req){
        
        $req->validate([
                'name'          => ['required', 'max:255'],
                'category_id'   => ['nullable']
        ]);

        if(isset($req->category_id) && !empty($req->category_id)){
            $category     = ExpenseCategory::findOrFail(hashids_decode($req->category_id));
            $msg          = 'Expense Category updated successfully';
        }else{
            $category      = new ExpenseCategory();
            $msg          = 'Expense Category added successfully';
        }

        $category->name      = $req->name;
        $category->save();

        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.expenses.index')
        ]);
    }

    public function edit($id){

        
        $data = array(
            'title'         => 'Category',
            'categories'    => ExpenseCategory::latest()->get(),
            'is_update'     => true,
            'edit_category' => ExpenseCategory::findOrFail(hashids_decode($id)),
        );
        return view('admin.expense.category')->with($data);
    }

    public function delete($id){
        ExpenseCategory::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Expense Category deleted successfully',
            'reload'    => true
        ]);
    }

    public function expense(Request $req){

       
        $data = array(
            'title' => 'Add Expense',
            'expenses' => Expense::with(['category'])->latest()->get(),
            'categories'    => ExpenseCategory::latest()->get(),
        );
        return view('admin.expense.add_expense')->with($data);
    }


    public function expensestore(Request $req){
       
        if(check_empty($req->expense_id)){
            
            $expense = Expense::findOrFail(hashids_decode($req->expense_id));
            $msg  = 'Expense updated successfully';
        
            $expense->date        = $req->date;
            $expense->category_id = hashids_decode($req->category_id);
            $expense->ammount        = $req->ammount;
            $expense->remarks     = $req->remarks;
            $expense->save();
    
            //Account Ledger
            $ac_le = AccountLedger::where('expense_id',hashids_decode($req->expense_id))->get();
                
            $ac_id = $ac_le[0]->id;
            $accountledger = AccountLedger::with(['account'])->findOrFail($ac_id);
            
            $accountledger->date               = $req->date;
            $accountledger->account_id = 0;
           
            $accountledger->expense_id      = $expense->id;
    
            $accountledger->debit            = $req->ammount ;
            $accountledger->credit           = 0;
            $accountledger->description      = $req->remarks;
            $accountledger->save();
            
        }else{
            
            $expense = new Expense;
            $msg  = 'Expense added successfully';
        
            
            $expense->date        = $req->date;
            $expense->category_id = hashids_decode($req->category_id);
            $expense->ammount        = $req->ammount;
            $expense->remarks     = $req->remarks;
            $expense->save();
    
            //Account Ledger
            $accountledger = new AccountLedger();
            
            $accountledger->date               = $req->date;
            $accountledger->account_id = 0;
            $accountledger->expense_id      = $expense->id;
            $accountledger->debit            = $req->ammount ;
            $accountledger->credit           = 0;
            $accountledger->description      = $req->remarks;
            $accountledger->save();
            
        }
        


        return response()->json([
            'success'   => $msg,
            'redirect'    => route('admin.expenses.expense'),
        ]);
    }

    public function expenseedit($id){

        $data = array(
            'title'             => 'Edit Expense',
            'expenses' => Expense::with(['category'])->latest()->get(),
            'categories'    => ExpenseCategory::latest()->get(),
            'edit_expense'         => Expense::findOrFail(hashids_decode($id)),
            'is_update'         => true
        );
        return view('admin.expense.add_expense')->with($data);
    }

    public function expensedelete($id){
        
        Expense::destroy(hashids_decode($id));

        return response()->json([
            'success'   => 'EXpense deleted successfully',
            'reload'    => true
        ]);
    }

}
