@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3>Expired Stock Report</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="categoryFilter">Filter by Category</label>
                                <select id="categoryFilter" class="form-control select2">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="itemFilter">Filter by Item</label>
                                <select id="itemFilter" class="form-control select2">
                                    <option value="">Select Item</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fromDateFilter">From Date</label>
                                <input type="date" id="fromDateFilter" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="toDateFilter">To Date</label>
                                <input type="date" id="toDateFilter" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3 table-responsive">
                                <table class="table table-bordered" id="stockTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
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
            $('.select2').select2();

            var stockTable = $('#stockTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.stock.expiry_stock_report') }}",
                    data: function(d) {
                        d.category = $('#categoryFilter').val();
                        d.item = $('#itemFilter').val();
                        d.from_date = $('#fromDateFilter').val();
                        d.to_date = $('#toDateFilter').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
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

            $('#categoryFilter').change(function() {
                var categoryId = $(this).val();
                fetchItems(categoryId);
                stockTable.ajax.reload();
            });

            $('#itemFilter').change(function() {
                stockTable.ajax.reload();
            });

            $('#fromDateFilter, #toDateFilter').change(function() {
                stockTable.ajax.reload();
            });

            function fetchItems(categoryId) {
                if (categoryId) {
                    $.ajax({
                        url: "{{ route('admin.stock.items.byCategory') }}",
                        method: "GET",
                        data: {
                            category_id: categoryId
                        },
                        success: function(response) {
                            $('#itemFilter').empty().append('<option value="">Select Item</option>');
                            $.each(response.items, function(key, item) {
                                $('#itemFilter').append('<option value="' + item.id + '">' +
                                    item.name + '</option>');
                            });
                        },
                        error: function() {
                            console.error('Error fetching items');
                        }
                    });
                } else {
                    $('#itemFilter').empty().append('<option value="">Select Item</option>');
                }
            }
        });
    </script>
@endsection
