@extends('layouts.admin')
@section('content')

<div class="box">
    <div class="box-header with-border">
      <h2 class="box-title text-dark">Filters</h2>
    </div>
    <div class="box-body">
      <form action="{{ route('admin.items.index') }}" method="GET">
        @csrf
        <div class="row">
          <div class="col-md-4">
            <label for="">Item type</label>
            <select class="form-control" name="item_type" id="item_type">
                <option value="">Select item type</option>
                <option value="purchase">Purchase</option>
                <option value="sale">Sale</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="">status</label>
            <select class="form-control" name="status" id="status">
                <option value="">Select status</option>
                <option value="1">active</option>
                <option value="0">deactive</option>
            </select>
          </div>
          <div class="col-md-2 mt-3">
            <input type="submit" class="btn btn-primary" value="Search">
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="col-12">

<div class="box">
   <div class="box-header with-border">
     <h4 class="box-title">All Sale Item Details</h4>
   </div>
   <!-- /.box-header -->
   <div class="box-body">
       <div class="table-responsive">
         <table id="example5" class="text-fade table table-bordered" style="width:100%">
         <thead>
                <tr class="text-dark">
                    <th>Category</th>
                    <th>Item <br />Name</th>
                    <th>Available <br />Stock</th>
                    <th>Rate</th>
                    <th>Item <br /> Type</th>
                    <th>Stock <br /> Status</th>
                    <th>Item <br />Status</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
           <tbody>
                @foreach($items AS $item)
                    <tr class="text-dark">
                        <td>{{ $item->category->name }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->stock_qty }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->type }}</td>
                        <td>
                            @if($item->stock_status == 1)
                                Enabled
                            @else
                                Disabled
                            @endif
                        </td>
                        <td>
                            @if($item->status == 1)
                                Active
                            @else
                                Deactive
                            @endif
                        </td>
                        <td>{!! wordwrap($item->remarks, 10, "<br />\n", true) !!}</td>
                        <td width="120">
                            <a href="{{route('admin.items.edit', $item->hashid)}}" >
                            <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                            </a>
                            <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.items.delete', $item->hashid) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
           <tfoot>
               <tr>
                   <th>Category</th>
                   
               </tr>
           </tfoot>
       </table>
       </div>
   </div>
   <!-- /.box-body -->
 </div>
 <!-- /.box -->      
</div> 
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
@endsection