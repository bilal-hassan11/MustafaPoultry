
@extends('layouts.admin')
@section('content')

<div class="main-content"> 
    @if(isset($is_update) && $is_update)
    <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title text-dark">Edit Sales</h2>
        </div>
        <div class="box-body">
            <form action="{{ route('admin.sales.update_sale') }}" class="ajaxForm" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="">Date</label>
                        <input type="date" class="form-control" name="date" id="sale" value="{{ isset($is_update) ? date('Y-m-d', strtotime(@$edit_sale->date)) : date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">Vehicle No</label>
                        <input type="text" class="form-control" name="vehicle_no" id="vehicle_no" value="{{ $edit_sale->vehicle_no }}" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">DC No</label>
                        <input type="text" class="form-control" name="gp_no" id="gp_no" value="{{ $edit_sale->gp_no }}" required readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Account Name</label>
                        <select class="form-control select2" name="account_id" id="account_id" required>
                            <option value="">Select account</option>
                            @foreach($accounts AS $account)
                                <option value="{{ $account->hashid }}" @if($account->id == $edit_sale->account_id) selected @endif>{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Subdealer Name</label>
                        <input type="text" class="form-control" name="sub_dealer_name" id="sub_dealer_name" value="{{ $edit_sale->sub_dealer_name }}" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="">Item Name</label>
                        <select class="form-control select2" name="item_id" id="item_id" required>
                            <option value="">Select Item</option>
                            @foreach($items AS $item)
                                <option value="{{ $item->hashid }}" @if($item->id == $edit_sale->item_id) selected @endif>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">No of Bags</label>
                        <input type="text" class="form-control" name="no_of_bags" id="no_of_begs" value="{{ $edit_sale->no_of_bags }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Bag Rate</label>
                        <input type="text" class="form-control" name="bag_rate" id="bag_rate" value="{{ $edit_sale->bag_rate }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Fare</label>
                        <input type="text" class="form-control" name="fare" id="fare" value="{{ $edit_sale->fare }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="">Discount</label>
                        <input type="text" class="form-control" disabled name="discount" id="discount" value="{{ $edit_sale->discount }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Commission</label>
                        <input type="text" class="form-control" disabled name="commission" id="commission" value="{{ $edit_sale->commission }}" required> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Net Value</label>
                        <input type="text" class="form-control" disabled name="net_ammount" id="net_ammount" value="{{ $edit_sale->net_ammount }}" required>
                    </div>
                </div>
                
                <input type="hidden" name="sale_book_id" value="{{ $edit_sale->hashid }}">
                <input type="submit" class="btn btn-primary" value="Update">
            </form>
        </div>
    </div>
    @endif

    <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title text-dark">Filters</h2>
        </div>
        <div class="box-body">
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
            <div class="col-md-3">
                <label for="">Vehicle No</label>
                <input type="text" class="form-control" name="vehicle_no" id="vehicle_no">
            </div>
            </div><br />
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
    <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title text-dark">All Sales Entries</h2>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                    <thead>
                        <tr class="text-dark">
                            <th>Date</th>
                            <th> Vehicle No </th>
                            <th>GP NO</th>
                            <th> Account Name </th>
                            <th> Subdealer Name</th>
                            <th> Item Name </th>
                            <th> Item Rate </th>
                            <th> Quantity </th>
                            <th> Fare </th>
                            <th> Commission </th>
                            <th> Discount </th>
                            <th> Net Value </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $line)
                            
                            <tr class="text-dark">
                                <td>{{ date('m-D-Y', strtotime($line->date)) }}</td>
                                @if(!$loop->first && $line->vehicle_no ==$sales[$loop->index - 1]->vehicle_no)
                                <td>-</td>
                                @endif
                                @if(!$loop->first && $line->vehicle_no !=$sales[$loop->index - 1]->vehicle_no)
                                <td>{{ $line->vehicle_no }}</td>
                                @endif
                                @if($loop->first )
                                <td>{{ $line->vehicle_no }}</td>
                                @endif
                                
                                @if(!$loop->first && $line->gp_no ==$sales[$loop->index - 1]->gp_no)
                                <td>-</td>
                                @endif
                                @if(!$loop->first && $line->gp_no !=$sales[$loop->index - 1]->gp_no)
                                <td>{{ $line->gp_no }}</td>
                                @endif
                                @if($loop->first )
                                <td>{{ $line->gp_no }}</td>
                                @endif

                                @if($loop->first )
                                <td>{{ $line->account->name }}</td>
                                @endif
                                @if(!$loop->first && $line->account->name == $sales[$loop->index - 1]->account->name && $line->gp_no == $sales[$loop->index - 1]->gp_no )
                                
                                <td>-</td>
                                
                                @endif 
                                @if(!$loop->first && $line->account->name == $sales[$loop->index - 1]->account->name && $line->gp_no != $sales[$loop->index - 1]->gp_no )
                                <td>{{ $line->account->name }}</td>
                                @endif 

                                @if(!$loop->first && $line->account->name !=$sales[$loop->index - 1]->account->name && $line->gp_no != $sales[$loop->index - 1]->gp_no )
                                <td>{{ $line->account->name }}</td>
                                @endif

                            

                                <td>{{ $line->sub_dealer_name }}</td>
                                <td>{{ @$line->item->name }}</td>
                                <td>{{ @$line->bag_rate }}</td>
                                <td>{{ @$line->no_of_bags    }}</td>
                                <td>{{ $line->fare }}</td>
                            
                                    
                                <td>{{ @$line->commission    }}</td>
                                <td>{{ @$line->discount    }}</td>
                                <td>{{ number_format(@$line->net_ammount) }}</td>
                            
                                <td width="120">
                                    <a href="{{route('admin.sales.edit_sale', $line->hashid)}}" >
                                    <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                                    </a>
                                    <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.sales.delete_sale', ['id'=>$line->hashid]) }}"  class="waves-effect waves-light btn btn-rounded btn-warning-light">
                                    <i class="fa-sharp fa-solid fa-plus"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        
                        @endforeach
                        
                    </tbody>
                </table>
            </div>              
            </div>
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


    $("#no_of_begs").keyup(function(){
    
        var no_of_begs = $("#no_of_begs").val();
        
        var rate = $("#bag_rate").val();
        var fare = $("#fare").val();
        var fare = $("#fare").val();

        var add_net = net +  tot_begs;
        var begs = $("#total_begs").val(net);

    
    });

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
</script>
@endsection