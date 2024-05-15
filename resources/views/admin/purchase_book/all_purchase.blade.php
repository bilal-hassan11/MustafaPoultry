
@extends('layouts.admin')
@section('content')

<div class="main-content"> 
    @if(isset($is_update) && $is_update)
    <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title text-dark">Edit Purchase</h2>
        </div>
        <div class="box-body">
            <form action="{{ route('admin.purchases.update_purchase') }}" class="ajaxForm" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Date</label>
                        <input type="date" class="form-control" name="date" id="sale" value="{{ $edit_purchase->date }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="">Vehicle No</label>
                        <input type="text" class="form-control" name="vehicle_no" id="vehicle_no" value="{{ $edit_purchase->vehicle_no }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="">Pro INV no</label>
                        <input type="text" class="form-control" name="pro_inv_no" id="pro_inv_no" value="{{ $edit_purchase->pro_inv_no }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Account Name</label>
                        <select class="form-control" name="account_id" id="account_id" required>
                            <option value="">Select account</option>
                            @foreach($accounts AS $account)
                                <option value="{{ $account->hashid }}" @if($account->id == $edit_purchase->account_id) selected @endif>{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="col-md-4">
                        <label for="">Subdealer Name</label>
                        <input type="text" class="form-control" name="sub_dealer_name" id="sub_dealer_name" value="{{ $edit_purchase->sub_dealer_name }}" required>
                    </div> --}}
                    <div class="col-md-4">
                        <label for="">No of Bags</label>
                        <input type="text" class="form-control" name="no_of_bags" id="no_of_bags" value="{{ $edit_purchase->no_of_bags }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Bag Rate</label>
                        <input type="text" class="form-control" name="bag_rate" id="bag_rate" value="{{ $edit_purchase->bag_rate }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="">Fare</label>
                        <input type="text" class="form-control" name="fare" id="fare" value="{{ $edit_purchase->fare }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="">Discount</label>
                        <input type="text" class="form-control" disabled name="discount" id="discount" value="{{ $edit_purchase->discount }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Commission</label>
                        <input type="text" class="form-control" disabled name="commission" id="commission" value="{{ $edit_purchase->commission }}" required> 
                    </div>
                    <div class="col-md-4">
                        <label for="">Net Value</label>
                        <input type="text" class="form-control" disabled name="net_ammount" id="net_ammount" value="{{ $edit_purchase->net_ammount }}" required>
                    </div>
                </div>
                <input type="hidden" name="purchase_book_id" value="{{ $edit_purchase->hashid }}">
                <input type="submit" class="btn btn-primary" value="Update">
            </form>
        </div>
    </div>
    @endif
      <div class="box">
			<div class="box-header with-border">
				<h2 class="box-title text-dark">All Sales Entries</h2>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					  <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
						<thead>
							<tr class="text-dark">
                                <th>Id.No</th>
                                <th>Date</th>
                                <th> Vehicle No </th>
                                <th>pro inv no</th>
                                <th> Account Name </th>
                                <th> Item Name </th>
                                <th> No Of Bags </th>
                                <th> Rate </th>
                                <th> Fare </th>
                                <th> Discount </th>
                                <th> Commission </th>
                                <th> Net Value </th>
                                <th>Action</th>
							</tr>
						</thead>
						<tbody>
                            @foreach($purchases AS $purchase)
                                <tr style="border-color:black;">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('m-D-Y', strtotime($purchase->date)) }}</td>
                                    <td>{{ $purchase->vehicle_no }}</td>
                                    <td>{{ $purchase->pro_inv_no }}</td>
                                    <td><span class="waves-effect waves-light btn btn-rounded btn-primary-light">{{ @$purchase->account->name }}</span> </td>
                                    <td>{{ $purchase->inward->item->name }}</td>
                                    <td>{{ $purchase->no_of_bags }}</td>
                                    <td>{{ $purchase->bag_rate }}</td>
                                    <td>{{ $purchase->fare }}</td>
                                    <td>{{ @$purchase->account->discount }}</td>
                                    <td>{{ @$purchase->account->commission }}</td>
                                    <td>{{ @$purchase->net_ammount }}</td>
                                    
                                    <td width="120">
                                        <a href="{{route('admin.purchases.edit_purchase', $purchase->hashid)}}" >
                                        <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                                        </a>
                                        <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.sales.delete_sale', ['id'=>$purchase->hashid]) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
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
             </div> 
           </div>
         </div>
      </div>      
</div>
 @endsection

@section('page-scripts')
@include('admin.partials.datatable')
@endsection