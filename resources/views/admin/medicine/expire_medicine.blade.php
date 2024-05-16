
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
                    <h3 class="card-title mb-0">Add Expire Medicine Details</h3>
                </div>
                <div class="card-body">
                
                <div class="card-block">
            <div class="item_row">
              
            <form class="ajaxForm" role="form" action="{{ route('admin.medicines.expire_store') }}" method="POST">
              @csrf
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Date</label>
                      <input class="form-control" type="date" name="date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_medicine->date)) : date('Y-m-d') }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Company(All Medicine Companies) </label>
                      <select class="form-control select2" name="company_id" id="company_id">
                          <option value=""> Select Company</option>
                        @foreach($companies AS $company)
                          
                          <option value="{{ $company->hashid }}" @if(@$edit_medicine->company_id == $company->id) selected @endif>{{ $company->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Item (selectd Companies Item)</label>
                      <select class="form-control select2" name="item_id" id="item_id">
                        <option value="">Select Item</option>
                        @foreach($items AS $item)
                          <option value="{{ $item->hashid }}" data-price="{{ $item->purchase_ammount }}" @if(@$edit_medicine->item_id == $item->id) selected @endif>{{ $item->name }}</option>
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
                          <option value="{{ $account->hashid }}" @if(@$edit_medicine->account_id == $account->id) selected @endif data-commission="{{ $account->commission }}" data-discount="{{ $account->discount }}">{{ $account->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Rate</label>
                      <input class="form-control" name="rate" id="rate"  value="{{ @$edit_medicine->rate }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Quantity</label>
                      <input class="form-control" name="quantity" id="quantity" value="{{ @$edit_medicine->quantity }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Purchase Ammount</label>
                      <input class="form-control" name="purchase_ammount" id="purchase_ammount" value="{{ @$edit_medicine->purchase_ammount }}" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Status </label>
                        <select class="form-control select2" name="status" id="status">
                          <option value="available" @if(@$edit_medicine->status == 'available') selected @endif>Available</option>
                          <option value="not_available" @if(@$edit_medicine->status == 'not_available') selected @endif>Not Available</option>
                        </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Commission</label>
                      <input class="form-control" name="commission" id="commission"  value="{{ @$edit_medicine->commission }}" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Discount</label>
                      <input class="form-control" name="discount" id="discount"  value="{{ @$edit_medicine->discount }}" required>
                    </div>
                  </div>
                  <div class="col-md-2">
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
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Expiry Date</label>
                      <input class="form-control" type="date" name="expiry_date"  id="expiry_date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_medicine->expiry_date)) : date('Y-m-d') }}" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                      <label for="">Remarks</label>
                      <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="4">{{ @$edit_medicine->remarks }}</textarea>
                    </div>
                </div>
                <input type="hidden" name="expire_medicine_id" value="{{ @$edit_medicine->hashid }}">
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
                    <h3 class="card-title mb-0"> Expire Medicine Filters</h3>
                </div>
                <div class="card-body">
                <form action="" method="GET">
          @csrf
          <div class="row">
            
            <div class="col-md-2">
              <label for="">Company</label>
              <select class="form-control" name="parent_id" id="parent_id">
                <option value="">Select  Account</option>
              </select>
            </div>
            <div class="col-md-2">
              <label for="">Item</label>
              <select class="form-control" name="parent_id" id="parent_id">
                <option value="">Select Item</option>
              </select>
            </div>
            <div class="col-md-3">
              <label for="">From</label>
              <input type="date" class="form-control" name="from_date" id="from_date">
            </div>
            <div class="col-md-3">
              <label for="">To</label>
              <input type="date" class="form-control" name="to-date" id="to-date">
            </div>
            <div class="col-md-2 my-6">
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
                    <h3 class="card-title mb-0">All Expire Medicine Detail</h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                <thead>
                        <tr class="text-dark">
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
                      @foreach($expire_medicines AS $purcahse) 
                        <tr class="text-dark">
                          <td>{{ @$purcahse->account->name }}</td>
                          <td>{{ @$purcahse->company->name }}</td>
                          <td>{{ @$purcahse->item->name }}</td>
                          <td>{{ $purcahse->rate }}</td>
                          <td>{{ $purcahse->quantity }}</td>
                          <td>{{ $purcahse->net_ammount }}</td>
                          <td>
                            <a href="{{ route('admin.medicines.purchase_edit',['id'=>$purcahse->hashid]) }}" >
                            <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                            </a>
                            <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.feeds.purchase_delete', ['id'=>$purcahse->hashid]) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
                            <i class="fa-sharp fa-solid fa-trash"></i> &nbsp 
                            </button>
                          </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="text-dark">
                            <th>Acount Name</th>
                            <th>Company Name</th>
                            <th>Item</th>
                            <th>Rate</th>
                            <th>Quantity</th>
                            <th>Net Ammount</th>
                            <th>Action</th>
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

$('#item_id, #quantity').bind('keyup change', function(){
  var price      = $('#item_id').find(':selected').data('price')
  
  var quantity   = $('#quantity').val();
  var total             = (price*quantity);
  $('#net_ammount').val(total);

  });
  
  $('#rate').keyup(function(){
    var other_charges = $("#rate").val();
    var net_val = $("#quantity").val();
    var final_value = net_val * other_charges;
     $("#net_ammount").val(final_value);
    
    
  });


  // $('#grand_parent_id').change(function(){
  //   var id    = $(this).val();
  //   var route = "{{ route('admin.cash.get_parent_accounts', ':id') }}";
  //   route     = route.replace(':id', id);

  //  if(id != ''){
  //     getAjaxRequests(route, "", "GET", function(resp){
  //       $('#parent_id').html(resp.html);
  //     });
  //   }
  // });

  // $('#item_id').change(function(){
  //   $('#rate').val($(this).find(':selected').data('price'));
  //   calculate_net_amount();
  // });
  // //calculate net amount
  // function calculate_net_amount(){
  //   var price      = $('#item_id').find(':selected').data('price')
  //   var quantity   = $('#quantity').val();
  //   var discount   = $('#discount').val();
  //   var commission = $('#commission').val();
    
  //   if(price != '' &&  quantity != '' && discount != '' && commission != ''){//if both values are set the put net amount in input field
  //     var total             = (price*quantity);
  //     var total_commission  = (total*commission)/100;
  //     var total_discount    = (discount*quantity);
  //     $('#net_ammount').val(total-(total_commission+total_discount));
  //     $('#commission').val(total_commission);
  //     $('#discount').val(total_discount);
  //   }
  // }
  // //when there is change in account then put the commissiona and discount in fields
  // $('#account_id').change(function(){
  //   $('#commission').val(($(this).find(':selected').data('commission')));
  //   $('#discount').val(($(this).find(':selected').data('discount')));
  //   calculate_net_amount();
  // });
//when there is change in quantity calculate total amount
// $(document).on('keypress', '#quantity', function(){
//   calculate_net_amount();
// });

</script>
@endsection