@extends('layouts.admin')
@section('content')


<div class="main-content app-content mt-0">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
        <!-- PAGE-HEADER END --> <!-- ROW-1 --> 
      
        <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 --> 
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Inward Detail</h3>
                </div>
                <div class="card-body">
                
                    <form class="ajaxForm" role="form" action="{{ route('admin.purchases.store') }}" method="POST">
                      @csrf
                      <div  class="row" >
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Date</label>
                            <input class="form-control" type="date" name="purchase_date" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_purchase->date)) : date('Y-d-d') }}"
                            required>
                          </div>
                        </div>
                        <div class="col-md-3">
                              <div class="form-group">
                                  <label>Vehical No</label>
                                  <input class="form-control" name="vehicle_no" type="text" id="remarks"  value="{{ @$edit_purchase->vehicle_no }}"
                                  >
                              </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Bilty No</label>
                            <input class="form-control" type="number" name="bilty_no" value="{{ @$edit_purchase->bilty_no }}"
                            >
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>P.Invoice No</label>
                            <input class="form-control" type="text" name="prod_inv_no" value="{{ @$edit_purchase->pro_inv_no }}"
                            >
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group ">
                            <label>Account Name </label>                        
                            <select class="form-control select2" id="account_name" type="text" name="account_id"   >
                            <option value="">Select account </option>
                            @foreach($accounts AS $account)
                              <option value="{{ $account->hashid }}" @if(@$edit_purchase->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                            @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Item Name</label>
                            <select class="form-control select2"  type="text" id="item_name" name="item_id"   required>                          
                              <option value="">Select item</option>
                              @foreach($items AS $item)
                                <option value="{{ $item->hashid }}"  @if(@$edit_purchase->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label>Company Weight  </label>
                              <input class="form-control" type="number" name="company_weight" id="company_weight" value="{{ @$edit_purchase->company_weight }}" required>
                          </div>
                        </div>    
                        <div class="col-md-3">
                          <div class="form-group">
                              <label>party Weight  </label>
                              <input class="form-control" type="number" name="party_weight" id="party_weight" value="{{ @$edit_purchase->party_weight }}" required>
                          </div>
                        </div>
                      </div>  
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label> Weight  Difference </label>
                            <input class="form-control" readonly type="number"  name="weight_difference" id="weight_difference" value="{{ @$edit_purchase->party_weight_difference }}" required>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Rate</label>
                            <input class="form-control" name="rate" type="text" id="rate"  value="{{ @$edit_purchase->item->price }}"
                            required>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>No Of Begs</label>
                            <input class="form-control" name="no_of_begs" type="number" id="no_of_begs"  value="{{ @$edit_purchase->no_of_bags }}"
                            required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Remarks</label>
                            <input class="form-control" type="text" name="remarks" id="remarks" value="{{ @$edit_purchase->remarks }}" required>
                          </div>
                        </div>
                          
                      </div>
                      <br /><br />
                      <div class="row">
                        <div class="col-md-2">
                          <div class="form-group">
                              <label>Difference</label>
                              <input class="form-control" type="number" name="differenece" id="differenece" value="{{ @$edit_purchase->differenece != null ?  @$edit_purchase->differenece : 0 }}" >
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                              <label>posted weight </label>
                              <input class="form-control" type="number" name="posted_weight" id="posted_weight" value="{{ @$edit_purchase->posted_weight != null ?  @$edit_purchase->posted_weight : 0 }}" >
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                              <label>Loading Charges </label>
                              <input class="form-control" type="text" name="loading_charges" id="loading_charges" value="{{ @$edit_purchase->loading_charges != null ?  @$edit_purchase->loading_charges : 0 }}" >
                          </div>
                        </div>
                        @isset($is_update)
                          <?php 
                            $purchase_amount = $edit_purchase->item->price * $edit_purchase->company_weight;//5600
                            $get_commission = ($purchase_amount * $edit_purchase->account->commission)  /100 ; 
                          ?> 
                        @endisset
                        
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Commission (in %)</label>
                            <input class="form-control" type="text" name="commission" id="commission" value="{{ @$get_commission  != null ? @$get_commission :0  }}"
                            >
                          </div>
                        </div>
                        @isset($is_update)
                          <?php 
                              
                              $gross_Ammount = $get_commission + $purchase_amount;//
                
                              $net_ammount = $gross_Ammount - $edit_purchase->fare;
                
                          ?>
                        @endisset
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Gross Amount</label>
                            <input class="form-control" type="text" name="gross_ammount" id="gross_ammount" value="{{ @$gross_Ammount != null ? @$gross_Ammount : 0 }}"
                            >
                          </div>
                        </div> 

                      </div>

                      <div class="row">
                        <div class="col-md-3">
                            <div class="form-group ">
                              <label>Fare Status </label>                        
                              <select class="form-control select2" id="fare_Status" type="text" name="fare_Status" >
                              <option value="">Select Fare Status </option>

                              <option value="exmill">Exmill </option>
                              <option value="delivered">Delievered </option>
                              
                              </select>
                            </div>
                          </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label>Fare </label>
                              <input class="form-control" type="number" name="fare" id="fare" value="{{ @$edit_purchase->fare }}" >
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label>Other Charges  </label>
                              <input class="form-control" type="number" name="others_charges" id="others_charges" value="{{ @$edit_purchase->other_charges }}" >
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label>Net Amount  </label>
                              <input class="form-control" type="text" name="net_ammount" id="net_ammount" value="{{ @$net_ammount != null ? @$net_ammount : 0}}" >
                          </div>
                        </div>
                      </div>
                      
                      <div class="row" >
                        <div class="col-md-2 mt-4 mr-8">
                            <div class="form-group">
                              <input type="hidden" name="purchase_id" value="{{ @$inward_id }}">
                                <button type="submit" name="save_purchase" class="btn btn-success "><i class="fa fa-check"></i> save</button>
                            </div>
                        </div>
                      </div>
                  </form>  
                                
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
                    <h3 class="card-title mb-0">Filters</h3>
                </div>
                <div class="card-body">
                
                <form action="{{ route('admin.purchases.index') }}" method="GET">
                  @csrf
                  <div class="row">
                    <div class="col-md-3">
                      <label class="text-dark">Grand Parent</label>
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
                      <label class="text-dark">Parent Account</label>
                      <select class="form-control select2" name="parent_id" id="parent_id">
                        <option value="">Select  Account</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label class="text-dark">Items</label>
                      <select class="form-control select2" name="item_id" id="item_id">
                        <option value="">Select item</option>
                        @foreach($items AS $item)
                          <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  
                  </div>
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
          </div>
          <!-- COL END --> 
        </div>

        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Accounts Detail</h3>
                </div>
                <div class="card-body">
                
                <table  class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100" id="example">
                  <thead>
                      <tr class="text-dark">
                        <th>Id.No</th>
                        <th>Date</th>
                        <th> Vehicle No </th>
                        <th> DC No </th>
                        <th> Account Name </th>
                        <th> Item Name </th>
                        <th> Item Rate </th>
                        <th> No Of Begs </th>
                        <th> Fare </th>
                        <th> Commission </th>
                        <th> Net Value </th>
                        <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach($inwards AS $inward)
                      <tr class="text-dark">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d-M-Y', strtotime($inward->date)) }}</td>
                        <td>{{ $inward->vehicle_no }}</td>
                        <td>{{ $inward->gp_no }}</td>
                        <td> <span class="waves-effect waves-light btn btn-outline btn-success">{{ @$inward->account->name }} </span></td>
                        <td>{{ $inward->item->name }}</td>
                        <td>{{ $inward->item->price }}</td>
                        <td>{{ $inward->no_of_bags }}</td>
                        <td>{{ $inward->fare }}</td>
                        <?php 
                                    $get_commsission = @$inward->account->commission;
                                    $get_value = @$inward->item->price * @$inward->company_weight;
                                    $get_net_commission = ($get_commsission * $get_value)/100;
                                    
                                    $get_net_value = ((@$inward->item->price * @$inward->company_weight)+$get_net_commission)-$inward->fare ;
                                  ?>
                        <td>{{ @$get_net_commission }}</td>
                        <td>{{ @$get_net_value }}</td>

                        <td width="120">
                                <a href="{{route('admin.purchases.edit', $inward->hashid)}}"  >
                                <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                                </a>
                                @if(@$inward->purchase_status == "completed")
                                    <button type="button" style="color:green;"   class="waves-effect waves-light btn btn-rounded btn-success-light">
                                      <i class="fa-sharp fa-solid fa-plus"></i> &nbsp Posted
                                    </button>
                                @endif
                                @if(@$inward->purchase_status == "pending")
                                  <button type="button" style="color:green;" onclick="ajaxRequest(this)" data-url="{{ route('admin.purchases.migrate_to_purchase', ['id'=>$inward->hashid]) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
                                      <i class="fa-sharp fa-solid fa-plus"></i> &nbsp Post
                                    </button>
                                @endif
                            
                              </td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
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
@include('admin.partials.datatable')

<script>
  $('#account_name').change(function(){
    var id = $("#account_name").val();
    var url = '{{ route("admin.sales.account_details", ":id") }}';
    url = url.replace(':id', id);
    $.ajax({
            url: url,
            type: 'GET',
            success: function(resp){
              var c = resp.account.commission;
              var rate = $("#rate").val();

              if(rate != 0){
                var company_weight = $("#company_weight").val();
                var gross_val = Number(rate) * Number(company_weight);
                var net_c = (gross_val * c)/100;

              $('#commission').val(net_c);
              }else{
                $('#commission').val(c);
              }

              
              var posted_weight = $("#posted_weight").val();
              if(posted_weight == 0){
                var company_weight = $("#company_weight").val();
                var rate = $("#rate").val();
                var val = Number(rate) * Number(company_weight) ;
                
                var l_charges = $("#loading_charges").val();
                var c_charges = $("#commission").val();
                
                var l_c = Number(l_charges) +  Number(c_charges);
                var n_val = Number(val) + Number(l_c);
                var g_amt = $("#gross_ammount").val(n_val);

                var fare = $("#fare").val();
                var n = Number(n_val) - fare;
                var net = $("#net_ammount").val(n);
              }else{
                var rate = $("#rate").val();
                var val = Number(rate) * Number(posted_weight) ;
                
                var l_charges = $("#loading_charges").val();
                var c_charges = $("#commission").val();
                
                var l_c = Number(l_charges) +  Number(c_charges);
                var n_val = Number(val) + Number(l_c);
                var g_amt = $("#gross_ammount").val(n_val);

                var fare = $("#fare").val();
                var n = Number(n_val) - fare;
                var net = $("#net_ammount").val(n);
              }
              
              
              
            
            
            },
            error: function(){
                console.log("no response");
            }
      });

  });
</script>

<script>
  

  $('#item_name').change(function(){
    var id = $("#item_name").val();
    var url = '{{ route("admin.sales.item_details", ":id") }}';
    url = url.replace(':id', id);
    $.ajax({
            url: url,
            type: 'GET',
            success: function(resp){
             

              var posted_weight = $("#posted_weight").val();
              if(posted_weight == 0){
                var item_rate = resp.item.price;
                var rate = $("#rate").val(item_rate);
                
                var company_weight = $("#company_weight").val();
                var val = Number(item_rate) * Number(company_weight) ;
                
                var l_charges = $("#loading_charges").val();
                var c_charges = $("#commission").val();
                
                var l_c = Number(l_charges) +  Number(c_charges);
                var n_val = Number(val) + Number(l_c);
                var g_amt = $("#gross_ammount").val(n_val);

                var fare = $("#fare").val();
                var n = Number(n_val) - fare;
                var net = $("#net_ammount").val(n);
              }else{

                var item_rate = resp.item.price;
                var rate = $("#rate").val(item_rate);
                

                var val = Number(item_rate) * Number(posted_weight) ;
                
                var l_charges = $("#loading_charges").val();
                var c_charges = $("#commission").val();
                
                var l_c = Number(l_charges) +  Number(c_charges);
                var n_val = Number(val) + Number(l_c);
                var g_amt = $("#gross_ammount").val(n_val);

                var fare = $("#fare").val();
                var n = Number(n_val) - fare;
                var net = $("#net_ammount").val(n);
              }
              
              
              
            
            
            },
            error: function(){
                console.log("no response");
            }
      });

  });
</script>

<script>


$("#company_weight").keyup(function(){
   
    var rate = $("#rate").val();
    var c = $('#commission').val();
    
    var company_weight = $("#company_weight").val();
    var gross_val = Number(rate) * Number(company_weight);
    
    var n_gross = Number(gross_val) + Number(c) ;
    var p_weight = $("#gross_ammount").val(n_gross);

  });


  $("#rate").keyup(function(){
    var rate = $("#rate").val();
    var c = $('#commission').val();
    
    var company_weight = $("#company_weight").val();
    
    var gross_val = Number(rate) * Number(company_weight);
    
    var n_gross = Number(gross_val) + Number(c) ;
    var p_weight = $("#gross_ammount").val(n_gross);

  }); 

  
  
</script>

<script>
  function check_weight_difference(){//calculate the weight differnce
    var company_weight = $("input[name='company_weight']").val();
    var party_weight   = $("input[name='party_weight']").val();
    var weight_difference = 0;
    if(company_weight != '' && party_weight != ''){
        weight_difference = parseInt(party_weight) - parseInt(company_weight);
        $("input[name=weight_difference]").val(weight_difference);
    }
  }
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
  

  $("#differenece").keyup(function(){
    
    var difference = $("#differenece").val();
    var company_weight = $("#company_weight").val();
    var net = Number(company_weight) - Number(difference) ;
    var p_weight = $("#posted_weight").val(net);

    var rate = $("#rate").val();
    
    var val = Number(rate) * Number(net) ;
    var l_charges = $("#loading_charges").val();
    var c_charges = $("#commission").val();
    
    var l_c = Number(l_charges) +  Number(c_charges);
    var n_val = Number(val) + Number(l_c);
    var g_amt = $("#gross_ammount").val(n_val);

    
    var fare = $("#fare").val();
    var n = Number(n_val) - fare;
    var net = $("#net_ammount").val(n);
    

  
  });

  $("#loading_charges").keyup(function(){
    
    var difference = $("#differenece").val();
    var company_weight = $("#company_weight").val();
    var net = Number(company_weight) - Number(difference) ;
    var p_weight = $("#posted_weight").val(net);

    var rate = $("#rate").val();
    
    var val = Number(rate) * Number(net) ;
    var l_charges = $("#loading_charges").val();
    var c_charges = $("#commission").val();
    
    var l_c = Number(l_charges) +  Number(c_charges);
    var n_val = Number(val) + Number(l_c);
    var g_amt = $("#gross_ammount").val(n_val);

    var fare = $("#fare").val();
    var n = Number(n_val) - fare;
    var net = $("#net_ammount").val(n);
    
    
  
  });

  

  $("#fare_Status").change(function(){
    
    var fare_Status = $("#fare_Status").val();
    var gross_ammount = $("#gross_ammount").val();
    var fare = $("#fare").val();

    //alert(fare_Status);
    if(fare_Status === "exmill"){

      var net = $("#net_ammount").val(gross_ammount);

    }else{
      
      var net = Number(gross_ammount) - Number(fare);
      var f_net = $("#net_ammount").val(net);

    }

  
  });

  


</script>
@endsection