@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                <h4 class="page-title">{{ (isset($is_update)) ? 'Edit' : 'Add' }} Item</h4>
                </div>
                <form action="{{ route('admin.items.store') }}" class="ajaxForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="">Category</label>
                            <select class="form-control select2" name="category_id" id="category_id">
                                    <option value="">Select category</option>
                                @foreach($categories AS $category)
                                    <option value="{{ $category->hashid }}" @if(@$edit_item->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Item Name</label>
                            <input type="text" class="form-control" placeholder="Enter item name here" value="{{ @$edit_item->name }}" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="">Item Type</label>
                            <select class="form-control select2" name="type" id="type">
                                <option value="">Select item type</option>
                                <option value="purchase" @if(@$edit_item->type == 'purchase') selected @endif>Purchase</option>
                                <option value="sale" @if(@$edit_item->type == 'sale') selected @endif>Sale</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Item Rate</label>
                            <input type="number" class="form-control" placeholder="0" value="{{ @$edit_item->price }}" min="0" name="price" id="price" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="">Item Stock Status</label>
                            <select class="form-control select2" name="stock_status" id="stock_status">
                                <option value="">Select stock status</option>
                                <option value="1" @if(@$edit_item->stock_status == 1) selected @endif>Enable</option>
                                <option value="0" @if(@$edit_item->stock_status == 1) selected @endif>Disable</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Item Status</label>
                            <select class="form-control select2" name="item_status" id="item_status">
                                <option value="">Select item status</option>
                                <option value="1" @if(@$edit_item->status == 1) selected @endif>Active</option>
                                <option value="0" @if(@$edit_item->status == 0) selected @endif>Deactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Remarks</label>
                            <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" value="{{ @$edit_item->hashid }}" name="item_id" id="item_id">
                            <input type="submit" value="{{ (isset($is_update) ? 'Update' : 'Add') }}" class="btn btn-primary mt-2 float-right" style="float:right">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>        
</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
<script>
    $('#grand_parent_id').change(function(){
        var id = $(this).val();
        var route = "{{ route('admin.common.get_parent_account', ':id') }}";
        route     = route.replace(':id', id);
        
        getAjaxRequests(route, '', 'GET', function(resp){
            $('#parent_id').html(resp.html);
        });
    });
</script>
@endsection