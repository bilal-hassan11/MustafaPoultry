@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3>Low Stock Report</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3 table-responsive">
                                <table class="table table-bordered" id="stockTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Category</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Expiry Date</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
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
            $('#stockTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.stock.low_stock_report') }}",
                    data: function(d) {}
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'item.category.name',
                        name: 'item.category.name'
                    },
                    {
                        data: 'item.name',
                        name: 'item.name'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'rate',
                        name: 'rate'
                    },
                    {
                        data: 'expiry_date',
                        name: 'expiry_date'
                    }
                ]
            });
        });
    </script>
@endsection
