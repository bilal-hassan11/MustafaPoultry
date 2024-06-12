@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="card">
                <div class="card-header">
                    <h4>Income Report</h4>
                </div>
                <div class="card-body">
                    <form id="income-report-form">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="from_date">From</label>
                                <input type="date" class="form-control" name="from_date" id="from_date">
                            </div>
                            <div class="col-md-2">
                                <label for="to_date">To</label>
                                <input type="date" class="form-control" name="to_date" id="to-date">
                            </div>
                            <div class="col-md-1 mt-6">
                                <input type="submit" class="btn btn-primary" value="Search">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Medicine Income Report</h3>
                </div>
                <div class="card-body">
                    <table id="income-report-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Particular</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script>
        $(document).ready(function() {
            $('#income-report-form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                fetchIncomeReport(formData);
            });

            function fetchIncomeReport(formData) {
                $.ajax({
                    url: "{{ route('admin.reports.income-report') }}",
                    method: 'GET',
                    data: formData,
                    success: function(response) {
                        populateIncomeReportTable(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            function populateIncomeReportTable(data) {
                var tableBody = $('#income-report-table tbody');
                tableBody.empty();
                var keysOrder = ['opening_stock', 'total_purchases', 'purchase_returns', 'total_sales',
                    'sales_returns', 'net_sales', 'closing_stock', 'cost_of_goods_sold', 'gross_profit'
                ];
                $.each(keysOrder, function(index, key) {
                    if (data.hasOwnProperty(key)) {
                        tableBody.append('<tr><td class="text-right">' + key.replace(/_/g, ' ') +
                            '</td><td style="text-align:right;">' + data[key] +
                            '</td></tr>');
                    }
                });
            }

        });
    </script>
@endsection
