
@extends('layouts.admin')
@section('content')
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
                    <h3 class="card-title mb-0">Add Sale Medicine  Details</h3>
                </div>
                <div class="card-body">
                
                <div class="card-block">
            <div class="item_row">
              
            <form class="ajaxForm" action="{{ route('admin.medicines.sale_store') }}" method="POST">
              @csrf
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Date</label>
                      <input class="form-control" type="date" name="date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_medicine->date)) : date('Y-m-d') }}" required>
                    </div>
                  </div>
                  <div class="col-md-2 form-group">
                      <label for="">Invoice No</label>
                      <input type="text" class="form-control invoice_no" name="Invoice_no" id="Invoice_no" value="{{ !empty($invoice_no) ? $invoice_no : @$edit_medicine->invoice_no }}" required>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Account </label>
                      <select class="form-control select2" name="account_id" id="account_id">
                        <option value="">Select Account</option>
                        @foreach($accounts AS $account)
                          <option value="{{ $account->hashid }}" data-sale_medicine_discount="{{ $account->sale_medicine_discount }}" data-discount="{{ $account->discount }}" @if(@$edit_medicine->account_id == $account->id) selected @endif >{{ $account->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
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
               
                
               
                @if(!isset($is_update))
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for=""> Items</label>
                                <select class="form-control purchase_item_id select2" name="item_id[]" id="purchase_item_id0" required>
                                    <option value="">Select sale item</option>
                                    @foreach($purchase_items AS $item)
                                        <option value="{{ $item->hashid }}" data-price="{{ $item->sale_ammount }}" >{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Rate</label>
                                <input type="text" class="form-control rate" name="item_rate[]" id="rate0" >
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Quantity</label>
                                <input type="text" class="form-control qty" name="item_quantity[]" id="quantity0" required>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Total Ammount</label>
                                <input type="text" class="form-control total" name="ammount[]" id="ammount0" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary mt-3 add_row">+</button>
                            </div>
                        </div>
                    @endif
                    @if(isset($is_update))
                        
                    <div class="row">
                            <div class="col-md-3 form-group">
                                <label for=""> Items</label>
                                <select class="form-control purchase_item_id select2" name="item_id" id="purchase_item_id0" required>
                                    <option value="">Select sale item</option>
                                    @foreach($purchase_items AS $item)
                                        <option value="{{ $item->hashid }}" @if(@$edit_medicine->item_id == $item->id) selected @endif  data-price="{{ $item->sale_ammount }}" data-unit="{{ $item->unit }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Rate</label>
                                <input type="text" class="form-control rate" name="item_rate" id="rate0" value=" {{@$edit_medicine->rate ? @$edit_medicine->rate : 0}}" required>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Quantity</label>
                                <input type="text" class="form-control qty" name="item_quantity" id="quantity0" value=" {{@$edit_medicine->quantity ? @$edit_medicine->quantity : 0}}" required>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Total Ammount</label>
                                <input type="text" class="form-control total" name="ammount" id="ammount0" value=" {{@$edit_medicine->sale_ammount ? @$edit_medicine->sale_ammount : 0}}" required>
                            </div>
                            
                        </div>
                        
                    @endif
                    <div id="btn_div"></div>
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Sales Commission (%)</label>
                      <input class="form-control" name="commission" id="commission"  value="{{ @$edit_medicine->commission }}" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Discount (Rs)</label>
                      <input class="form-control" name="discount" id="discount"  value="{{ @$edit_medicine->discount }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Other Charges</label>
                      <input class="form-control" name="other_charges" id="other_charges" value="{{ @$edit_medicine->other_charges }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Net Ammount</label>
                      <input class="form-control" name="net_ammount"  id="net_ammount" value="{{ @$edit_medicine->net_ammount }}" required>
                    </div>
                  </div>

                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                      <label for="">Remarks</label>
                      <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="4">{{ @$edit_medicine->remarks }}</textarea>
                    </div>
                </div>
                <input type="hidden" name="sale_medicine_id" value="{{ @$edit_medicine->hashid }}">
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
                    <h3 class="card-title mb-0"> Sale Medicine Filters</h3>
                </div>
                <div class="card-body">
                <form action="" method="GET">
            @csrf
            <div class="row">
              <div class="col-md-2">
                <label for="">Invoice No</label>
                <input type="text" class="form-control" name="invoice_no" value="GH-" id="invoice_no">
              </div>
              <div class="col-md-3">
                <label for="">Accounts</label>
                <select class="form-control select2" name="parent_id" id="parent_id">
                  <option value="">Select  Account</option>
                  @foreach($accounts AS $account)
                    <option value="{{ $account->hashid }}" >{{ $account->name }}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="col-md-2">
                <label for="">Item</label>
                <select class="form-control select2" name="item_id" id="item_id">
                  <option value="">Select Item</option>
                  @foreach($purchase_items AS $item)
                    <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                    @endforeach
                </select>
              </div>
              <div class="col-md-2">
                <label for="">From</label>
                <input type="date" class="form-control" name="from_date" id="from_date">
              </div>
              <div class="col-md-2">
                <label for="">To</label>
                <input type="date" class="form-control" name="to_date" id="to-date">
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
                    <h3 class="card-title mb-0">All Sale Medicine  Detail</h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                <thead>
                        <tr class="text-dark">
                          <th>ID</th>
                          <th>Date</th>
                          <th>Invoice No</th>
                          <th>Account Name</th>
                          <th>Item</th> 
                          <th>Quantity</th>
                          <th>Rate</th>
                          <th>Sale Ammount</th>
                          <th>Other Charges</th>
                          <th>Remarks</th>
                          
                          <!--<th>Net Profit</th>-->

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $tot_qty = 0; $tot_amt = 0; $tot_othercharges_amt = 0; ?>
                      @foreach($sale_medicines AS $sale) 
                        <tr class="text-dark">
                          <td>{{ @$sale->id }}</td>
                          <td>{{ date('d-M-Y', strtotime(@$sale->date)) }}</td>
                          <td>{{ @$sale->invoice_no }}</td>
                          
                          <td><span class="waves-effect waves-light btn btn-danger-light">{{ @$sale->account->name }}</span></td>
                          <td>{{ @$sale->item->name }}</td>
                          <?php $tot_qty += @$sale->quantity;  ?>
                          <?php $tot_amt +=  @$sale->sale_ammount + @$sale->profit; ?> 
                          <td>{{ @$sale->quantity }}</td> 
                          <td>{{ @$sale->rate }}</td>
                          <td>{{ @$sale->sale_ammount + $sale->profit }}</td>
                          <?php $tot_othercharges_amt +=  @$sale->other_charges ?>
                          <td>{{ $sale->other_charges }}</td>
                        <td>{{ $sale->remarks }}</td>

                          <!--<td>{{ $sale->profit }}</td>-->

                          <td>
                            <a href="{{ route('admin.medicines.invoice',['invoice_no'=>$sale->invoice_no]) }}" >
                              <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-download"></i></span>
                            </a>

                            <a href="{{ route('admin.medicines.sale_edit',['id'=>$sale->hashid]) }}" >
                            <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>
                            </a>
                            <!--<button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.feeds.sale_delete', ['id'=>$sale->hashid]) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">-->
                            <!--<i class="fa-sharp fa-solid fa-plus"></i> &nbsp Post-->
                            <!--</button>-->
                          </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="text-dark">
                            <th>Total :</th>
                            <th>-</th>
                            <th>-</th>
                            <th>-</th>
                            <th>-</th>
                            <th>{{ @$tot_qty }}</th>
                            <th>-</th>
                           <th>{{ @$tot_amt }}</th>
                           <th>{{ @$tot_othercharges_amt}}</th>
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

    </div>
  
@endsection

@section('page-scripts')


<script>

  
  //when there is change in account then put the commission and discount in fields
  $('#account_id').change(function(){
    $('#commission').val($(this).find(':selected').data('sale_medicine_discount'));
    
  });
  
 
  $(document).on('keyup', '.qty', function(){
    var g =$(this).attr("id");
    var a = g.match(/\d+/g);
    var v ='#rate'+a+'';
    var pr = $(v).val();
    var quantity = $(this).val();
    var net_ammount = pr * quantity;
    $('#ammount'+a+'').val(net_ammount);

    calc_total();

    });

    $(document).on('keyup', '#discount', function(){

    calc_total();

    });
    $(document).on('keyup', '#other_charges', function(){

      calc_total();

    });

    $(document).on('keyup', '.rate', function(){
      var g =$(this).attr("id");
      var a = g.match(/\d+/g);
      
      var v ='#quantity'+a+'';

      var pr = $(v).val();
      
      var quantity = $(this).val();
      //var val_rate = $(".rate").val();
      // var init_rate = $("#rate").val();
      // var quantity = $("#quantity").val();
      var net_ammount = pr * quantity;
      $('#ammount'+a+'').val(net_ammount);
      calc_total();

    });
    
    function calc_total(){
      var sum = 0;
      $(".total").each(function(){
        sum += parseFloat($(this).val());
      });
      var commission   = $('#commission').val();
      var discount   = $('#discount').val();
      var other_charges   = $('#other_charges').val();

      var g = (sum * commission)/100;
      var sub = Number(other_charges) + Number(sum) +  Number(g);
      $('#net_ammount').val(Number(sub) - Number(discount) );
    }
  // $('#quantity, #rate,').bind('keyup change', function(){
  //   var price      = $('#rate').val();
  //   var quantity   = $('#quantity').val();
    
  //   var p_amt = Number(price) * Number(quantity);  
    
  //   $("#net_ammount").val(p_amt);

    
  // });

  
  var i = 0;

  //check product quantity
  $(document).on('change', '.purchase_item_id', function(){
        var g =$(this).attr("id");
        var a = g.match(/\d+/g);
        var v ='#rate'+a+'';
        var sale_price = $(this).find(':selected').data('price');
        var g = (sale_price * 25)/100; 
        var r = $(v).val(Number(sale_price) + Number(g));
  
        var item_id = $(this).val();
        
        var route   = "{{ route('admin.formulations.check_product_qty', ':id') }}";
        route       = route.replace(':id', item_id);
        

        if(item_id != ''){
            getAjaxRequests(route, '', 'GET', function(resp){
                
                Swal.fire(
                    'Item stock quantity',
                    'Item current stock quantity is '+resp.stock + resp.unit ,
                    
                    'info'
                )
            });
        }

    });

  $(document).on('click', '.add_row', function(e){
        
         
        ++i;
        
        var html = '<div class="row">'+
                        '<div class="col-md-3 form-group">'+
                            '<label>Purchase Item</label>'+
                            '<select class="form-control purchase_item_id select2" name="item_id[]" id="purchase_item_id'+i+'" required>'+
                                '<option value="">Select purchase item</option>';
                        
                                @foreach($purchase_items AS $item)
        html    +=                  '<option value='+"{{ $item->hashid }}"+' data-price='+"{{ $item->sale_ammount }}"+'>'+"{{ $item->name }}"+'</option>';
                                @endforeach   
        html  +=           '</select>'+
                        '</div>'+
                        '<div class="col-md-2 form-group">'+
                            '<label for="">Rate</label>'+
                            '<input type="text" class="form-control rate" name="item_rate[]" id="rate'+i+'" required>'+
                        '</div>'+
                        '<div class="col-md-2 form-group">'+
                            '<label for="">Quantity</label>'+
                            '<input type="text" class="form-control qty" name="item_quantity[]" id="quantity'+i+'" required>'+
                        '</div>'+
                        '<div class="col-md-2 form-group">'+
                            '<label for="">Total Ammount</label>'+
                            '<input type="text" class="form-control total" name="ammount[]" id="ammount'+i+'" required>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<button type="button" class="btn btn-primary mt-3 add_row">+</button>'+
                            '<button type="button" class="btn btn-danger mt-3 remove_row">x</button>'+
                        '</div>'+
                    '</div>';
        $('#btn_div').before().append(html);
        
    });

    $(document).on('click', '.remove_row', function(e){//remove row
        e.preventDefault();
        $(this).parent().parent().remove();
    });

    
</script>
@endsection