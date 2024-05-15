
@extends('layouts.admin')
@section('content')

<div class="main-content">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
          <div class="card-block">
            <div class="item_row">
              <div class="row">
              
                </div>
                <h5>All Inward Details</h5>
                
                <br /><br />
                
              


  </div>
</div>

  <div  class="row ml-2">
    <div class="col-md-2">
      <div class="form-group ">
        <label>Item Name</label>
        <select class="form-control js-example-basic-multiple"  type="text" name="account_name"   required>
          <option value="">Select item</option>
          @foreach($items AS $item)
              <option value="{{ $item->hashid }}">{{ $item->name }}</option>
          @endforeach
          
        </select>
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group center">
        <label>Account Name</label>
        <select class="form-control js-example-basic-multiple"  type="text" name="account_name"   required>
          <option value="">Select item</option>
          @foreach($accounts AS $account)
              <option value="{{ $account->hashid }}">{{ $account->name }}</option>
          @endforeach
          
        </select>
      </div>
    </div>
    <div class="col-md-2 mt">
        <div class="form-group ">
          <label>From Date</label>
          <input class="form-control" type="date" name="sale_date" value="<?= @$sales['date'] ?$sales['date']:"" ?>"
                required>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group ">
          <label>To Date</label>
          <input class="form-control" type="date" name="sale_date" value="<?= @$sales['date'] ?$sales['date']:"" ?>"
                required>
        </div>
      </div>
              
  </div> 

  <div class="card-body">

  <div class="card-block mcontainer">
    <table class="table dt_table" id="example" style="width:100%">
      <thead>
          <tr>
            <th>Id.No</th>
            <th>Date</th>
            <th> Account Name </th>
            <th> Item Name </th>
            <th> Vehicle No </th>
            <th> No Of Bags </th>
            <th> Fare </th>
            <th> Bilty No </th>
            <th> GP no </th>
            <th>party Weight</th>
            <th> Company Weight </th>
            <th>Weight Difference</th>
            <th>Party Weight Difference</th>
            <th>Remarks</th>
          </tr>
      </thead>
      <tbody>
        @foreach($inwards AS $inward)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('m-D-Y', strtotime($inward->date)) }}</td>
            <td>{{ $inward->account->name }}</td>
            <td>{{ $inward->item->name }}</td>
            <td>{{ $inward->vehicle_no }}</td>
            <td>{{ $inward->no_of_bags }}</td>
            <td>{{ $inward->fare }}</td>
            <td>{{ $inward->bilty_no }}</td>
            <td>{{ $inward->gp_no }}</td>
            <td>{{ $inward->party_weight }}</td>
            <td>{{ $inward->company_weight }}</td>
            <td>{{ $inward->weight_difference }}</td>
            <td>{{ $inward->party_weight_difference }}</td>
            <td>{{ $inward->remarks }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
      </tfoot>
    </table>
    {{-- <table class="table table-striped table-bordered table-hover table-checkable order-column display" id="example" style="width:100%">
      <thead>
          <tr>
            <th>Id.No</th>
            <th>Date</th>
            <th> Account Name </th>
            <th> Item Name </th>
            <th> Posted Weight </th>
            <th> Rate </th>
            <th> Gross Ammount </th>
            <th> Fare </th>
            <th> Net Value </th>
            <th> Remarks </th>
            <th> Action </th>
          </tr>
      </thead>
      <tbody>
          @foreach($purchases AS $purchase)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ date('d-m-Y', strtotime($purchase->date)) }}</td>
              <td>{{ $purchase->account->name }}</td>
              <td>{{ $purchase->item->name }}</td>
              <td>{{ $purchase->posted_weight }}</td>
              <td>{{ $purchase->bag_rate }}</td>
              <td></td>
              <td>{{ $purchase->fare }}</td>
              <td>{{ $purchase->net_amount }}</td>
              <td>{{ $purchase->remarks }}</td>
              <td width="120">
                <a href="{{route('admin.purchases.edit', $purchase->hashid)}}" class="btn btn-warning btn-xs waves-effect waves-light">
                    <i class="fas fa-edit"></i>
                </a>
                <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.purchases.delete', $purchase->hashid) }}"  class="btn btn-danger btn-xs waves-effect waves-light">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
            </tr>
          @endforeach
      </tbody>
      <tfoot>
      </tfoot>
    </table> --}}
  </div>
  </div>

</div>

</div>
</div>

@endsection

@section('page-scripts')
@include('admin.partials.datatable')
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
</script>
@endsection