@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Report</li>
                </ol>
            </div>
            <h4 class="page-title">Outward Report</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="" id="form">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Items</label>
                            <select class="form-control" name="item_id" id="item_id">
                                <option value="">Select item</option>
                                @foreach($items AS $item)
                                    <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="">From Date</label>
                            <input type="date" class="form-control" name="from_date" id="from_date">
                        </div>
                        <div class="col-md-4">
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
                    <h4 class="header-title">Items Report</h4>
                </div>
                <p class="sub-header">Following is the list of all the items report.</p>
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
                          </tr>
                    </thead>
                    <tbody>
                        @foreach($outward AS $outw)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ date('d-m-Y', strtotime($outw->date)) }}</td>
                          <td>{{ @$outw->account->name }}</td>
                          <td>{{ @$outw->item->name }}</td>
                          <td>{{ $outw->posted_weight }}</td>
                          <td>{{ $outw->bag_rate }}</td>
                          <td></td>
                          <td>{{ $outw->fare }}</td>
                          <td>{{ $outw->net_amount }}</td>
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
            url: "{{ route('admin.reports.outward_pdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "outward_report.pdf";
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
        var route = "{{ route('admin.reports.outward_print') }}";
        $('form').attr('action', route);
        $('form').attr('target', '_blank');;
        $('#form').submit();

    });
</script>
@endsection