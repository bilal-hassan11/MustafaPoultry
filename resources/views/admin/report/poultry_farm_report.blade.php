@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="card">
                <div class="card-header">
                    <h4>Poultry Farm Report</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="from_date">From</label>
                            <input type="date" class="form-control" name="from_date" id="from_date" required>
                        </div>
                        <div class="col-md-2">
                            <label for="to_date">To</label>
                            <input type="date" class="form-control" name="to_date" id="to_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="account" class="required">Account</label>
                            <select class="form-control select2" name="account" id="account_id" required>
                                <option value="">Select Account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mt-6">
                            <a href="#" id="download-pdf" class="btn btn-outline-danger rounded-pill btn-wave"
                                target="_blank" title="Download">
                                <i class="ri-download-2-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-scripts')
    <script>
        $(document).ready(function() {
            $('#download-pdf').on('click', function(e) {
                e.preventDefault();

                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var account = $('#account_id').val();

                if (fromDate && toDate && account) {
                    var url = "{{ route('admin.reports.poultry-farm-report') }}?from_date=" + fromDate +
                        "&to_date=" + toDate + "&account=" + account + "&generate_pdf=1";
                    $(this).attr('href', url);
                    window.open(url, '_blank');
                } else {
                    toastr.error('Please fill in all the fields.');
                }
            });
        });
    </script>
@endsection
