@extends('layouts.admin')
@section('content')


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        
                        <h4 class="page-title">Purchase Book Report</h4>
                    </div>
                </div>
            </div>
                <form action="">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Items</label>
                            <select class="form-control js-example-basic-single" name="item_id" id="item_id">
                                <option value="">Select item</option>
                                @foreach($items AS $item)
                                    <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Accounts</label>
                            <select class="form-control js-example-basic-single" name="account_id" id="account_id">
                                <option value="">Select account</option>
                                @foreach($accounts AS $account)
                                    <option value="{{ $account->hashid }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">From Date</label>
                            <input type="date" class="form-control" name="from_date" id="from_date">
                        </div>
                        <div class="col-md-3">
                            <label for="">To Date</label>
                            <input type="date" class="form-control" name="to_date" id="to_date">
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary float-right mt-2">
                    <button class="btn btn-danger mt-2" id="pdf">PDF</button> 
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="header-title">Purchase Book Report</h4>
                </div>
                <p class="sub-header">Following is the list of all the purchase book report.</p>
                <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
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
                          <!-- <th> Action </th> -->
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
                          <!-- <td width="120">
                            <a href="{{route('admin.purchases.edit', $purchase->hashid)}}" class="btn btn-warning btn-xs waves-effect waves-light">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.purchases.delete', $purchase->hashid) }}"  class="btn btn-danger btn-xs waves-effect waves-light">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td> -->
                        </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
<script>
    $('#pdf').click(function(event){
        event.preventDefault();
        var form_data = $('form').serialize();
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.purchase_pdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "purchase_book_report.pdf";
                link.click();
                return false;
            },
            error: function(blob){
                console.log(blob);
            }
        });
    });
</script>
@endsection