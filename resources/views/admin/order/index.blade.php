@extends('layouts.admin')
@section('content')

<div class="main-content app-content mt-0">
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
                    <h3 class="card-title mb-0">Add Order</h3>
                </div>
                <div class="card-body">
                
                    <form action="{{ route('admin.orders.store') }}" class="ajaxForm" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Order Date</label>
                                <input type="date" class="form-control" name="date" id="date" value="{{ isset($is_update) ? date('Y-m-d', strtotime(@$edit_order->date)) : date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="">Accounts</label>
                                <select class="form-control select2" name="account_id" id="account_id" required>
                                    <option value="">Select Account</option>
                                    @foreach($accounts AS $account)
                                        <option value="{{ $account->hashid }}" @if(@$edit_order->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Items</label>
                                <select class="form-control select2" name="item_id" id="item_id" required>
                                    <option value="">Select Item</option>
                                    @foreach($items AS $item)
                                        <option value="{{ $item->hashid }}" @if(@$edit_order->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Quantity</label>
                                <input type="number" class="form-control" name="quantity" id="quantity" value="{{ @$edit_order->qunantity }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks" cols="" rows="">{{ @$edit_order->remarks }}</textarea>
                            </div>
                        </div>
                        <input type="hidden" name="order_id" id="order_id" value="{{ @$edit_order->hashid }}">
                        <input type="submit" class="btn btn-primary mt-3" value="{{ isset($is_update) ? 'Update' : 'Add' }}">
                    </form>  
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <!-- ROW-5 END -->
       
        
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Orders Detail</h3>
                </div>
                <div class="card-body">
                
                <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                <thead>
                        <tr class="text-dark">
                            <th >S.No</th>
                            <th>Account</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Date</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders AS $order)
                            <tr class="text-dark">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->account->name }}</td>
                                <td> <span class="waves-effect waves-light btn btn-rounded btn-primary-light"> {{ @$order->item->name }} </span></td>
                                <td>{{ $order->qunantity}}</td>
                                <td>{{ date('d-M-Y', strtotime($order->date)) }}</td>
                                <td>{{ $order->remarks }}</td>
                                <td width="120">
                                    <a href="{{route('admin.orders.edit', $order->hashid)}}" >
                                    <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                                    </a>
                                    <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.orders.delete', $order->hashid) }}" class="waves-effect waves-light btn btn-rounded btn-primary-light">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            	
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