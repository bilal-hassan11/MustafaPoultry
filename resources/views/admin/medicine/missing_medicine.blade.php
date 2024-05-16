
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
                    <h3 class="card-title mb-0">Add Missing Medicine Details</h3>
                </div>
                <div class="card-body">
                
                <div class="card-block">
            <div class="item_row">
              
            <form class="ajaxForm" role="form" action="{{ route('admin.medicines.missing_store') }}" method="POST">
              @csrf
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Date</label>
                      <input class="form-control" type="date" name="date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_medicine->date)) : date('Y-m-d') }}" required>
                    </div>
                  </div>
                  <div class="col-md-2 form-group">
                      <label for="">Invoice No</label>
                      <input type="text" class="form-control " name="Invoice_no" id="Invoice_no" value="{{ @$edit_medicine->invoice_no }}" required>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Item (selectd Item)</label>
                      <select class="form-control select2" name="item_id" id="item_id">
                        <option value="">Select Item</option>
                        @foreach($items AS $item)
                          <option value="{{ $item->hashid }}" data-price="{{ $item->purchase_ammount }}" @if(@$edit_medicine->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Quantity</label>
                      <input class="form-control" name="quantity" id="quantity" value="{{ @$edit_medicine->quantity }}" required>
                    </div>
                  </div>
                  
                </div>

                </div>
               
                
                <div class="row">
                    <div class="col-md-12 form-group">
                      <label for="">Remarks</label>
                      <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="4">{{ @$edit_medicine->remarks }}</textarea>
                    </div>
                </div>
                <input type="hidden" name="missing_medicine_id" value="{{ @$edit_medicine->hashid }}">
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
                    <h3 class="card-title mb-0"> Missing Medicine Filters</h3>
                </div>
                <div class="card-body">
                <form action="{{ route('admin.medicines.missing_medicine') }}" method="GET">
          @csrf
          <div class="row">
            
            
            <div class="col-md-3">
              <label for="">Invoice No</label>
              <input type="text" class="form-control" name="invoice_no" id="invoice_no">
            </div>
            <div class="col-md-3">
              <label for="">Item</label>
              <select class="form-control select2" name="item_id" id="item_id">
                <option value="">Select Item</option>
                @foreach($items AS $item)
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
            <div class="col-md-1 mt-3">
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
                    <h3 class="card-title mb-0">All Missing Medicine  Detail</h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                <thead>
                        <tr class="text-dark">
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($missing_medicines AS $m_s) 
                        <tr class="text-dark">
                          <td>{{ date('d-M-Y', strtotime(@$m_s->date)) }}</td>
                          <td>{{ @$m_s->invoice_no }}</td>
                          <td>{{ @$m_s->item->name }}</td>
                          <td>{{ @$m_s->quantity }}</td>
                          <td>
                            <a href="{{ route('admin.medicines.missing_edit',['id'=>$m_s->hashid]) }}" >
                            <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                            </a>
                            <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.medicines.missing_delete', ['id'=>$m_s->hashid]) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
                            <i class="fa-sharp fa-solid fa-trash"></i> &nbsp 
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