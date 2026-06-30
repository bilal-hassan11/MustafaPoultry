<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
        if (!auth('admin')->user()->hasPermissionTo('Categories Access')) {
            abort(403, 'Unauthorized action.');
        }
        
        $data = array(
            'title'         => 'Category',
            'categories'    => Category::latest()->get(),
        );
        return view('admin.category.index')->with($data);
    }

    public function store(Request $req){
        if (isset($req->category_id) && !empty($req->category_id)) {
            if (!auth('admin')->user()->hasPermissionTo('Categories Edit')) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            if (!auth('admin')->user()->hasPermissionTo('Categories Create')) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $req->validate([
                'name'          => ['required', 'max:255'],
                'category_id'   => ['nullable']
        ]);

        if(isset($req->category_id) && !empty($req->category_id)){
            $category     = Category::findOrFail(hashids_decode($req->category_id));
            $msg          = 'Category updated successfully';
        }else{
            $category      = new Category();
            $msg          = 'Category added successfully';
        }

        $category->name      = $req->name;
        $category->save();

        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.categories.index')
        ]);
    }

    public function edit($id){
        if (!auth('admin')->user()->hasPermissionTo('Categories Edit')) {
            abort(403, 'Unauthorized action.');
        }
        
        $data = array(
            'title'         => 'Category',
            'categories'    => Category::latest()->get(),
            'is_update'     => true,
            'edit_category' => Category::findOrFail(hashids_decode($id)),
        );
        return view('admin.category.index')->with($data);
    }

    public function delete($id){
        if (!auth('admin')->user()->hasPermissionTo('Categories Delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        Category::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Category deleted successfully',
            'reload'    => true
        ]);
    }
}
