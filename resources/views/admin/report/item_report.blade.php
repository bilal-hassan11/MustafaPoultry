@extends('layouts.admin')
@section('content')


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        
                        <h4 class="page-title">Items Report</h4>
                    </div>
                </div>
            </div>
                <form action="" id="form">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Items</label>
                            <select class="form-control js-example-basic-single" name="item_id" id="item_id" required>
                                <option value="">Select item</option>
                                @foreach($items AS $item)
                                    <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="">From Date</label>
                            <input type="date" class="form-control" name="from_date" id="from_date" required>
                        </div>
                        <div class="col-md-4">
                            <label for="">To Date</label>
                            <input type="date" class="form-control" name="to_date" id="to_date" required>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary float-right mt-2">
                    <button class="btn btn-danger mt-2" id="pdf">PDF</button>
                    <button class="btn btn-warning mt-2" id="print">Print</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="box">
        <div class="box-header with-border">
            <h4 class="box-title">ALL Purchase Items</h4>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                <thead>
                    <tr class="text-dark">
                        <th>Id.No</th>
                        <th>Date</th>
                        <th colspan="1"> Account Name </th>
                        <th> Item Name </th>
                        <th> Posted Weight </th>
                        <th> Rate </th>
                        <th> Gross Ammount </th>
                        <th> Fare </th>
                        <th> Net Value </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($purchases AS $purchase)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ date('d-m-Y', strtotime($purchase->date)) }}</td>
                          <td>{{ @$purchase->account->name }}</td>
                          <td>{{ @$purchase->item->name }}</td>
                          <td>{{ $purchase->posted_weight }}</td>
                          <td>{{ $purchase->bag_rate }}</td>
                          <td></td>
                          <td>{{ $purchase->fare }}</td>
                          <td>{{ $purchase->net_amount }}</td>
                        </tr>
                      @endforeach
                </tbody>
            </table>
            </div>              
        </div>
        <!-- /.box-body -->
        </div>
        <!-- /.box -->          
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
            url: "{{ route('admin.reports.item_pdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "item_report.pdf";
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
        var route = "{{ route('admin.reports.item_print') }}";
        $('form').attr('action', route);
        $('form').attr('target', '_blank');;
        $('#form').submit();

    });
</script>
@endsection