
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
                    <h3 class="card-title mb-0">Add Sale Chick Details</h3>
                </div>
                <div class="card-body">
                
                <div class="card-block">
            <div class="item_row">
              
            <form class="ajaxForm" role="form" action="{{ route('admin.chicks.sale_store') }}" method="POST" novalidate>
                @csrf
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>Date</label>
                        <input class="form-control" type="date" required data-validation-required-message="This field is required"  name="date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_sale->date)) : date('Y-m-d') }}" required>
                      </div>
                    </div>
                    <div class="col-md-1 form-group">
                      <label for="">Invoice No</label>
                      <input type="text" class="form-control invoice_no" name="Invoice_no" id="Invoice_no" value="{{ !empty($invoice_no) ? $invoice_no : @$edit_sale->invoice_no }}" required>
                  </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>Select Company </label>
                        <select class="form-control select2" name="company_id" id="company_id">
                          <option value="">Select Company</option>
                          @foreach($category->companies AS $company)
                            <option value="{{ $company->hashid }}" @if(@$edit_sale->company_id == $company->id) selected @endif>{{ $company->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
  
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>Item </label>
                        <select class="form-control select2" name="item_id" id="item_id">
                          <option value="">Select Item</option>
                          @foreach($category->items AS $item)
                            <option value="{{ $item->hashid }}" data-price="{{ $item->sale_ammount }}" @if(@$edit_sale->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
  
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Account </label>
                        <select class="form-control select2" name="account_id" id="account_id">
                          <option value="">Select Account</option>
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
                        <label>Rate</label>
                        <input class="form-control" name="rate" id="rate"  value="{{ @$edit_sale->rate }}" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Quantity</label>
                        <input class="form-control" name="quantity" id="quantity" value="{{ @$edit_sale->quantity }}" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Net Ammount</label>
                        <input class="form-control" name="net_ammount" readonly id="net_ammount" value="{{ @$edit_sale->net_ammount }}" required>
                      </div>
                    </div>
                    
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Status </label>
                          <select class="form-control select2" name="status" id="status">
                            <option value="available" @if(@$edit_sale->status == 'available') selected @endif>Available</option>
                            <option value="not_available" @if(@$edit_sale->status == 'not_available') selected @endif>Not Available</option>
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
                    <input type="hidden" name="sale_chick_id" value="{{ @$edit_sale->hashid }}">
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
                    <h3 class="card-title mb-0"> Sale Chick Filters</h3>
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
                    <h3 class="card-title mb-0"> Pending  sales (Due To Some Reason)</h3>
                </div>
                <div class="card-body">
                <div class="news red full-width">
                	
                	<ul class="scrollLeft">
                        @foreach($pending_sale as $i )
                            <li><a href="{{ route('admin.chicks.sale_edit',['id'=>$i->hashid]) }}" > Date: {{@$i->date}} , Invoice No : {{$i->invoice_no}} , Item Name: Chicks - Price {{$i->rate}}</a></li>
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
                    <h3 class="card-title mb-0">All Sale Chicks Detail</h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                             <th>Transaction Date</th>
                            <th>Account Name</th>
                            <th>Company Name</th>
                            
                            <th>Rate</th>
                            <th>Quantity</th>
                            <th>Net Ammount</th>
                            <th>Remarks</th>
                            
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $tot_qty = 0; $tot_amt = 0; ?>
                        @foreach($sale_chicks AS $sale) 
                          <tr class="text-dark">
                              <td>{{ date('d-M-Y', strtotime($sale->date)) }}</td>
                            <td><span class="waves-effect waves-light btn btn-rounded btn-primary-light">{{ @$sale->account->name }}</span></td>
                            <td>{{ @$sale->company->name }}</td>
                            
                            <td>{{ @$sale->rate }}</td>
                            <?php $tot_qty +=  @$sale->quantity; ?>
                            <td>{{ @$sale->quantity }}</td>
                             <?php $tot_amt +=  @$sale->net_ammount; ?>
                            <td><span class="waves-effect waves-light btn btn-rounded btn-warning-light">{{ @$sale->net_ammount }}</span></td>
                             <td><span class="waves-effect waves-light btn btn-rounded btn-success-light">{{ @$sale->remarks }}</span></td>
                        <td>
                            
                              <a href="{{ route('admin.chicks.sale_edit',['id'=>$sale->hashid]) }}"  >
                              <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                              </a>
                              <!--<button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.chicks.sale_delete', ['id'=>$sale->hashid]) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">-->
                              <!--<i class="fa-sharp fa-solid fa-plus"></i> &nbsp Post-->
                              <!--</button>-->
                            </td>
                          </tr>
                        @endforeach
                    </tbody>
                     <tfoot>
                        <tr class="text-dark">
                            <th>Total</th>
                            <th>-</th>
                            <th>-</th>
                            <th>-</th>
                            <th>-</th>
                            <th>{{ @ $tot_qty }}</th>
                            <th>{{ @ $tot_amt }}</th>
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
  
  $(' #quantity, #rate').bind('keyup change', function(){
    var price      = $('#rate').val();
    var quantity   = $('#quantity').val();
    
    var p_amt = Number(price) * Number(quantity);  
    $("#net_ammount").val(p_amt);

    
  });


  $(document).on('change', '#item_id', function(){
        var item_id = $(this).val();
        var route   = "{{ route('admin.formulations.check_product_qty', ':id') }}";
        route       = route.replace(':id', item_id);
        
        if(item_id != ''){
            getAjaxRequests(route, '', 'GET', function(resp){
                Swal.fire(
                    'Item stock quantity',
                    'Item current stock quantity is '+resp.stock,
                    'info'
                )
            });
        }

    });

  $('#item_id').change(function(){
    $('#rate').val($(this).find(':selected').data('price'));
    
  });
  
</script>
@endsection