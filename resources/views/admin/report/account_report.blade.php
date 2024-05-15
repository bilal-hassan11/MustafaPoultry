@extends('layouts.admin')
@section('content')


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        
                        <h4 class="page-title">Account Ledger</h4>
                    </div>
                </div>
            </div>
                <form action="" id="form">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Account</label>
                            <select class="form-control js-example-basic-single" name="account_id" id="account_id">
                                <option value="">Select item</option>
                                @foreach($acounts AS $acc)
                                    <option value="{{ $acc->hashid }}">{{ $acc->name }}</option>
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
                <table class="table dt_table table-bordered w-100 nowrap"  id="laravel_datatable">
                    <thead>
                        <tr>
                            <th>Id.No</th>
                            <th>Date</th>
                            <th > Account Name </th>
                            <th> Description </th>
                            <th> Debit </th>
                            <th> Credit </th>
                          </tr>
                    </thead>
                    <tbody>
                        @foreach($account_ledger AS $al)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ date('d-m-Y', strtotime($al->created_at)) }}</td>
                          <td>{{ $al->account->name }}</td>
                          <td>{{ $al->description }}</td>
                          <td>{{ $al->debit }}</td>
                          <td>{{ $al->credit }}</td>

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
        // console.log(form_data);
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.account_pdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "account_report.pdf";
                link.click();
                return false;
            },
            error: function(blob){
                console.log(blob);
            }
        });
    });
</script>
<script>

$(document).ready( function () {
  $('.datatable').DataTable();

} );

</script>
@endsection