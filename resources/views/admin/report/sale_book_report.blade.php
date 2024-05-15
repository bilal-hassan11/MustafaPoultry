@extends('layouts.admin')
@section('content')


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        
                        <h4 class="page-title">Sale Book Report</h4>
                    </div>
                </div>
            </div>
                <form action="" id="form">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Items</label>
                            <select class="form-control select2" name="item_id" id="item_id">
                                <option value="">Select item</option>
                                @foreach($items AS $item)
                                    <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Accounts</label>
                            <select class="form-control select2" name="account_id" id="account_id">
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
                     <button class="btn btn-warning mt-2" id="print">Print</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="header-title">sale Book Report</h4>
                </div>
                <table class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100" id="example">
                    <thead>
                        <tr class="text-dark">
                            <thead>
                                <tr>
                                  <th>Id.No</th>
                                  <th>Date</th>
                                  <th> Account Name </th>
                                  <th> Item Name </th>
                                  <th> No Of Bags </th>
                                  <th> Bags Rate </th>
                                  <th> Net Value </th>
                                  <th> Remarks </th>
                                  <!-- <th> Action </th> -->
                                </tr>
                            </thead>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales AS $sale)
                        <tr class="text-dark">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ date('d-M-Y', strtotime($sale->date)) }}</td>
                          <td>{{ @$sale->account->name }}</td>
                          <td>{{ @$sale->item->name }}</td>
                          <td>{{ @$sale->no_of_bags }}</td>
                          <td>{{ $sale->bag_rate }}</td>
                          <td>{{ $sale->net_ammount }}</td>
                          <td>{{ $sale->remarks }}</td>
                          <!-- <td width="120">
                            <a href="{{route('admin.sales.edit', $sale->hashid)}}" class="btn btn-warning btn-xs waves-effect waves-light">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.sales.delete', $sale->hashid) }}"  class="btn btn-danger btn-xs waves-effect waves-light">
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
            url: "{{ route('admin.reports.sale_pdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "sale_book_report.pdf";
                link.click();
                return false;
            },
            error: function(blob){
                console.log(blob);
            }
        });
    });

    // //when click on print
    $('#print').click(function(event){
        event.preventDefault();
        var route = "{{ route('admin.reports.sale_print') }}";
        $('form').attr('action', route);
        $('form').attr('target', '_blank');;
        $('#form').submit();
    });
</script>
@endsection