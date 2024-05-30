
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
                  <input class="form-control" type="date" name="sale_date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_sale[0]->date)) : date('Y-m-d') }}"
                  required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>DC No</label>
                  <input class="form-control" type="text" id="dc_no" name="gp_no" value="{{ @$edit_sale[0]->gp_no }}" required >
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label>Vehical No</label>
                    <input class="form-control" name="vehicle_no" type="text" id="vehicle_no"  value="{{ @$edit_sale[0]->vehicle_no }}"
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
                        <option value="{{ $account->hashid }}" @if(@$edit_sale[0]->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                      @endforeach
                  </select>
                </div>
              </div>  
              <div class="col-md-6">
                <div class="form-group">
                  <label>Sub Dealer / Farmer</label>
                  <input class="form-control" name="sub_dealer_name" type="text" id="sub_dealer_name"  value="{{ @$edit_sale[0]->sub_dealer_name }}"
                  >
                </div>
              </div>
            </div>
 
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Item </label>                        
                  <select class="form-control select2" style="width: 100%;"  type="text" id="item_name" name="item_id[]"  style="padding:0px;"   required>
                    <option value="">Select item</option>
                    @foreach($items AS $item)
                      <option value="{{ @$item->hashid }}" @if(@$edit_sale[0]->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                    @endforeach                               
                  </select>
                </div>
              </div> 
              <div class="col-md-2">
                <div class="form-group">
                  <label>Bags </label>
                  <input class="form-control" type="number" id="bags" name="bags" value="{{ @$edit_sale[0]->no_of_begs }}" >
                </div>
              </div> 
              <div class="col-md-2">
                <div class="form-group">
                  <label>Rate </label>
                  <input class="form-control" type="text"  name="rate" id="rate" value="{{ @$edit_sale[0]->item->price }}" >
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Sale Ammount</label>
                  <input class="form-control" name="sale_ammount" id="sale_ammount" type="text" value="{{ @$edit_sale[0]->item->price * @$edit_sale[0]->no_of_begs }}" >
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Fare  </label>
                  <input class="form-control" type="number" name="fare" id="fare_val" value="{{ @$edit_sale[0]->fare }}" >
                </div>
              </div>
              @php $sale_amount = @$edit_sale[0]->item->price * @$edit_sale[0]->no_of_begs @endphp
              @php $tot_com =  (@$edit_sale[0]->account->commission * @$sale_amount)/100 @endphp
              <div class="col-md-4">
                <div class="form-group">
                  <label>Commission %</label>                        
                  <input class="form-control"  type="text" id="commission" name="commission" readonly value="{{ @$tot_com}}"
                  >
                </div>
              </div>
              @php $tot_dis =  @$edit_sale[0]->account->discount * @$edit_sale[0]->no_of_begs  @endphp
              <div class="col-md-4">
                <div class="form-group">
                  <label>Discount </label>
                  <input class="form-control"  type="text" id="discount" name="discount" readonly value="{{ @$tot_dis }}"
                  >
                </div>
              </div>    
            </div>
            <div class="row">
              
              @php $f_net = $tot_dis + $tot_com ;
              $s_net = @$sale_amount - $f_net ;
              $net =  $s_net - @$edit_sale[0]->fare;
              @endphp
              <div class="col-md-4">
                <div class="form-group">
                  <label>Net Amount</label>
                  <input class="form-control" type="text" name="net_value" id="net_ammount" readonly value="{{ @$net}}"
                  >
                </div>
              </div>  
            <div class="form-group">
							<label class="form-label" for="textarea">Remarks</label>
							<br>
							<textarea class="form-control" id="textarea" name="remarks" type="text" id="remarks">{{ @$edit_sale[0]->remarks }}</textarea>
						</div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <input type="hidden" name="sale_id" value="{{ @$edit_sale[0]->hashid }}">
                   <button type="submit" name="save_sale" class="btn btn-success "><i class="fa fa-check"></i> save</button>
                </div>
              </div>
            </div>
					</fieldset>
				</form>
			</div>
      @endif
			<!-- /.box-body -->
		  </div>

      <div class="box">
  <div class="box-header with-border">
    <h2 class="box-title text-dark">Filters</h2>
  </div>
  <div class="box-body">
    <form action="{{ route('admin.sales.index') }}" method="GET">
      @csrf
      <div class="row">
        <div class="col-md-2">
          <label class="text-dark">Grand Parent</label>
          <select class="form-control select2" name="grand_parent_id" id="grand_parent_id">
            <option value="">Select grand parent</option>
            @foreach($account_types AS $type)
              @foreach($type->childs AS $child)
                <option value="{{ $child->hashid }}">{{ $type->name }} -> {{ $child->name }}</option>
              @endforeach
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="text-dark">Parent Account</label>
          <select class="form-control select2" name="parent_id" id="parent_id">
            <option value="">Select  Account</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="text-dark">Items</label>
          <select class="form-control select2" name="item_id" id="item_id">
            <option value="">Select item</option>
            @foreach($items AS $item)
              <option value="{{ $item->hashid }}">{{ $item->name }}</option>
            @endforeach
          </select>
        </div>
        <br />
      
        <div class="col-md-2">
          <label for="">From</label>
          <input type="date" class="form-control" name="from_date" id="from_date">
        </div>
        <div class="col-md-2">
          <label for="">To</label>
          <input type="date" class="form-control" name="to-date" id="to-date">
        </div>
        <div class="col-md-2 mt-3">
          <input type="submit" class="btn btn-primary" value="Search">
        </div>
      
    </form>
  </div>
</div>
</div>

		  <!-- /.box --> 
      <div class="box">
				<div class="box-header with-border">
				  <h2 class="box-title text-dark">All Sales Outward Entries</h2>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
					  <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
						  <thead>
							  <tr class="text-dark">
                  <th>Date</th>
                  <th> Vehicle No </th>
                  <th>DC NO</th>
                  <th> Account Name </th>
                  <th> Subdealer Name</th>
                  <th> Item Name </th>
                  <th> Quantity </th>
                  <th> Item Rate </th>
                  <th> Gross Ammount </th>
                  <th> Fare </th>
                  <th> Commission </th>
                  <th> Discount </th>

                  <th> Net Value </th>
                  <th>Action</th>
							  </tr>
						  </thead>
						  <tbody>
              @foreach ($outwards as $line)
                
                  <tr class="text-dark">
                    <td>{{ date('d-M-Y', strtotime($line->date)) }}</td>
                    @if(!$loop->first && $line->vehicle_no ==$outwards[$loop->index - 1]->vehicle_no)
                    <td>-</td>
                    @endif
                    @if(!$loop->first && $line->vehicle_no !=$outwards[$loop->index - 1]->vehicle_no)
                    <td>{{ $line->vehicle_no }}</td>
                    @endif
                    @if($loop->first )
                    <td>{{ $line->vehicle_no }}</td>
                    @endif

                    
                    @if(!$loop->first && $line->gp_no ==$outwards[$loop->index - 1]->gp_no)
                    <td>-</td>
                    @endif
                    @if(!$loop->first && $line->gp_no !=$outwards[$loop->index - 1]->gp_no)
                    <td>{{ $line->gp_no }}</td>
                    @endif
                    @if($loop->first )
                    <td>{{ $line->gp_no }}</td>
                    @endif

                    @if($loop->first )
                    <td>{{ @$line->account->name }}</td>
                    @endif
                    @if(!$loop->first && @$line->account->name == @$outwards[$loop->index - 1]->account->name && $line->gp_no == $outwards[$loop->index - 1]->gp_no )
                      
                      <td>-</td>
                      
                    @endif 
                    @if(!$loop->first && @$line->account->name == @$outwards[$loop->index - 1]->account->name && $line->gp_no != $outwards[$loop->index - 1]->gp_no )
                    <td>{{ @$line->account->name }}</td>
                    @endif 

                    @if(!$loop->first && @$line->account->name != @$outwards[$loop->index - 1]->account->name && $line->gp_no != $outwards[$loop->index - 1]->gp_no )
                    <td>{{ @$line->account->name }}</td>
                    @endif

                    

                    <td>{{ @$line->sub_dealer_name }}</td>
                    
                    <td>{{ @$line->item->name }}</td>
                    <td>{{ @$line->no_of_begs    }}</td>
                    <td>{{ @$line->item->price }}</td>
                    <td>{{ @$line->item->price * @$line->no_of_begs   }}</td>

                    <td>{{ @$line->fare }}</td>
                    
                    <?php 
                        @$get_commsission = $line->account->commission;
                        @$get_value = @$line->item->price * @$line->no_of_begs;
                        @$get_net_commission = ($get_commsission * $get_value)/100;
                        @$get_discount = $line->account->discount * @$line->no_of_begs;
                        @$get_net_value = (((@$line->item->price * @$line->no_of_begs)- $get_discount)-$get_net_commission)-$line->fare ;
                      ?>
                      <td>{{ @$get_net_commission    }}</td>
                      <td>{{ @$get_discount    }}</td>
                      <td>{{ number_format(@$get_net_value) }}</td>
                    
                    <td width="120">
                        <a href="{{route('admin.sales.edit', @$line->id)}}" >
                        <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                        </a>
                        @if(@$line->sale_status == "completed")

                            <button type="button" style="color:green;"   class="waves-effect waves-light btn btn-rounded btn-success-light">
                              <i class="fa-sharp fa-solid fa-plus"></i> &nbsp Post
                            </button>
                        @endif
                        @if(@$line->sale_status == "pending")
                          <button type="button" style="color:green;" onclick="ajaxRequest(this)" data-url="{{ route('admin.sales.migrate_to_sale', ['id'=>$line->hashid]) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
                              <i class="fa-sharp fa-solid fa-plus"></i> &nbsp Post
                            </button>
                        @endif
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

  $("#rate").keyup(function(){
      
            var id = $("#account_name").val();
            var url = '{{ route("admin.sales.account_details", ":id") }}';
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(resp){
                  var c = resp.account.commission;
                  var d = resp.account.discount;
                  var s = $("#sale_ammount").val();
                  var bags = $("#bags").val();
    
                  var net_c = (s * c)/100;
                  var net_d = bags * d;
                  $('#commission').val(net_c);
                  $('#discount').val(net_d);
                  var commission = $('#commission').val();
                  var discount = $('#discount').val();
                    var no_of_bags = $("#bags").val();
                    var rate = $("#rate").val();
                    var s_val = no_of_bags * rate;
                    var sale_amt = $("#sale_ammount").val(s_val);
                
                    var net_cd = Number(commission) + Number(discount);
                    var fare = $("#fare_val").val();
                    var n_f = Number(net_cd) + Number(fare);
                    var s = $("#sale_ammount").val();
                    var net = Number(s) - Number(n_f) ;
                    
                    var p_weight = $("#net_ammount").val(net);
                  
                        
            
                },
                error: function(){
                    console.log("no response");
                }
        });
      
    

  
  });

  $("#bags").keyup(function(){
    var no_of_bags = $("#bags").val();
    var rate = $("#rate").val();
    var s_val = no_of_bags * rate;
    var sale_amt = $("#sale_ammount").val(s_val);

    var commission = $("#commission").val();
    var discount = $("#discount").val();
    var net_cd = Number(commission) + Number(discount);
    var fare = $("#fare_val").val();
    var n_f = Number(net_cd) + Number(fare);
    var s = $("#sale_ammount").val();
    var net = Number(s) - Number(n_f) ;
    
    var p_weight = $("#net_ammount").val(net);

  
  });

  $("#fare_val").keyup(function(){
    var no_of_bags = $("#bags").val();
    var rate = $("#rate").val();
    var s_val = no_of_bags * rate;
    var sale_amt = $("#sale_ammount").val(s_val);

    var commission = $("#commission").val();
    var discount = $("#discount").val();
    var net_cd = Number(commission) + Number(discount);
    var fare = $("#fare_val").val();
    var n_f = Number(net_cd) + Number(fare);
    var s = $("#sale_ammount").val();
    var net = Number(s) - Number(n_f) ;
    
    var p_weight = $("#net_ammount").val(net);

  
  });



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
              var c = resp.account.commission;
              var d = resp.account.discount;
              var s = $("#sale_ammount").val();
              var bags = $("#bags").val();

              var net_c = (s * c)/100;
              var net_d = bags * d;
              $('#commission').val(net_c);
              $('#discount').val(net_d);

              var no_of_bags = $("#bags").val();
              var rate = $("#rate").val();
              var s_val = no_of_bags * rate;
              var sale_amt = $("#sale_ammount").val(s_val);

              var commission = $("#commission").val();
              var discount = $("#discount").val();
              var net_cd = Number(commission) + Number(discount);
              var fare = $("#fare_val").val();
              var n_f = Number(net_cd) + Number(fare);
              var s = $("#sale_ammount").val();
              var net = Number(s) - Number(n_f) ;
              
              var p_weight = $("#net_ammount").val(net);
                        
            
            },
            error: function(){
                console.log("no response");
            }
        });
  });


  $('#item_name').change(function(){
    var id = $(this).val();
    var url = '{{ route("admin.sales.item_details", ":id") }}';
    url = url.replace(':id', id);
    $.ajax({
            url: url,
            
            type: 'GET',
            success: function(resp){
              $('#rate').val(resp.item.price);

              var no_of_bags = $("#bags").val();
              var rate = $("#rate").val();
              var s_val = no_of_bags * rate;
              var sale_amt = $("#sale_ammount").val(s_val);

              var id = $("#account_name").val();
              var url = '{{ route("admin.sales.account_details", ":id") }}';
              url = url.replace(':id', id);
              $.ajax({
                      url: url,
                      
                      type: 'GET',
                      success: function(resp){
                        var c = resp.account.commission;
                        var d = resp.account.discount;
                        var s = $("#sale_ammount").val();
                        var bags = $("#bags").val();

                        var net_c = (s * c)/100;
                        var net_d = bags * d;
                        $('#commission').val(net_c);
                        $('#discount').val(net_d);

                        var net_cd = Number(net_c) + Number(net_d);
                        
                        var fare = $("#fare_val").val();
                        var n_f = Number(net_cd) + Number(fare);
                        var s = $("#sale_ammount").val();
                        var net = Number(s) - Number(n_f) ;
                        
                        var p_weight = $("#net_ammount").val(net);
                        
                                  
                      
                      },
                      error: function(){
                          console.log("no response");
                      }
                  });
              
              
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