@extends('layouts.admin')
@section('content')
<div class="main-content app-content mt-6">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
        <!-- PAGE-HEADER END --> <!-- ROW-1 --> 
      
        <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 --> 
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">Add Item Detail</h3>
                </div>
                <div class="card-body">
                
                <form action="{{ route('admin.items.store') }}" class="ajaxForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Category</label>
                            <select class="form-control select2" name="category_id" id="category_id">
                                    <option value="">Select category</option>
                                @foreach($categories AS $category)
                                    <option value="{{ $category->hashid }}" @if(@$edit_item->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">Companies</label>
                            <select class="form-control select2" name="company_id" id="company_id">
                                <option value="">Select Company</option>
                                @foreach($companies AS $company)
                                    <option value="{{ $company->hashid }}" @if(@$edit_item->company_id == $company->id) selected @endif>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">Item Name</label>
                            <input type="text" class="form-control" placeholder="Enter item name here" value="{{ @$edit_item->name }}" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="row">
                       

                        <div class="col-md-3 form-group">
                            <label for="">Item Unit</label>
                            <input type="text" class="form-control" placeholder="Enter Unit" value="{{ @$edit_item->unit }}"  name="unit"  required>

                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Primary Unit</label>
                            <input type="text" class="form-control" placeholder="Enter Primary Unit" value="{{ @$edit_item->primary_unit }}"  name="primary_unit"  required>

                        </div>

                        <div class="col-md-2 form-group">
                            <label for="">Item Rate</label>
                            <input type="text" class="form-control" placeholder="0" value="{{ @$edit_item->price }}" min="0" name="price" id="price" required>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="">Purchase Ammount</label>
                            <input type="text" class="form-control" placeholder="0" value="{{ @$edit_item->purchase_ammount }}" id="purchase_ammount"  name="purchase_ammount"  required>

                        </div>

                        <div class="col-md-2 form-group">
                            <label for="">Sale Ammount</label>
                            <input type="text" class="form-control" placeholder="0" value="{{ @$edit_item->sale_ammount }}"  name="sale_ammount" id="sale_ammount" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Item Stock Status</label>
                            <select class="form-control select2" name="stock_status" id="stock_status">
                                <option value="">Select stock status</option>
                                <option value="1" @if(@$edit_item->stock_status == 1) selected @endif>Enable</option>
                                <option value="0" @if(@$edit_item->stock_status == 1) selected @endif>Disable</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
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
          <!-- COL END --> 
        </div>
        <!-- ROW-5 END -->
        
       
    </div>
    <!-- CONTAINER END --> 
  </div>
</div>



@endsection

@section('page-scripts')

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