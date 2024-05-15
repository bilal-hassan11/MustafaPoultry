<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormulationRequest;
use App\Models\Formulation;
use App\Models\FormulationDetail;
use App\Models\Item;
use Illuminate\Http\Request;

class FormulationController extends Controller
{
    public function index(){
        $data = array(
            'title' => 'All formulations',
            'formulations'  => Formulation::latest()->get(),
        );
        return view('admin.formulation.index')->with($data);
    }

    public function add(){
        $data = array(
            'title' => 'Add Formulation',
            'sale_items' => Item::where('type', 'sale')->latest()->get(),
            'purchase_items' => Item::where('type', 'purchase')->latest()->get(),
        );
        return view('admin.formulation.add')->with($data);
    }

    public function store(FormulationRequest $req){
        
        $validated = $req->validated();
        
        if($validated['sale_weight'] != array_sum($validated['purchase_weight'])){//give error when purchase item and sale item weight are not equal
            return response()->json([
                'error' => 'Sale item weight and purchase item weight is not equal',
            ]);
        }
        
        if(isset($validated['formulation_id']) && !empty($validated['formulation_id'])){
            $formulation = Formulation::find(hashids_decode($validated['formulation_id']));
            $formulation->formulation_details()->delete();
            $msg        = 'Formulation updated successfully';
        }else{
            $formulation = new Formulation();
            $msg         = 'Formulation added successfully';
        }

        $formulation->sale_item_id         = hashids_decode($validated['sale_item_id']);
        $formulation->sale_weight          = $validated['sale_weight'];
        $formulation->total_purchase_weight = array_sum($validated['purchase_weight']);
        $formulation->save();
        
        $formulation_details = array();
        
        foreach($validated['purchase_item_id'] AS $key=>$purhcase_item){
            $formulation_details[$key] = array(
                'formulation_id'    => $formulation->id,
                'purhcase_item_id'  => hashids_decode($purhcase_item),
                'quantity'           => $validated['purchase_weight'][$key],
                'created_at'        => date('y-m-d H:i:s'),
                'updated_at'        => date('y-m-d H:i:s'),
            );
        }

        FormulationDetail::insert($formulation_details);

        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.formulations.index') 
        ]);
    }

    public function checkProductQuantity($id){
        $item = Item::findOrFail(hashids_decode($id));
        return response()->json([
            'stock' => $item->stock_qty
        ]);
    }

    public function edit($id){

        $data = array(
            'title'             => 'Edit formulation',
            'is_update'         => true,
            'sale_items' => Item::where('type', 'sale')->latest()->get(),
            'purchase_items' => Item::where('type', 'purchase')->latest()->get(),
            'edit_formulation'  => Formulation::findOrFail(hashids_decode($id)),
        );

        return view('admin.formulation.add')->with($data);
    }

    public function deleteRow($id){
        FormulationDetail::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Sale row deleted successfully',
            'reload'    => true
        ]);
    }

    public function delete($id){
        $formulation = Formulation::find(hashids_decode($id));
        $formulation->formulation_details()->delete();
        $formulation->delete();

        return response()->json([
            'success'   => 'Formulation deleted successfully',
            'reload'    => true
        ]);
    }

    public function view($id){
        $data = array(
            'title' => 'Formulation details',
            'details'   => Formulation::with(['item', 'formulation_details'])->where('id', hashids_decode($id))->first(),
        );
        return view('admin.formulation.details')->with($data);
    }
}
