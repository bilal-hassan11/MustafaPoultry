@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h4>Purchase Medicine </h4>
                    </div>
                    <div class="card-body">
                        <form id="formData">
                            @csrf
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label for="invoice_no" class="required">Invoice No</label>
                                    <input type="text" name="invoice_no" class="form-control text-right"
                                        value="{{ $invoice_no }}" readonly>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" name="date" class="form-control text-right"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-4 mb-3"></div>
                                <div class="col-md-4 mb-3">
                                    <label for="ref_no" class="required">Ref No</label>
                                    <input type="text" name="ref_no" class="form-control text-right" value="0">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_id">Account</label>
                                    <select class="form-control select2" name="account_id" id="account_id11">
                                        <option value="">Select Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="description" class="required">Description</label>
                                    <input type="text" name="description" class="form-control text-right"
                                        placeholder="Description">
                                </div>
                            </div>
                    </div>
                    <div class="card-body" style="width: 100%; overflow-x: auto">
                        <table class="table table-bordered text-center add-stock-table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Item</th>
                                    <th style="width: 10%;">Quantity</th>
                                    <th style="width: 10%;">Rate</th>
                                    <th style="width: 20%;">Expiry</th>
                                    <th style="width: 10%;">Dis In (Rs)</th>
                                    <th style="width: 10%;">Dis In (%)</th>
                                    <th style="width: 20%;">Amount</th>
                                    <th style="width: 20%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="row">
                            </tbody>
                            <tfoot>
                                <tr style="text-align: right;">
                                    <td colspan="5">
                                        <label>Grand Total</label>
                                    </td>
                                    <td>
                                        <input type="number" name="total_discount" class="form-control text-right"
                                            value="0">
                                    </td>
                                    <td>
                                        <input type="number" name="grand_total" class="form-control text-right"
                                            value="0">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm add-row">Add Row</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <button type="submit" class="btn btn-primary mt-2">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script type="text/javascript">
        let productDetailsArray = {!! json_encode($products->keyBy('id')->toArray()) !!};
        $(document).ready(function() {
            addRow();
            $(".add-row").click(addRow);

            function addRow() {
                let row = `
        <tr class="rows">
            <td class="product_col">
                @if ($products)
                <select class="form-control product product_val select2" name="product[]" id="products" required>
                    <option value="">Select Items</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name ?? '' }}</option>
                    @endforeach
                </select>
                @endif
            </td>
            <td class="quantity_col">
                <input type="number" name="quantity[]" class="form-control quantity text-right" min="1" value="1">
            </td>
            <td class="purchase_rate">
                <input type="number" name="purchase_rate[]" class="form-control purchaseRate text-right" value="1">
            </td>
            <td class="expiry_date">
                <input type="date" name="expiry_date[]" class="form-control text-right" value="1">
            </td>
            <td class="dis_in_rs">
                <input type="number" name="dis_in_rs[]" class="form-control dis_in_rs text-right" value="0">
            </td>
            <td class="dis_in_percentage">
                <input type="number" name="dis_in_percentage[]" class="form-control dis_in_percentage text-right" min="0" value="0">
            </td>
            <td class="amount_col">
                <input type="number" name="amount[]" class="form-control amount text-right" value="0">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm fa fa-trash delete_row"></button>
            </td>
        </tr>
        `;
                $("#row").append(row);
                $(".select2").select2();
            }
        });

        // AJAX form submission
        $("#formData").submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('admin.medicine-invoices.store') }}",
                method: "POST",
                data: formData,
                success: function(response) {
                    alert('Invoice saved successfully!');
                    window.location.reload();
                },
                error: function(response) {
                    let errors = response.responseJSON.errors;
                    $('.form-control').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    $.each(errors, function(key, value) {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                    });
                }
            });
        });


        $("body").on("click", ".delete_row", function() {
            $(this).parents("tr").remove();
            calculateTotalAmount();
        });

        $("body").on("change keyup blur", ".quantity, .purchaseRate, .dis_in_rs, .dis_in_percentage", function() {
            let $row = $(this).closest("tr");
            let qty = +$row.find(".quantity").val() || 0;
            let rate = +$row.find(".purchaseRate").val() || 0;
            let disInRs = +$row.find(".dis_in_rs").val() || 0;
            let disInPercentage = +$row.find(".dis_in_percentage").val() || 0;

            let amount = qty * rate;
            let discountAmount = (amount * disInPercentage / 100);
            let finalAmount = amount - discountAmount;

            $row.find(".dis_in_rs").val(discountAmount.toFixed(2));
            $row.find(".amount").val(finalAmount.toFixed(2));
            calculateTotalAmount();
        });

        function calculateTotalAmount() {
            let totalDiscount = 0;
            let grandTotal = 0;
            $(".amount").each(function() {
                grandTotal += +$(this).val() || 0;
            });
            $(".dis_in_rs").each(function() {
                totalDiscount += +$(this).val() || 0;
            });
            $(".dis_in_percentage").each(function() {
                totalDiscount += (+$(this).closest("tr").find(".quantity").val() * +$(this).closest("tr").find(
                    ".purchaseRate").val()) * (+$(this).val() / 100);
            });
            $("input[name='total_discount']").val(totalDiscount.toFixed(2));
            $("input[name='grand_total']").val(grandTotal.toFixed(2));
        }
    </script>
@endsection
