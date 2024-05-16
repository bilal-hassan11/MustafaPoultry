
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
        
       
        <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 --> 
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">Add Sale Feed Details</h3>
                </div>
                <div class="card-body">
                
                <div class="card-block">
            <div class="item_row">
              
              <form class="ajaxForm" role="form" action="{{ route('admin.feeds.sale_store') }}" method="POST" novalidate>
                  @csrf
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Date</label>
                          <input class="form-control" type="date" required data-validation-required-message="This field is required"  name="date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_feed->date)) : date('Y-m-d') }}" required>
                        </div>
                      </div>
                      <div class="col-md-1 form-group">
                        <label for="">Invoice No</label>
                        <input type="text" class="form-control invoice_no" name="Invoice_no" id="Invoice_no" value="{{ !empty($invoice_no) ? $invoice_no : @$edit_feed->invoice_no }}" required>
                    </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Company </label>
                          <select class="form-control select2" name="company_id" id="company_id">
                            <option value="">Select Company</option>
                            @foreach($category->companies AS $company)
                              <option value="{{ $company->hashid }}" @if(@$edit_feed->company_id == $company->id) selected @endif>{{ $company->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
    
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Item </label>
                          <select class="form-control select2 purchase_item_id" name="item_id" id="item_id22">
                            <option value="">Select Item</option>
                            @foreach($category->items AS $item)
                              <option value="{{ $item->hashid }}" data-price="{{ $item->sale_ammount }}" @if(@$edit_feed->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
    
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Account </label>
                          <select class="form-control select2" name="account_id" id="account_id">
                            <option value="">Select Account</option>
                            @foreach($accounts AS $account)
                              <option value="{{ $account->hashid }}" @if(@$edit_feed->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Select Shade </label>
                          <select class="form-control select2" name="shade_id" id="shade_id">
                            <option value="">Select Shade</option>
                            @foreach($shades AS $s)
                              <option value="{{ $s->hashid }}"  @if(@$edit_medicine->shade_id == $s->id) selected @endif >{{ $s->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Item Rate</label>
                          <input class="form-control" name="rate" id="rate"  value="{{ @$edit_feed->rate }}" required>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Sale Rate</label>
                          <input class="form-control" name="sale_rate" id="sale_rate" value="{{ @$edit_feed->sale_rate }}" required>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Quantity</label>
                          <input class="form-control" name="quantity" id="quantity" value="{{ @$edit_feed->quantity }}" required>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Fare</label>
                          <input class="form-control" name="fare" id="fare" value="{{ @$edit_feed->fare }}" required>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Net Ammount</label>
                          <input class="form-control" name="net_ammount" readonly id="net_ammount" value="{{ @$edit_feed->net_ammount }}" required>
                        </div>
                      </div>
                      
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Status </label>
                            <select class="form-control select2" name="status" id="status">
                              <option value="available" @if(@$edit_feed->status == 'available') selected @endif>Available</option>
                              <option value="not_available" @if(@$edit_feed->status == 'not_available') selected @endif>Not Available</option>
                            </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="">Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="4">{{ @$edit_sale->remarks }}</textarea>
                            </div>
                      </div>
                      <input type="hidden" name="sale_feed_id" value="{{ @$edit_feed->hashid }}">
                      <input type="submit" class="btn btn-primary" value="{{ (isset($is_update)) ? 'Update' : 'Add' }}">
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
                    <h3 class="card-title mb-0">Sale Feed Filters</h3>
                </div>
                <div class="card-body">
                <form action="" method="GET">
          @csrf
          <div class="row">
            
            <div class="col-md-3">
              <label for="">Accounts</label>
              <select class="form-control select2" name="parent_id" id="parent_id">
                <option value="">Select  Account</option>
                    @foreach($accounts AS $account)
                        <option value="{{ $account->hashid }}" @if(@$edit_feed->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                     @endforeach
              </select>
            </div>
           <div class="col-md-2">
              <label for="">Invoice No</label>
              <input type="text" class="form-control" name="invoice_no" id="invoice_no">
            </div>
            <div class="col-md-2">
              <label for="">Item</label>
              <select class="form-control select2" name="item_id" id="item_id">
                <option value="">Select Item</option>
                @foreach($items AS $item)
                          <option value="{{ $item->id }}"  @if(@$edit_dc->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                        @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label for="">From</label>
              <input type="date" class="form-control" name="from_date" id="from_date">
            </div>
            <div class="col-md-2">
              <label for="">To</label>
              <input type="date" class="form-control" name="to_date" id="to_date">
            </div>
            <div class="col-md-1 mt-6">
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
                    <h3 class="card-title mb-0">All Sale Feed Detail</h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                      <thead>
                        <tr class="text-dark">
                            
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Account Name</th>
                            <th>Company Name</th>
                            <th>Item</th>
                            <th>Rate</th>
                            <th>Quantity</th>
                           
                            <th>Net Ammount</th>
                            
                            
                            <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php $tot_qty = 0; $tot_net_amt = 0; ?>
                        @foreach($sale_feed AS $sale) 
                        <tr class="text-dark">
                          
                          <td>{{ date('d-M-y', strtotime(@$sale->date)) }}</td>
                          <td>{{ @$sale->invoice_no }}</td>
                          
                          <td><span class="waves-effect waves-light btn btn-rounded btn-success-light">{{ @$sale->account->name }}</span></td>
                          <td>{{ @$sale->company->name }}</td>
                          <td>{{ @$sale->item->name }}</td>
                          <td>{{ @$sale->rate }}</td>
                          <?php $tot_qty += @$sale->quantity ; ?>
                          <td>{{ @$sale->quantity }}</td>
                         
                          
                          <?php $tot_net_amt += @$sale->net_ammount; ?>
                          <td>{{ @$sale->net_ammount }}</td>
                          
                          
                          <td>
                            <a href="{{ route('admin.feeds.sale_edit',['id'=>@$sale->hashid]) }}" >
                            <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                            </a>
                          
                          </td>
                        </tr>
                    @endforeach
                      </tbody>
                      <tfoot>
                          <tr class="text-dark">
                              <th>Total:</th>
                              
                              <th>-</th>
                              <th>-</th>
                              
                             
                              <th>-</th>
                              <th>-</th>
                              <th><span class="waves-effect waves-light btn btn-rounded btn-danger-light">{{@$tot_qty}} Bags</span></th>
                              <th>-</th>
                              <th><span class="waves-effect waves-light btn btn-rounded btn-primary-light">{{@$tot_net_amt }}</span></th>
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
    $('#account_id').change(function(){
        var id = $(this).val();
        var url = '{{ route("admin.feeds.account_balance", ":id") }}';
        url = url.replace(':id', id);
        $.ajax({
                url: url,
                type: 'GET',
                success: function(resp){
                 var get_act_nat = resp.account.account_nature ;
                 var get_act_bal = resp.account.opening_balance ;
                 
                  Swal.fire(
                    'Account Current Status',
                    'Account Nature '+get_act_bal +' ( '+ get_act_nat+' ) ',
                    'info'
                )
                },
                error: function(){
                    console.log("no response");
                }
            });
      });
    //check product rate
        $(document).on('change', '.purchase_item_id', function(){
            
            var sale_price = $(this).find(':selected').data('price');
            
    
                    Swal.fire(
                        'Item Rate',
                        'Item Rate '+sale_price,
                        'info'
                    )
            
    
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
  });

  // $('#commission, #discount, #other_charges, #quantity, #rate').bind('keyup change', function(){
  //   var price      = $('#rate').val();
  //   var quantity   = $('#quantity').val();
    
  //   var p_amt = Number(price) * Number(quantity);  
  //   $("#purchase_ammount").val(p_amt);

  //   var commission   = $('#commission').val();
  //   var discount   = $('#discount').val();
  //   var other_charges   = $('#other_charges').val();
    
  //   var sum = Number(commission) + Number(discount) + Number(other_charges);
  //   var net = Number(p_amt) - Number(sum);
  //   $("#net_ammount").val(net);

    
  // });

  $('#quantity,#fare').keyup(function(){
    var fare = $('#fare').val();
    var quantity = $('#quantity').val();
    
    var purchase_rate = $('#sale_rate').val();
    var net_val = Number(purchase_rate) * Number(quantity);
    var net = Number(net_val) + Number(fare);
    $('#net_ammount').val(net);
    
    
  });

  $('#item_id22').change(function(){
    $('#rate').val($(this).find(':selected').data('price'));
    
  });
  
</script>
@endsection