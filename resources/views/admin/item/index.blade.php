@extends('layouts.admin')
@section('content')
<div class="main-content app-content mt-6">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
        <!-- PAGE-HEADER END --> <!-- ROW-1 --> 
      
        <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 --> 
  
        <!-- ROW-5 END -->
        
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0"> All Items Filters</h3>
                </div>
                <div class="card-body">
                <form action="{{ route('admin.items.index') }}" method="GET">
                    @csrf
                    <div class="row">
                      <div class="col-md-4">
                        <label for="">Items </label>
                        <select class="form-control select2" name="item_id" id="item_id">
                            <option value="">Select Item Name</option>
                            @foreach($getitems AS $item)
                              <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label for="">status</label>
                        <select class="form-control select2" name="status" id="status">
                            <option value="">Select status</option>
                            <option value="1">active</option>
                            <option value="0">deactive</option>
                        </select>
                      </div>
                      <div class="col-md-2 mt-6">
                        <input type="submit" class="btn btn-primary" value="Search">
                      </div>
                    </div>
                  </form>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Sale Items Detail</h3>
                </div>
                <div class="card-body">
                
                <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                <thead>
                <tr class="text-dark">
                    <th>Category</th>
                    <th>Item <br />Name</th>
                    <th>Available <br />Stock</th>
                    <th>Rate</th>
                    <th>Stock value</th>
                    <th>Item <br /> Type</th>
                    <th>Stock <br /> Status</th>
                    <th>Item <br />Status</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
           <tbody>
               <?php $tot = 0; ?>
                @foreach($items AS $item)
                    <tr class="text-dark">
                        <td>{{ $item->category->name }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->stock_qty }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <?php $tot +=  $item->price * $item->stock_qty; ?>
                        <td>{{ $item->price * $item->stock_qty }}</td>
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
                            <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>

                            </a>
                            <!-- <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.items.delete', $item->hashid) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
                                <i class="fas fa-trash"></i>
                            </button> -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
           <tfoot>
               <tr class="text-dark">
                   <td colspan="4">Total:</td>
                   <td>{{$tot}}</td>
               </tr>
           </tfoot>
            	
				        </table>
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
    </div>
    <!-- CONTAINER END --> 
  </div>
</div>



@endsection

@section('page-scripts')
@include('admin.partials.datatable')
@endsection