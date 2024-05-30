
@extends('layouts.admin')
@section('content')
@isset($is_update)
  @php 
        
        $purchase_amount = $edit_purchase->item->price * $edit_purchase->company_weight;//5600
        $get_commission = ($purchase_amount *$edit_purchase->account->commission)  /100 ;//280
        $gross_Ammount = $get_commission + $purchase_amount + $edit_purchase->loading_charges;//
        
        $net_ammount = $gross_Ammount + $edit_purchase->fare;
        

  @endphp
@endisset



<div class="main-content app-content mt-0">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
        <!-- PAGE-HEADER END --> <!-- ROW-1 --> 
      
        <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 -->
        @if(isset($is_update) && $is_update) 
          <div class="row">
            <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Inward Detail</h3>
                </div>
                <div class="card-body">
                  <form class="ajaxForm" role="form" action="{{ route('admin.purchases.update_purchase') }}" method="POST">
                    @csrf
                    <div  class="row" >
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Date</label>
                          <input class="form-control" type="date" name="purchase_date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_purchase->date)) : date('Y-d-d') }}"
                          required>
                        </div>
                      </div>
                      <div class="col-md-3">
                            <div class="form-group">
                                <label>Vehical No</label>
                                <input class="form-control" name="vehicle_no" type="text" id="remarks"  value="{{ @$edit_purchase->vehicle_no }}"
                                >
                            </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Bilty No</label>
                          <input class="form-control" type="number" name="bilty_no" value="{{ @$edit_purchase->bilty_no }}"
                          >
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>P.Invoice No</label>
                          <input class="form-control" type="text" name="prod_inv_no" value="{{ @$edit_purchase->pro_inv_no }}"
                          >
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group ">
                          <label>Account Name </label>                        
                          <select class="form-control select2" id="account_name" type="text" name="account_id"   >
                          <option value="">Select account </option>
                          @foreach($accounts AS $account)
                            <option value="{{ $account->hashid }}" @if(@$edit_purchase->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                          @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Item Name</label>
                          <select class="form-control select2"  type="text" name="item_id"   >                          
                            <option value="">Select item</option>
                            @foreach($items AS $item)
                              <option value="{{ $item->hashid }}"  @if(@$edit_purchase->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                            <label>Company Weight  </label>
                            <input class="form-control" type="number" name="company_weight" id="company_weight" value="{{ @$edit_purchase->company_weight }}" >
                        </div>
                      </div>    
                      <div class="col-md-3">
                        <div class="form-group">
                            <label>party Weight  </label>
                            <input class="form-control" type="number" name="party_weight" id="party_weight" value="{{ @$edit_purchase->party_weight }}" >
                        </div>
                      </div>
                    </div>  
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label> Weight  Difference </label>
                          <input class="form-control"  type="number"  name="weight_difference" id="weight_difference" value="{{ @$edit_purchase->weight_difference }}" >
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Rate</label>
                          <input class="form-control" name="rate" type="text" id="rate"  value="{{ @$edit_purchase->item->price }}"
                          required>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>No Of Begs</label>
                          <input class="form-control" name="no_of_begs" type="number" id="no_of_begs"  value="{{ @$edit_purchase->no_of_bags }}"
                          required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Remarks</label>
                          <input class="form-control" type="text" name="remarks" id="remarks" value="{{ @$edit_purchase->remarks }}" required>
                        </div>
                      </div>
                        
                    </div>
                    <br /><br />
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                            <label>Difference</label>
                            <input class="form-control" type="number" name="differenece" id="differenece" value="{{ @$edit_purchase->differenece }}" >
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                            <label>posted weight </label>
                            <input class="form-control" type="number" name="posted_weight" id="posted_weight" value="{{ @$edit_purchase->posted_weight }}" >
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                            <label>Loading Charges </label>
                            <input class="form-control" type="text" name="loading_charges" id="loading_charges" value="{{ @$edit_purchase->loading_charges }}" >
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Commission (in %)</label>
                          <input class="form-control" type="text" name="commission" id="commission" value=" {{ @$edit_purchase->commission }}"
                          >
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Gross Amount</label>
                          <input class="form-control" type="text" name="gross_ammount" id="gross_ammount" value="{{ @$gross_Ammount }}"
                          >
                        </div>
                      </div> 

                    </div>

                    <div class="row">
                      <div class="col-md-3">
                          <div class="form-group ">
                            <label>Fare Status </label>                        
                            <select class="form-control select2" id="fare_Status" type="text" name="fare_Status"   >
                            <option value="">Select Fare Status </option>
                            <option value="exmill">Exmill </option>
                            <option value="delivered">Delievered </option>
                            
                            </select>
                          </div>
                        </div>
                      <div class="col-md-3">
                        <div class="form-group">
                            <label>Fare </label>
                            <input class="form-control" type="number" name="fare" id="fare" value="{{ @$edit_purchase->fare }}" >
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                            <label>Other Charges  </label>
                            <input class="form-control" type="number" name="others_charges" id="others_charges" value="{{ @$edit_purchase->other_charges }}" >
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                            <label>Net Amount  </label>
                            <input class="form-control" type="text" name="net_ammount" id="net_ammount" value="{{ @$edit_purchase->net_ammount }}" >
                        </div>
                      </div>
                    </div>
                    <input type="hidden" name="purchase_book_id" value="{{ $edit_purchase->hashid }}">
                    <div class="row" >
                      <div class="col-md-2 mt-4 mr-8">
                          <div class="form-group">
                            <input type="hidden" name="purchase_id" value="{{ @$edit_purchase->hashid }}">
                              <button type="submit" name="save_purchase" class="btn btn-success "><i class="fa fa-check"></i> save</button>
                          </div>
                      </div>
                    </div>
                  </form>
                </div>
                
              </div>
            </div>
            <!-- COL END --> 
          </div>
        @endif  
        <!-- ROW-5 END --> 
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Accounts Detail</h3>
                </div>
                <div class="card-body">
                
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
                              <td>{{ $purchase->item->name }}</td>
                              <td>{{ $purchase->no_of_bags }}</td>
                              <td>{{ $purchase->bag_rate }}</td>
                              <td>{{ $purchase->fare }}</td>
                              <td>{{ @$purchase->commission }}</td>
                              <td>{{ @$purchase->net_ammount }}</td>
                              
                              <td width="120">
                                  <a href="{{route('admin.purchases.edit_purchase', $purchase->hashid)}}" >
                                  <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                                  </a>
                                  <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.purchases.delete', ['id'=>$purchase->hashid]) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
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