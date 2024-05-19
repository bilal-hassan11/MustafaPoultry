
@extends('layouts.admin')
@section('content')
 
<div class="main-content app-content mt-5">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        <div class="row">
          <div class="col-12 col-sm-12">
            <div class="card">
              <form id="formData" method="POST" action="#">
                  @csrf
                  <div class="card-header">
                      <h4> Add Purchase Medicine </h4>
                  </div>
                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-2 mb-3">
                              <label for="date" class="required">Invoice No</label>
                              <input type="text" name="invoice_no" class="form-control" value="0" >
                          </div>
                          <div class="col-md-2 mb-3">
                              <label for="date" class="required">Date</label>
                              <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                          </div>

                          <div class="col-md-4 mb-3">
                          </div>
                          <div class="col-md-4 mb-3">
                              <label for="date" class="required">Ref No</label>
                              <input type="text" name="ref_no" class="form-control" value="0" >
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-2 mb-3">
                              
                          </div>
                          <div class="col-md-2 mb-3">
                              
                          </div>

                          <div class="col-md-4 mb-3">
                          </div>
                          <div class="col-md-4 mb-3">
                            <label for=""> Account </label>
                            <select class="form-control select2" name="account_id" id="account_id11">
                              <option value="">Select Account</option>
                              @foreach($accounts AS $account)
                                <option value="{{ $account->hashid }}" @if(@$edit_medicine->account_id == $account->id) selected @endif data-commission="{{ $account->commission }}" data-discount="{{ $account->discount }}">{{ $account->name }}</option>
                              @endforeach
                            </select>
                          </div>
                      </div>
                  </div>
                  <div class="card-body" style="width: 100%; overflow-x: auto">
                      <table class="table table-bordered text-center add-stock-table" style="width: 100%">
                          <thead>
                              <tr>
                                  <th style="width: 20%;">Item</th>
                                  <th style="width: 10%;">Unit</th>
                                  <th style="width: 10%;">Rate</th>
                                  <th style="width: 10%;">Quantity</th>
                                  <th style="width: 20%;">Expiry</th>
                                  <th style="width: 10%;">Dis In (Rs)</th>
                                  <th style="width: 10%;">Dis In (%)</th>
                                  <th style="width: 20%;">Amount</th>
                                  <th style="width: 20%;">Action</th>
                              </tr>
                          </thead>
                          <tbody id="row">
                          </tbody>
                          <tfoot>
                              <tr>
                                  <td colspan="6"></td>
                                  <td >
                                    <label for="">Total Dis</label>
                                    <input type="number" name="total_discount" class="form-control  text-right" value="0">
                                  </td>
                                  <td >
                                    <label for=""> Grand Total</label>
                                    <input type="number" name="grand_total" class="form-control  text-right" value="0">
                                  </td>

                                  <td>
                                      <button type="button"
                                          class="btn btn-info btn-sm add-row">Add Row</button>
                                  </td>
                              </tr>
                          </tfoot>
                      </table>
                      <button type="button" class="btn btn-success mt-2" id="submit-form">Save</button>
                  </div>
              </form>

            </div>
          </div>
        </div>
        <!-- <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0"> Purchase Medicine Filters</h3>
                </div>
                <div class="card-body">
                  <form action="" method="GET">
                    @csrf
                    <div class="row">
                      
                      <div class="col-md-3">
                        <label for="">Accounts</label>
                          <select class="form-control select2" name="account_id" id="account_id">
                            <option value="">Select Account</option>
                            @foreach($accounts AS $account)
                              <option value="{{ $account->hashid }}" >{{ $account->name }}</option>
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
                          @foreach($category->items AS $item)
                            <option value="{{ $item->hashid }}" data-price="{{ $item->purchase_ammount }}" @if(@$edit_medicine->item_id == $item->id) selected @endif>{{ $item->name }}</option>
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
          
        </div> -->
        <!-- <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Purchase Medicine Detail</h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                <thead>
                      <tr class="text-dark">
                        <th>ID</th>
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
                    <?php $tot_qty = 0; $tot_amt = 0; ?>
                      @foreach($purchase_medicines AS $purcahse) 
                        <tr class="text-dark">
                          <td>{{ @$purcahse->id }}</td>
                          <td>{{ date('d-M-Y', strtotime($purcahse->date)) }}</td>
                          <td>{{ @$purcahse->invoice_no }}</td>
                          
                          <td>[{{@$purcahse->account->id}}]{{ @$purcahse->account->name }}</td>
                          <td>{{ @$purcahse->company->name }}</td>
                          <td>{{ @$purcahse->item->name }}</td>
                          <td>{{ @$purcahse->rate }}</td>
                          <?php $tot_qty += @$purcahse->quantity;  ?>
                          <?php $tot_amt +=  @$purcahse->net_ammount; ?>
                          <td>{{ @$purcahse->quantity }}</td>
                          <td>{{ @$purcahse->net_ammount }}</td>
                          <td>
                            <a href="{{ route('admin.medicines.purchase_edit',['id'=>$purcahse->hashid]) }}" >
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
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                        <th>{{ @$tot_qty }}</th>
                        <th>{{ @$tot_amt }}</th>
                        <th>-</th>
                      </tr>
                    </tfoot>
                </table>
                
            </div>
              </div>
          </div>
          
        </div> -->
    </div>
    <!-- CONTAINER END --> 
  </div>
</div>    
@endsection

@section('page-scripts')
<script type="text/javascript">
    let productDetailsArray = {!! json_encode($products->keyBy('id')->toArray()) !!};
    console.log(productDetailsArray);
    $(document).ready(function() {

        addRow();

        $(".add-row").click(addRow);

        function addRow() {
            let row = `
            <tr class="rows">
                <td class="product_col">
                    @if ($products)
                    <select class="form-control product product_val select2" name="product[]" id="products" required>
                        <option value="">Select Items</option>
                        @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name ?? '' }}</option>
                        @endforeach
                    </select>
                    @endif
                </td>
                
                <td class="unit">
                  <input type="text" name="unit[]" class="form-control unit text-right" value="1">
                </td>
                <td class="unit_qty">
                  <input type="number" name="unit_qty[]" class="form-control unitRate text-right" value="1">
                </td>
                <td class="quantity_col">
                    <input type="number" name="quantity[]" class="form-control quantity text-right" min="1" value="1"  >
                </td>
                <td class="expiry_date">
                  <input type="date" name="expiry_date[]" class="form-control text-right" value="1">
                </td>
                <td class="dis_in_rs">
                    <input type="number" name="dis_in_rs[]" class="form-control dis_in_rs text-right" value="0">
                </td>
                <td class="dis_in_percentage">
                    <input type="number" name="dis_in_percentage[]" class="form-control dis_in_percentage text-right" min="0" value="0" >
                </td>
                <td class="amount_col">
                    <input type="number" name="amount[]" class="form-control amount text-right" min="0" value="0"  >
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-row" title="Delete">Remove</button>
                </td>
            </tr>
        `;
            $("table tbody").append(row);
            
            // Hide delete button for the first row
            if ($("table tbody tr").length > 0) {
                $("table tbody tr:first .delete-row").hide();
            }
        }

        $(document).on("click", ".delete-row", function() {
            $(this).closest("tr").remove();
        });


       
    });
</script>
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

  $('#item_id22').change(function(){
    $('#rate').val($(this).find(':selected').data('price'));
    
  });
  
  //when there is change in account then put the commissiona and discount in fields
  // $('#account_id').change(function(){
  //   // $('#commission').val(($(this).find(':selected').data('commission')));
  //   // $('#discount').val(($(this).find(':selected').data('discount')));
    
  // });
  $('#commission, #discount, #other_charges, #quantity, #rate').bind('keyup change', function(){
    var price      = $('#rate').val();
    var quantity   = $('#quantity').val();
    
    var p_amt = Number(price) * Number(quantity);  
    $("#purchase_ammount").val(p_amt);

    var commission   = $('#commission').val();
    var discount   = $('#discount').val();
    var other_charges   = $('#other_charges').val();
    
    var sum = Number(commission) + Number(discount) + Number(other_charges);
    var net = Number(p_amt) - Number(sum);
    $("#net_ammount").val(net);

    
  });

  // $('#quantity').change(function(){
  //   var price      = $('#rate').val();
  //   var quantity   = $('#quantity').val();
    
  //   var p_amt = Number(price) * Number(quantity);  
  //   $("#purchase_ammount").val(p_amt);
    
  // });

//when there is change in quantity calculate total amount

</script>
@endsection