@extends('layouts.admin')
@section('content')


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    {{-- <h4 class="header-title">Add Account Type</h4> --}}
                </div>
                <form action="{{ route('admin.formulations.store') }}" class="ajaxForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-dark">Sale Items </label>
                            <select class="form-control select2" name="sale_item_id" id="sale_item_id">
                                <option value="">Select sale item</option>
                                @foreach($sale_items AS $item)
                                    <option value="{{ $item->hashid }}" @if(@$edit_formulation->sale_item_id == $item->id) selected @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label for="">Weight</label>
                            <select class="form-control select2" name="sale_weight" id="sale_weight" required>
                                <option value="">Select sale weight</option>
                                <option value="500" @if(@$edit_formulation->sale_weight == '500') selected @endif>500 KG</option>
                                <option value="1000" @if(@$edit_formulation->sale_weight == '1000') selected @endif>1000 KG</option>
                                <option value="2000" @if(@$edit_formulation->sale_weight == '2000') selected @endif>2000 KG</option>
                                <option value="4000" @if(@$edit_formulation->sale_weight == '4000') selected @endif>4000 KG</option>
                                <option value="8000" @if(@$edit_formulation->sale_weight == '8000') selected @endif>8000 KG</option>
                                <option value="10000" @if(@$edit_formulation->sale_weight == '10000') selected @endif>10000 KG</option>
                            </select>
                        </div>
                    </div><br /> 
                    @if(!isset($is_update))
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="">Purchase Items</label>
                                <select class="form-control purchase_item_id select2" name="purchase_item_id[]" id="purchase_item_id" required>
                                    <option value="">Select sale item</option>
                                    @foreach($purchase_items AS $item)
                                        <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="">Weight</label>
                                <input type="number" class="form-control" name="purchase_weight[]" id="purchase_weight" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary mt-3 add_row">+</button>
                            </div>
                        </div>
                    @endif
                    @if(isset($is_update))
                        @foreach($edit_formulation->formulation_details()->get() AS $details)
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="">Purchase Items</label>
                                    <select class="form-control purchase_item_id select2" name="purchase_item_id[]" id="purchase_item_id" required>
                                        <option value="">Select sale item</option>
                                        @foreach($purchase_items AS $item)
                                            <option value="{{ $item->hashid }}" @if($details->purhcase_item_id == $item->id) selected @endif>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="">Weight</label>
                                    <input type="number" class="form-control" name="purchase_weight[]" id="purchase_weight" value="{{ $details->quantity }}" required>
                                </div>
                                <div class="col-md-2">
                                    @if($loop->iteration == 1)
                                        <button type="button" class="btn btn-primary mt-3 add_row">+</button>
                                    @else
                                        <button type="button" class="btn btn-primary mt-3 add_row">+</button>'+
                                        <button type="button" class="btn btn-danger mt-3 remove_row" onclick="ajaxRequest(this)" data-url="{{ route('admin.formulations.delete_row', $details->hashid) }}">x</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="btn_div"></div>
                        <input type="hidden" name="formulation_id" id="formulation_id" value="{{ @$edit_formulation->hashid }}">
                        <input type="submit" class="btn btn-primary float-right" value="{{ isset($is_update) ? 'Update' : 'Add' }}">
                    </div>
                </form>
            </div>
        </div>
    </div>        
</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
{{-- <script>
    $('#grand_parent_id').change(function(){
        var id = $(this).val();
        var route = "{{ route('admin.common.get_parent_account', ':id') }}";
        route     = route.replace(':id', id);
        
        getAjaxRequests(route, '', 'GET', function(resp){
            $('#parent_id').html(resp.html);
        });
    });
</script> --}}
<script>
    $(document).on('click', '.add_row', function(e){
        e.preventDefault();
        var html = '<div class="row">'+
                        '<div class="col-md-6 form-group">'+
                            '<label>Purchase Item</label>'+
                            '<select class="form-control purchase_item_id select2" name="purchase_item_id[]" id="" required>'+
                                '<option value="">Select purchase item</option>';
                        
                                @foreach($purchase_items AS $item)
        html    +=                  '<option value='+"{{ $item->hashid }}"+'>'+"{{ $item->name }}"+'</option>';
                                @endforeach   
        html  +=           '</select>'+
                        '</div>'+
                        '<div class="col-md-4 form-group">'+
                            '<label for="">Weight</label>'+
                            '<input type="number" class="form-control" name="purchase_weight[]" id="purchase_weight" required>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<button type="button" class="btn btn-primary mt-3 add_row">+</button>'+
                            '<button type="button" class="btn btn-danger mt-3 remove_row">x</button>'+
                        '</div>'+
                    '</div>';
        $('.btn_div').before().append(html);
    });

    $(document).on('click', '.remove_row', function(e){//remove row
        e.preventDefault();
        $(this).parent().parent().remove();
    });

    //check product quantity
    $(document).on('change', '.purchase_item_id', function(){
        var item_id = $(this).val();
        var route   = "{{ route('admin.formulations.check_product_qty', ':id') }}";
        route       = route.replace(':id', item_id);
        
        if(item_id != ''){
            getAjaxRequests(route, '', 'GET', function(resp){
                Swal.fire(
                    'Item stock quantity',
                    'Item current stock quantity is '+resp.stock,
                    'info'
                )
            });
        }

    });
</script>
@endsection