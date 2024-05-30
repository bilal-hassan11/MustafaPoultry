@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3>Low Selling Report</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3 table-responsive">
                                <table class="table table-bordered" id="sellingTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Item</th>
                                            <th>Total Quantity Sold</th>
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
            $('#sellingTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.stock.low_selling_report') }}",
                    data: function(d) {
                        // No additional filters needed
                    }
                },
                columns: [{
                        data: 'item_id',
                        name: 'item_id'
                    },
                    {
                        data: 'item.name',
                        name: 'item.name'
                    },
                    {
                        data: 'total_quantity',
                        name: 'total_quantity'
                    }
                ]
            });
        });
    </script>
@endsection
