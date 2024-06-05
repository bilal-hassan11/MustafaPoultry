@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3>Available Stock</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4 align-items-end">
                            <div class="col-md-6 mb-3">
                                <label for="categoryFilter">Filter by Category</label>
                                <select id="categoryFilter" class="form-control select2">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="itemFilter">Filter by Item</label>
                                <select id="itemFilter" class="form-control select2">
                                    <option value="">Select Item</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <form action="{{ route('admin.stock.index') }}" method="GET" target="_blank">
                                    @csrf
                                    <input type="hidden" name="category" id="categoryInput">
                                    <input type="hidden" name="item" id="itemInput">
                                    <input type="hidden" name="generate_pdf" value="1">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="ri-download-2-line"></i> Download PDF
                                    </button>
                                </form>
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
                                            <th>Average Amount</th>
                                            <th>Amount</th>
                                            <th>Expiry Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right">
                                                Grand Total
                                            </th>
                                            <th id="grand-total" style="text-align:right">
                                            </th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
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
                    url: "{{ route('admin.stock.index') }}",
                    data: function(d) {
                        d.category = $('#categoryFilter').val();
                        d.item = $('#itemFilter').val();
                    },
                    dataSrc: function(json) {
                        var grandTotal = json.grandTotal;
                        $('#grand-total').text('Rs ' + grandTotal);
                        return json.data;
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
                        render: function(data, type, row) {
                            return '<span style="text-align: right; display: block;">' + data +
                                '</span>';
                        }
                    },
                    {
                        data: 'avg_amount',
                        name: 'avg_amount',
                        render: function(data, type, row) {
                            return '<span style="text-align: right; display: block;">Rs ' +
                                data +
                                '</span>';
                        }
                    },
                    {
                        data: 'total_cost',
                        name: 'total_cost',
                        render: function(data, type, row) {
                            return '<span style="text-align: right; display: block;">Rs ' +
                                data +
                                '</span>';
                        }
                    },
                    {
                        data: 'expiry_date',
                        name: 'expiry_date',
                        render: function(data, type, row) {
                            return '<span style="display: block;">' + data + '</span>';
                        }
                    }
                ]
            });

            $('#categoryFilter').change(function() {
                var categoryId = $(this).val();
                $('#categoryInput').val(categoryId);
                fetchItems(categoryId);
                stockTable.ajax.reload();
            });

            $('#itemFilter').change(function() {
                var itemId = $(this).val();
                $('#itemInput').val(itemId);
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
                            $('#itemFilter').empty().append(
                                '<option value="">Select Item</option>');
                            $.each(response.items, function(key, item) {
                                $('#itemFilter').append('<option value="' + item.id +
                                    '">' +
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