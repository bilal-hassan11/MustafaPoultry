@extends('layouts.admin')
@section('content')

<style>

.news {
  box-shadow: inset 0 -15px 30px rgba(0,0,0,0.4), 0 5px 10px rgba(0,0,0,0.5);
  width: 350px;
  height: 39px;
  margin: 20px auto;
  overflow: hidden;
  border-radius: 4px;
  padding: 3px;
  -webkit-user-select: none
} 
.full-width{
    width: 100%;
}
.news span {
  float: left;
  color: #fff;
  padding: 6px;
  position: relative;
  top: 1%;
  border-radius: 4px;
  box-shadow: inset 0 -15px 30px rgba(0,0,0,0.4);
  font: 16px 'Source Sans Pro', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -webkit-user-select: none;
  cursor: pointer
}

.news ul {
  float: left;
  padding-left: 20px;
  animation: ticker 10s cubic-bezier(1, 0, .5, 0) infinite;
  -webkit-user-select: none
}

.news ul li {line-height: 30px; list-style: none }

.news ul li a {
  color: #fff;
  text-decoration: none;
  font: 16px Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -webkit-user-select: none
}

@keyframes ticker {
	0%   {margin-top: 0}
	25%  {margin-top: -30px}
	50%  {margin-top: -60px}
	75%  {margin-top: -90px}
	100% {margin-top: 0}
}

.news ul:hover { animation-play-state: paused }
.news span:hover+ul { animation-play-state: paused }

/* OTHER COLORS */
.blue { background: #347fd0 }
.blue span { background: #2c66be }
.red { background: #3455d2 }
.red span { background: #382bc2 }
.green { background: #699B67 }
.green span { background: #547d52 }
.magenta { background: #b63ace }
.magenta span { background: #842696 }
.yellow {background : yellow}
.yellow span {background : yellow}


</style>    

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
									<h2 class="mb-0 text-secondary">Total Murghi Sale Qty 
									    (کل وزن مرغی )</h2><br />
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
								   <h2 class="mb-0 text-secondary">Total Sale Ammount (کل مرغی کی رقم)</h2><br />
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
                    <h3 class="card-title mb-0">Add Sale Murghi Details</h3>
                </div>
                <div class="card-body">
                
                <div class="card-block">
            <div class="item_row">
              
            <form class="ajaxForm" role="form" action="{{ route('admin.sale_murghis.store') }}" method="POST">
                @csrf
                <div  class="row" >
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Date</label>
                      <input class="form-control" type="date" name="date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_sale->date)) : date('Y-m-d') }}"
                      required>
                    </div>
                  </div>
                  
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Invoice No</label>
                      <input class="form-control" type="text" name="invoice_no" value="{{ !empty($invoice_no) ? $invoice_no : @$edit_sale->invoice_no }}"
                       >
                    </div>
                  </div>
                  <div class="col-md-3">
                        <div class="form-group">
                            <label>Vehical No</label>
                            <input class="form-control" name="vehicle_no" type="text" id="vehicle_no"  value="{{ @$edit_sale->vehicle_no ? @$edit_sale->vehicle_no : 0 }}"
                            >
                        </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group ">
                      <label>Account Name </label>                        
                      <select class="form-control select2" id="account_id" type="text" name="account_id"   >
                      <option value="">Select account </option>
                      @foreach($accounts AS $account)
                        <option value="{{ $account->hashid }}" @if(@$edit_sale->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                      @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  
                  
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Item Name </label>
                      <select class="form-control select2"  type="text" name="item_id"   required>                          
                        <option value="">Select item</option>
                        @foreach($items AS $item)
                          <option value="{{ $item->hashid }}" data-price="{{ $item->sale_ammount }}"  @if(@$edit_sale->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <label> No of Crate </label>
                        <input class="form-control" type="text" name="no_of_crate" id="no_of_crate" value="{{ @$edit_sale->no_of_crate ? @$edit_sale->no_of_crate : 0 }}" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Total Murghi </label>
                        <input class="form-control" type="text" name="quantity" id="quantity" value="{{ @$edit_sale->quantity ? @$edit_sale->quantity : 0 }}" required>
                    </div>
                  </div>  
                  <div class="col-md-2">
                      <div class="form-group">
                          <label>Net Weight  </label>
                          <input class="form-control" type="text" name="net_weight" id="net_weight" value="{{ @$edit_sale->net_weight ? @$edit_sale->net_weight : 0 }}" >
                      </div>
                    </div> 
                  
                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Average</label>
                        <input class="form-control" type="text" name="average" id="average" value="{{ @$edit_sale->average ? @$edit_sale->average : 0 }}" >
                    </div>
                  </div>
                </div>
                
              
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Rate</label>
                      <input class="form-control" name="rate" type="text" id="rate"  value="{{ @$edit_sale->rate ? @$edit_sale->rate : 0 }}"
                        required>
                    </div>
                  </div>
                  
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Gross Amount</label>
                      <input class="form-control" type="text" name="gross_ammount" id="gross_ammount" value="{{ @$edit_sale->gross_ammount ? @$edit_sale->gross_ammount : 0 }}"
                      required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Other Charges  </label>
                        <input class="form-control" type="text" name="other_charges" id="other_charges" value="{{ @$edit_sale->other_charges ? @$edit_sale->other_charges : 0 }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Net Amount  </label>
                        <input class="form-control" type="text" name="net_ammount" id="net_ammount" value="{{ @$edit_sale->net_ammount ? @$edit_sale->net_ammount : 0 }}" required>
                    </div>
                  </div>               
                </div>
                
                <div class="row">
                  <div class="col-md-11">
                    <div class="form-group">
                        <label>Remarks </label>
                        <input class="form-control" type="text" name="remarks" id="remarks" value="{{ @$edit_sale->remarks ? @$edit_sale->remarks : "Murghi Sale Added " }}" required>
                    </div>
                  </div>  
                </div>
                <div class="row" >
                    
                    
                    <div class="col-md-2 mt-4 mr-8">
                        <div class="form-group">
                          <input type="hidden" name="sale_id" value="{{ @$edit_sale->hashid }}">
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
                    <h3 class="card-title mb-0"> Pending  sales (Due To Some Reason)</h3>
                </div>
                <div class="card-body">
                <div class="news red full-width">
                	
                	<ul class="scrollLeft">
                  @foreach($pending_purchase as $i )
                            <li><a href="{{ route('admin.purchase_murghis.edit',['id'=>@$i->hashid]) }}" > Date: {{@$i->date}} , Invoice No : {{$i->invoice_no}} , Item Name:Murghi - Price {{$i->rate}}</a></li>
                        @endforeach
                    </ul>
                </div>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0"> Sale Murghi Filters</h3>
                </div>
                <div class="card-body">
                <form action="{{ route('admin.sale_murghis.index') }}" method="GET">
                    @csrf
                    <div class="row">
                      
                      <div class="col-md-4">
                                  <div class="form-group ">
                                    <label>Account Name </label>                        
                                    <select class="form-control select2" id="parent_id" type="text" name="parent_id"   >
                                    <option value="">Select account </option>
                                    @foreach($accounts AS $account)
                                      <option value="{{ $account->hashid }}" >{{ $account->name }}</option>
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
                      
                      <div class="col-md-2 mt-5">
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
                    <h3 class="card-title mb-0">All Sale Murghi Detail</h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                  <thead>
                    <tr class="text-dark">
                      
                      <th>Entry Date</th>
                      <th>Invoice NO</th>
                    
                      <th> Account Name </th>
                      <th> Quantity </th>
                      <th> Net Weight </th>
                      <th> Rate </th>
                      <th> Net Ammount </th>
                      <th>Remarks</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $tot_qty = 0; $tot_amt = 0; ?>
                    @foreach($sales as $s)
                      <tr style="border-color:black;">
                        <td>{{ date('d-M-Y', strtotime(@$s->date)) }}</td>
                        <td>{{ @$s->invoice_no }}</td>
                        <td> <span class="waves-effect waves-light btn btn-warning-light">{{ @$s->account->name }}</span></td>
                        
                        <?php $tot_qty +=  @$s->quantity; ?>
                        <td>{{ @$s->quantity    }}</td>
                        <td>{{ @$s->net_weight    }}</td>
                        <td>{{ $s->rate }}</td>
                        <?php $tot_amt +=   $s->net_ammount; ?>
                        <td><span class="waves-effect waves-light btn btn-info-light">{{ $s->net_ammount }} </span></td>
                      
                        <td><span class="waves-effect waves-light btn btn-rounded btn-success-light">{{ @$s->remarks }} </span></td>
                        
                        <td width="120">
                            <a href="{{route('admin.sale_murghis.edit', $s->hashid)}}" >
                            <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                            </a>
                            
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                      <tr class="text-dark">
                          <th>Total</th>
                        
                          <th>-</th>
                        <th>-</th>
                          <th>{{ @$tot_qty }}</th>
                          <th>-</th>
                          <th>-</th>
                        <th>{{ @$tot_amt }}</th>
                          <th>-</th>
                          <th>-</th>
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


<script>



$.fn.digits = function(){ 
    return this.each(function(){ 
        $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
    })
}

  $("#rate").keyup(function(){
    var init_rate = $("#rate").val();
    var net_weight = $("#net_weight").val();
    var gross_ammount = Number(init_rate * net_weight).toFixed(2);
    $("#gross_ammount").val(gross_ammount);
    
    
  });


  $("#other_charges").keyup(function(){
    var gross_ammount = $("#gross_ammount").val();
    var other_charges = $("#other_charges").val();
    var net_ammount = gross_ammount - other_charges;
    $("#net_ammount").val(net_ammount);
    
    
  });

  $("#net_weight").keyup(function(){
    var net_weight = $("#net_weight").val();
    var total_murghi = $("#quantity").val();
    var avg = net_weight / total_murghi;
    $("#average").val(avg);
    // alert(avEg);
    
    
  });

  $("#net_weight").keyup(function(){
    var net_weight = $("#rate").val();
    var total_murghi = $("#total_murghi").val();
    var avg = net_weight / total_murghi;
    $("#average").val(avg);
    
    
  });

  $("#rate_detection").keyup(function(){
    var rate_detection = $("#rate_detection").val();
    var rate = $("#rate").val();
    var final_rate = rate - rate_detection;
    $("#final_rate").val(final_rate);
   
    
  });

  $('#company_weight, #party_weight').bind('keyup change', function(){
    check_weight_difference();
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