<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $data = array(
            'title'         => 'Category',
            'categories'    => Category::latest()->get(),
        );
        return view('admin.category.index')->with($data);
    }

    public function store(Request $req){
        
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
        $data = array(
            'title'         => 'Category',
            'categories'    => Category::latest()->get(),
            'is_update'     => true,
            'edit_category' => Category::findOrFail(hashids_decode($id)),
        );
        return view('admin.category.index')->with($data);
    }

    public function delete($id){
        Category::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Category deleted successfully',
            'reload'    => true
        ]);
    }
}
