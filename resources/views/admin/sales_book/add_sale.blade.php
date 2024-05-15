
@extends('layouts.admin')
@section('content')

<div class="main-content"> 
       {{-- <div class="row">
         <div class="col-sm-12">
           <div class="card">
             <div class="card-header">
                <h4 class="card-title"> Sales Form</h4>
             </div>
             
            <!-- Default box -->
		  <div class="box"> --}}
        @if(isset($is_update))
			<div class="box-body">
      
				<a class="popup-with-form btn btn-primary d-none" href="#test-form" id="popup_button">Add Sale</a>
				<!-- form itself -->
				<form id="test-form"  role="form" action="{{ route('admin.sales.store') }}" method="POST" class="ajaxForm mfp-hide white-popup-block">
          @csrf
          <h4>Sales Form</h4>
					<fieldset style="border:0;">
						
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Date</label>
                  <input class="form-control" type="date" name="sale_date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_sale->date)) : date('Y-m-d') }}"
                  required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>G.P No</label>
                  <input class="form-control" type="text" name="gp_no" value="{{ @$edit_sale->gp_no }}" required >
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label>Vehical No</label>
                    <input class="form-control" name="vehicle_no" type="text" id="vehicle_no"  value="{{ @$edit_sale->vehicle_no }}"
                    required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                  <label>Account/Dealar </label>                        
                  <select class="form-control select2" style="width: 100%;"  id="account_name" type="text" name="account_name"   required>
                      <option value="">Select Accounts </option>
                      @foreach($accounts AS $account)
                        <option value="{{ $account->hashid }}" @if(@$edit_sale->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                      @endforeach
                  </select>
                  </div>
                </div>  
              <div class="col-md-6">
                  <div class="form-group">
                      <label>Sub Dealer / Farmer</label>
                      <input class="form-control" name="sub_dealer_name" type="text" id="sub_dealer_name"  value="{{ @$edit_sale->sub_dealer_name }}"
                      required>
                  </div>
                </div>
            </div>
            @php 
              $sale_amount = 0;
            @endphp
            @foreach($edit_sale->outardDetails AS $detail)
              <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                    <label>Item </label>                        
                    <select class="form-control select2" style="width: 100%;"  type="text" name="item_id[]"  style="padding:0px;"   required>
                      <option value="">Select item</option>
                      @foreach($items AS $item)
                        <option value="{{ $item->hashid }}" @if($detail->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                      @endforeach                               
                    </select>
                    </div>
                  </div>  
                  <div class="col-md-3">
                    <div class="form-group">
                    <label>Bags </label>
                    <input class="form-control" type="number"  name="bags[]" value="{{ $detail->quantity }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Rate</label>
                      <input class="form-control" name="rate[]" type="number" value="{{ $detail->item->price }}" required>
                    </div>
                  </div>
                </div>
                @php $sale_amount += $detail->item->price * $detail->quantity @endphp
              @endforeach
              @php
                $commission = ($sale_amount * $edit_sale->account->commission)/100;
                $discount   = ($sale_amount * $edit_sale->account->discount)/100;
                $net_value  = ($sale_amount - ($commission + $discount))-$edit_sale->fare;
              @endphp
            <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Fare  </label>
                    <input class="form-control" type="number" name="fare_value" id="fare_val" value="{{ @$edit_sale->fare }}" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Commission %</label>                        
                    <input class="form-control"  type="number" id="commission" name="commission" readonly value="{{ @$edit_sale->account->commission }}"
                    required readonly>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Discount </label>
                    <input class="form-control"  type="number" id="discount" name="discount" readonly value="{{ @$edit_sale->account['discount'] }}"
                    required readonly>
                  </div>
                </div>
                
            </div>
            <div class="row">
              <div class="col-md-4">
                  <div class="form-group">
                  <label>Fare Status </label>                        
                  <select class="form-control select2" style="width: 100%;"  type="text" name="fare_status" id="fare_status" style="padding:0px;"required>
                  <option value="">Select fare status</option>
                  <option value="1">Paid</option>
                  <option value="0">Not paid</option>                              
                  </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Sale Amount</label>
                    <input class="form-control" type="number" name="bags_value" id="bags_value" value="{{ $sale_amount }}"
                    required>
                  </div>
                </div>  
              <div class="col-md-4">
                  <div class="form-group">
                    <label>Net Value </label>
                    <input class="form-control" type="number" name="net_value" id="net_val" value="{{ round($net_value) }}" required>
                  </div>
                </div>
              </div>
              
						<div class="form-group">
							<label class="form-label" for="textarea">Remarks</label>
							<br>
							<textarea class="form-control" id="textarea" name="remarks" type="text" id="remarks">{{ @$edit_sale->remarks }}</textarea>
						</div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <input type="hidden" name="sale_id" value="{{ @$edit_sale->hashid }}">
                  <input type="submit" class="btn btn-success ">
                  {{-- <button type="submit" name="save_sale" class="btn btn-success "><i class="fa fa-check"></i> save</button> --}}
                </div>
              </div>
            </div>
					</fieldset>
				</form>
			</div>
      @endif
			<!-- /.box-body -->
		  </div>
		  <!-- /.box --> 
      <div class="box">
        <div class="box-header with-border">
          <h2 class="box-title text-dark">Filters</h2>
        </div>
        <div class="box-body">
          <form action="{{ route('admin.cash.index') }}" method="GET">
            @csrf
            <div class="row">
              <div class="col-md-3 text-dark">
                <label for="">Grand Parent</label>
                <select class="form-control select2" name="grand_parent_id" id="grand_parent_id">
                  <option value="">Select grand parent</option>
                  @foreach($account_types AS $type)
                    @foreach($type->childs AS $child)
                      <option value="{{ $child->hashid }}">{{ $type->name }}--{{ $child->name }}</option>
                    @endforeach
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Parent Account</label>
                <select class="form-control select2" name="parent_id" id="parent_id">
                  <option value="">Select  Account</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Items</label>
                <select class="form-control select2" name="item_id" id="item_id">
                  <option value="">Select item</option>
                  @foreach($items AS $item)
                    <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Vehicle No</label>
                <input type="text" class="form-control" name="vehicle_no" id="vehicle_no">
              </div>
            </div><br />
            <div class="row">
              <div class="col-md-3">
                <label for="">From</label>
                <input type="date" class="form-control" name="from_date" id="from_date">
              </div>
              <div class="col-md-3">
                <label for="">To</label>
                <input type="date" class="form-control" name="to-date" id="to-date">
              </div>
              <div class="col-md-2 mt-3">
                <input type="submit" class="btn btn-primary" value="Search">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="box">
				<div class="box-header with-border">
				  <h2 class="box-title text-dark">All Sales Entries</h2>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
					  <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
						<thead>
							<tr class="text-dark">
                <th>Id.No</th>
                <th>Date</th>
                <th> Vehicle No </th>
                <th>GP NO</th>
                <th> Account Name </th>
                <th> Subdealer Name</th>
                <th> Item Name </th>
                <th> No Of Bags </th>
                <th> Rate </th>
                <th> Sale Value </th>
                <th> Fare </th>
                <th> Discount </th>
                <th> Commission </th>
                <th> Net Value </th>
                <th>Action</th>
							</tr>
						</thead>
						<tbody>
              @foreach($outwards AS $outward)
                <tr class="text-dark">
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ date('m-D-Y', strtotime($outward->date)) }}</td>
                  <td>{{ $outward->vechicle_no }}</td>
                  <td>{{ $outward->gp_no }}</td>
                  <td>{{ @$outward->account->name }}</td>
                  <td>{{ $outward->sub_dealer_name }}</td>
                  <td class="waves-effect waves-light btn btn-rounded btn-primary-light">{{ @implode("    ", $outward->outardDetails->pluck('item')->pluck('name')->toArray()) }}</td>
                  <td >{!! @implode("<br />", $outward->outardDetails->pluck('quantity')->toArray()) !!}</td>
                  <td>{{ $outward->rate }}</td>
                  <td>{{ $outward->sale_value }}</td>
                  <td>{{ $outward->fare }}</td>
                  <td>{{ @$outward->account->discount }}</td>
                  <td>{{ @$outward->account->commission }}</td>
                  <td>{{ @$outward->net_value }}</td>
                  
                  <td width="120">
                    <a href="{{route('admin.sales.edit', $outward->hashid)}}" >
                    <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                    </a>
                    <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.sales.migrate_to_sale', ['id'=>$outward->hashid]) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
                    <i class="fa-sharp fa-solid fa-plus"></i> &nbsp Post
                    </button>
                  </td>
                </tr>
              @endforeach
						</tbody>
            	
					</table>
					</div>              
				</div>
				<!-- /.box-body -->
			  </div>
               
               
             </div> 
           </div>
         </div>
      </div>
 
      
 </div>

 @endsection

@section('page-scripts')
@include('admin.partials.datatable')
	

<script>
$(document).ready(function() {
  // var loop_time = $("#countingitem").val();
  //   for (let i = 0; i <= loop_time.length; i++) {
  //     console.log(i);
  //   }
    @if(isset($is_update))
    $('#test-form').removeClass('mfp-hide');
      $('#popup_button').click();
    @endif
  });
  $('#account_name').change(function(){
    var id = $(this).val();
    var url = '{{ route("admin.sales.account_details", ":id") }}';
    url = url.replace(':id', id);
    $.ajax({
            url: url,
            
            type: 'GET',
            success: function(resp){
              $('#commission').val(resp.account.commission);
              $('#discount').val(resp.account.discount);
            
            },
            error: function(){
                console.log("no response");
            }
        });
  });

  $('#grand_parent_id').change(function(){
    var id    = $(this).val();
    var route = "{{ route('admin.cash.get_parent_accounts', ':id') }}";
    route     = route.replace(':id', id);

   if(id != ''){
      getAjaxRequests(route, "", "GET", function(resp){
        $('#parent_id').html(resp.html);
      });
    }
  })
</script>
@endsection