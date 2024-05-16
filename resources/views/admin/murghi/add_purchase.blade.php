@extends('layouts.admin')
@section('content')

<div class="main-content app-content mt-5">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
          <div class="col">
					 <div class="card radius-10 border-start border-0 border-4 border-info">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<h2 class="mb-0 text-secondary">Total Murghi Purchase Qty 
									    (کل وزن مرغی کی خریداری)</h2><br />
									<h1 class="my-1 text-info">{{@$tot_qty}} Kg</h1><br />
									<p class="mb-0 font-13">+2.5% from last week</p>
								</div>
								<div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-cart'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>
				   <div class="col">
					<div class="card radius-10 border-start border-0 border-4 border-danger">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <h2 class="mb-0 text-secondary">Total Purchase Ammount (کل مرغی کی خریداری کی رقم)</h2><br />
								   <h1 class="my-1 text-danger">{{@$tot_amt}}</h1><br />
								   <p class="mb-0 font-13">+5.4% from last day</p>
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-wallet'></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div>
				  
				  
				</div>
        <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 --> 
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">Add Purchase Murghi Details</h3>
                </div>
                <div class="card-body">
                
                <div class="card-block">
            <div class="item_row">
              
            <form class="ajaxForm" role="form" action="{{ route('admin.purchase_murghis.store') }}" method="POST">

                @csrf
                <div  class="row" >
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Date</label>
                      <input class="form-control" type="date" name="date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_purchase->date)) : date('Y-m-d') }}"
                      required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Invoice No</label>
                      <input class="form-control" type="text" name="invoice_no" value="{{ !empty($invoice_no) ? $invoice_no : @$edit_purchase->invoice_no }}"
                       >
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Vehical No</label>
                      <input class="form-control" name="vehicle_no" type="text" id="vehicle_no"  value="{{ @$edit_purchase->vehicle_no ?@$edit_purchase->vehicle_no:0 }}"
                      >
                    </div>
                  </div>
                  
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group ">
                      <label>Account Name </label>                        
                      <select class="form-control select2" id="account_id" type="text" name="account_id"   >
                      <option value="">Select account </option>
                      @foreach($accounts AS $account)
                        <option value="{{ $account->hashid }}" @if(@$edit_purchase->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                      @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Item Name </label>
                      <select class="form-control select2"  type="text" name="item_id"   required>                          
                        <option value="">Select item</option>
                        @foreach($items AS $item)
                          <option value="{{ $item->hashid }}"  @if(@$edit_purchase->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label> No of Crate </label>
                        <input class="form-control" type="text" name="no_of_crate" id="no_of_crate" value="{{ @$edit_purchase->no_of_crate ? @$edit_purchase->no_of_crate : 0 }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Total Murghi </label>
                        <input class="form-control" type="text" name="quantity" id="quantity" value="{{ @$edit_purchase->quantity }}" required>
                    </div>
                  </div>  
                </div>
                <div class="row" >
                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Full Weight  </label>
                        <input class="form-control" type="text" name="net_weight" id="net_weight" value="{{ @$edit_purchase->net_weight }}" required>
                    </div>
                  </div> 
                  <div class="col-md-2">
                    <div class="form-group">
                      <label> Weight  Difference </label>
                      <input class="form-control"  type="text"  name="weight_difference" id="weight_difference" value="{{ @$edit_purchase->weight_difference ? @$edit_purchase->weight_difference : 0 }}" >
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <label>crate weight detection </label>
                        <input class="form-control" type="text" name="crate_weight" id="crate_weight" value="{{ @$edit_purchase->crate_weight ? @$edit_purchase->crate_weight : 0 }}" >
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <label> Mortality weight </label>
                        <input class="form-control" type="text" name="mortality_weight" id="mortality_weight" value="{{ @$edit_purchase->mortality_weight ? @$edit_purchase->mortality_weight : 0 }}" >
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Feed weight detection </label>
                        <input class="form-control" type="text" name="feed_weight" id="feed_weight" value="{{ @$edit_purchase->feed_weight ? @$edit_purchase->feed_weight : 0 }}" >
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Final Weight</label>
                        <input class="form-control" type="text" name="final_weight" id="final_weight" value="{{ @$edit_purchase->final_weight }}" >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Average</label>
                        <input class="form-control" type="text" name="average" id="average" value="{{ @$edit_purchase->average ? @$edit_purchase->average : 0 }}" >
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Rate</label>
                      <input class="form-control" name="rate" type="text" id="rate"  value="{{ @$edit_purchase->rate ? @$edit_purchase->rate : 0 }}"
                        required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Rate Detection</label>
                      <input class="form-control" name="rate_detection" type="text" id="rate_detection"  value="{{ @$edit_purchase->rate_detection ? @$edit_purchase->rate_detection : 0 }}"
                      required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Final Rate </label>
                      <input class="form-control" name="final_rate" type="text" id="final_rate"  value="{{ @$edit_purchase->final_rate  ? @$edit_purchase->final_rate : 0 }}"
                      required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Gross Amount</label>
                      <input class="form-control" type="text" name="gross_ammount" id="gross_ammount" value="{{ @$edit_purchase->gross_ammount ? @$edit_purchase->gross_ammount : 0 }}"
                      required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Fare </label>
                        <input class="form-control" type="text" name="fare" id="fare" value="{{ @$edit_purchase->fare ? @$edit_purchase->fare : 0 }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Other Charges  </label>
                        <input class="form-control" type="text" name="other_charges" id="other_charges" value="{{ @$edit_purchase->other_charges ? @$edit_purchase->other_charges : 0 }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Net Amount  </label>
                        <input class="form-control" type="text" name="net_ammount" id="net_ammount" value="{{ @$edit_purchase->net_ammount ? @$edit_purchase->net_ammount : 0 }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Send SMS  </label>
                        <select class="form-control select2" name="sms_status" id="sms_status">
                          <option value="">Select Status</option>
                          <option value="not_send_sms">Not Send Sms</option>
                          <option value="send_sms">Send Sms</option>
                        </select>
                    </div>
                  </div> 
                </div>
                <div class="row">
                  <div class="col-md-11">
                    <div class="form-group">
                        <label>Remarks </label>
                        <input class="form-control" type="text" name="remarks" id="remarks" value="{{ @$edit_purchase->remarks ? @$edit_purchase->remarks : "Murghi Purchase Added" }}" required>
                    </div>
                  </div>  
                </div>
                <div class="row" >
                  <div class="col-md-2 mt-4 mr-8">
                      <div class="form-group">
                        <input type="hidden" name="purchase_id" value="{{ @$edit_purchase->hashid }}">
                          <button type="submit" name="save_purchase" class="btn btn-success "><i class="fa fa-check"></i> save</button>
                      </div>
                      
                  </div>
                </div>
                
              </form>
              <br /><br />
            </div>

          </div>
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
                    <h3 class="card-title mb-0"> Purchase Murghi Filters</h3>
                </div>
                <div class="card-body">
                <form action="{{ route('admin.purchase_murghis.index') }}" method="GET">
                    @csrf
                    <div class="row">
                      <div class="col-md-3">
                            <div class="form-group ">
                              <label>Account Name </label>                        
                              <select class="form-control select2" id="parent_id" type="text" name="parent_id"   >
                              <option value="">Select account </option>
                              @foreach($accounts AS $account)
                                <option value="{{ $account->hashid }}">{{ $account->name }}</option>
                              @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <label for="">From</label>
                            <input type="date" class="form-control" name="from_date" id="from_date">
                          </div>
                          <div class="col-md-3">
                            <label for="">To</label>
                            <input type="date" class="form-control" name="to_date" id="to_date">
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
                    <h3 class="card-title mb-0">All Purchase Murghi Detail</h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                <thead>
                <tr style="border-color:black;">
                  
                  <th>Date</th>
                  <th>Invoice No</th>
                  <th> Account Name </th>
                  <th> Item Name </th>
                  <th> Rate </th>
                  <th> Final Weight </th>
                  <th>Net Ammount</th>
                  <th>Action</th>
                </tr>
            </thead>
            <tbody>
            
              @foreach(@$purchases as $p)
                  <tr style="text-dark">
                      
                      <td>{{ date('d-m-Y', strtotime($p->date)) }}</td>
                      <th> {{ @$p->invoice_no }}</th>
                      <td><span class="waves-effect waves-light btn btn-primary-light">{{ @$p->account->name }}</span></td>
                      <td>{{ @$p->item->name }}</td>
                      <th> {{ @$p->rate }}</th>
                      <td>{{ $p->final_weight }}</td>
                        
                      <td><span class="waves-effect waves-light btn btn-warning-light">{{ @$p->net_ammount }}</span></td>
                      
                      <td>
                                    <a href="{{ route('admin.purchase_murghis.edit',['id'=>hashids_encode($p->id)]) }}" >
                                    <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>
                                    </a>
                                    
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

<script>
$.fn.digits = function(){ 
    return this.each(function(){ 
        $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
    })
}

$('#net_weight, #crate_weight, #mortality_weight, #feed_weight, #final_weight').bind('keyup change', function(){
    var net_weight      = $('#net_weight').val();
    var crate_weight   = $('#crate_weight').val();
    var mortality_weight      = $('#mortality_weight').val();
    var feed_weight   = $('#feed_weight').val();
    var final_weight      = $('#final_weight').val();
    
    var sum = Number(feed_weight) + Number(crate_weight) + Number(mortality_weight);
    var net = Number(net_weight) - Number(sum);
    $("#final_weight").val(net);

    
  });
  
  $("#rate").keyup(function(){
    var init_rate = $("#rate").val();
    var net_weight = $("#final_weight").val();
    var gross_ammount = init_rate * net_weight;
    $("#gross_ammount").val(gross_ammount);
    $("#net_ammount").val(gross_ammount);
    
    
  });

  $("#rate_detection").keyup(function(){
    var rate_detection = $("#rate_detection").val();
    var rate = $("#rate").val();
    var final_rate = rate - rate_detection;
    var a  = $("#final_rate").val(final_rate);
    var final_weight      = $('#final_weight').val();
    var gross_ammount   = $('#gross_ammount').val(final_weight * a);
    var net_ammount      = $('#net_ammount').val(final_weight * a);
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