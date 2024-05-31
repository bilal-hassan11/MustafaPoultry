@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>{{ $type }} Invoice</h3>
                    </div>
                    <div class="card-header bg-primary text-white">
                        <div class="row">
                            <div class="col-md-8">
                                <h3>Invoice #{{ $feedInvoice[0]->invoice_no }}</h3>
                                <p class="mb-0">Date: {{ $feedInvoice[0]->date }}</p>
                                <p class="mb-0">Account: {{ $feedInvoice[0]->account->name }}</p>
                                <p class="mb-0">Description: {{ $feedInvoice[0]->description }}</p>
                            </div>
                            <div class="col-md-4" style="text-align: right;">
                                <a class="btn btn-secondary mb-3" href="{{ url()->previous() }}">
                                    Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>{{ $type }} Price</th>
                                    <th>Expiry</th>
                                    <th>Discount (Rs)</th>
                                    <th>Discount (%)</th>
                                    <th>Total Returned</th>
                                    <th>Net Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotal = 0;
                                    $totalDiscountRs = 0;
                                @endphp
                                @foreach ($feedInvoice as $index => $item)
                                    <tr>
                                        <td style="text-align: center;">{{ $index + 1 }}</td>
                                        <td>{{ $item->item->name }}</td>
                                        <td style="text-align: right;">{{ $item->quantity }}</td>
                                        <td style="text-align: right;">Rs
                                            {{ number_format($type == 'Purchase' ? $item->purchase_price : $item->sale_price, 2) }}
                                        </td>
                                        <td>{{ $item->expiry_date ?? '' }}</td>
                                        <td style="text-align: right;">Rs {{ number_format($item->discount_in_rs, 2) }}
                                        </td>
                                        <td style="text-align: right;">{{ number_format($item->discount_in_percent, 2) }}%
                                        </td>
                                        <td style="text-align: right;">
                                            {{ $item->total_returned ?? 0 }}
                                        </td>
                                        <td style="text-align: right;">Rs {{ number_format($item->net_amount, 2) }}</td>
                                        <td>
                                            <button class="btn-sm btn-primary open-modal" data-id="{{ $item->id }}"
                                                data-quantity="{{ $item->quantity }}"
                                                data-description="{{ $item->description }}"
                                                data-totalreturned="{{ $item->total_returned }}">
                                                <i class="bi bi-arrow-return-right"></i>
                                            </button>
                                        </td>

                                    </tr>
                                    @php
                                        $subtotal += $item->quantity * $item->purchase_price;
                                        $totalDiscountRs += $item->discount_in_rs;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot style="text-align: right;">
                                <tr>
                                    <th colspan="8">Subtotal</th>
                                    <th>Rs {{ number_format($subtotal, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="8">Total Discount</th>
                                    <th>Rs {{ number_format($totalDiscountRs, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="8">Net Amount</th>
                                    <th>Rs {{ number_format($subtotal - $totalDiscountRs, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Return Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="returnModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">Return Item</h5>
                    <button type="button" class="custom-close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="returnForm">
                        @csrf
                        <input type="hidden" id="feed_invoice_id" name="feed_invoice_id">
                        <input type="hidden" id="type" name="type" value="{{ $type }} Return">
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                            <div id="quantity-error" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Return</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script>
        $(document).ready(function() {
            $('.open-modal').click(function() {
                var id = $(this).data('id');
                var quantity = $(this).data('quantity');
                var description = $(this).data('description');
                var totalreturned = $(this).data('totalreturned');
                let remainingQty = quantity - totalreturned;
                $('#feed_invoice_id').val(id);
                $('#quantity').val(1);
                $('#returnModal').modal('show');
                $('#quantity').attr('max', remainingQty);
            });

            $('.custom-close').click(function() {
                $('#returnModal').modal('hide');
            });


            $('#returnForm').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                var quantity = $('#quantity').val();
                var maxQuantity = $('#quantity').attr('max');

                if (parseInt(quantity) > parseInt(maxQuantity)) {
                    $('#quantity').addClass('is-invalid');
                    $('#quantity-error').text('Quantity cannot be more than ' + maxQuantity);
                    return false;
                } else {
                    $('#quantity').removeClass('is-invalid');
                    $('#quantity-error').text('');
                }

                $.ajax({
                    url: "{{ route('admin.feed-invoices.single-return') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Item returned successfully!',
                        }).then(() => {
                            $('#returnModal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function(response) {
                        let errors = response.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });

                        if (response.responseJSON.error) {
                            toastr.error(response.responseJSON.error);
                        }
                    }
                });
            });

        });
    </script>
@endsection
