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
            <div id="reports-container">
                <!-- Report cards will be populated here -->
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
                        populateReports(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            function populateReports(reports) {
                var reportsContainer = $('#reports-container');
                reportsContainer.empty();

                var keysOrder = ['opening_stock', 'total_purchases', 'purchase_returns', 'total_sales',
                    'sales_returns', 'net_sales', 'closing_stock', 'cost_of_goods_sold', 'gross_profit'
                ];

                var labels = {
                    'opening_stock': 'Opening Stock',
                    'total_purchases': 'Total Purchases',
                    'purchase_returns': 'Purchase Returns',
                    'total_sales': 'Total Sales',
                    'sales_returns': 'Sales Returns',
                    'net_sales': 'Net Sales',
                    'closing_stock': 'Closing Stock',
                    'cost_of_goods_sold': 'Cost of Goods Sold',
                    'gross_profit': 'Gross Profit'
                };

                $.each(reports, function(invoiceType, data) {
                    var card = $('<div class="card mt-3"></div>');
                    var cardHeader = $('<div class="card-header"><h3 class="card-title mb-0">' +
                        invoiceType + ' Income Report</h3></div>');
                    var cardBody = $('<div class="card-body"></div>');
                    var table = $('<table class="table table-bordered"></table>');
                    var thead = $('<thead><tr><th>Particular</th><th>Amount</th></tr></thead>');
                    var tbody = $('<tbody></tbody>');

                    $.each(keysOrder, function(index, key) {
                        if (data.hasOwnProperty(key)) {
                            tbody.append('<tr><td class="text-right">' + labels[key] +
                                '</td><td style="text-align:right;">' + data[key] +
                                '</td></tr>');
                        }
                    });

                    table.append(thead).append(tbody);
                    cardBody.append(table);
                    card.append(cardHeader).append(cardBody);
                    reportsContainer.append(card);
                });
            }
        });
    </script>
@endsection
