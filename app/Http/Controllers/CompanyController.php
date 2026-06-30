<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;
use App\Http\Requests\CompanyRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Item;
use App\Models\Company;
use App\Models\OutwardDetail;
use App\Models\SaleBook;
use App\Models\AccountLedger;
use App\Models\AccountType;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth('admin')->check()) {
                return redirect()->route('login');
            }
            return $next($request);
        });
    }

    public function index(){
        if (!auth('admin')->user()->hasPermissionTo('Companies Access')) {
            abort(403, 'Unauthorized action.');
        }
        
        $data = [

            "title" => "Company",
            "categories" => Category::latest()->get(),
            "companies" => Company::with('category')->latest()->get(),
        ];

        return view('admin.company.index')->with($data);
    }
    public function store(Request $req){
        if (check_empty($req->company_id)) {
            if (!auth('admin')->user()->hasPermissionTo('Companies Edit')) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            if (!auth('admin')->user()->hasPermissionTo('Companies Create')) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        if(check_empty($req->company_id)){
            $company = Company::findOrFail(hashids_decode($req->company_id));
            $msg  = 'company updated successfully';
        }else{
            $company = new Company();
            $msg  = 'company added successfully';
        }
        
        $company->category_id      = hashids_decode($req->category);
        $company->name           = $req->name;
        $company->phone_no         = $req->phone_no ? $req->phone_no : 0 ;//item id is outward id
        $company->status           = $req->status;
        $company->address         = $req->address;
        $company->save();
        
        return response()->json([
            'success' => 'Company added successfully',
            'redirect'  => route('admin.companys.index'),
        ]);


    }

    public function edit($id){
        if (!auth('admin')->user()->hasPermissionTo('Companies Edit')) {
            abort(403, 'Unauthorized action.');
        }
        
        $data = array(
            'title'     => 'Company',
            "categories" => Category::latest()->get(),
            'edit_company' => Company::with(['category'])->where('id',hashids_decode($id))->first(),
            "companies" => Company::with('category')->latest()->get(),
            'is_update' => true,
        );
        //dd($data['item_detail']);
        return view('admin.company.index')->with($data);
    }

    public function delete($id){
        if (!auth('admin')->user()->hasPermissionTo('Companies Delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        Company::destroy(hashids_decode($id));

        return response()->json([
            'success'   => 'Company delted successfully',
            'reload'    => true,
        ]);
    }
}
