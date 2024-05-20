@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h4>Purchase Murghi</h4>
                    </div>
                    <form id="formData">
                        @csrf
                        <div class="card-body">
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
                                    <label for="ref_no" class="required">Reference No</label>
                                    <input type="text" name="ref_no" class="form-control text-right"
                                        placeholder="Reference No">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_id">Account</label>
                                    <select class="form-control select2" name="account" id="account_id11">
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
                            <table class="table responsive table-bordered text-center add-stock-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Item</th>
                                        <th style="width: auto;">Quantity</th>
                                        <th style="width: auto;">Rate</th>
                                        <th style="width: auto;">Expiry</th>
                                        <th style="width: auto;">Dis In (Rs)</th>
                                        <th style="width: auto;">Dis In (%)</th>
                                        <th style="width: auto;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="row">
                                </tbody>
                                <tfoot>
                                    <tr style="text-align: right;">
                                        <td colspan="6">
                                            <label>Subtotal</label>
                                        </td>
                                        <td>
                                            <input type="text" name="subtotal" class="form-control text-right"
                                                value="0" style="text-align: right;" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn-sm btn-info fa fa-plus add-row"
                                                title="Add Row"></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="text-align: right;">
                                            Discount
                                        </td>
                                        <td>
                                            <input type="text" name="total_discount" class="form-control text-right"
                                                value="0" style="text-align: right;" readonly>
                                        </td>
                                    </tr>
                                    <tr style="text-align: right;">
                                        <td colspan="6">
                                            <label>Net Amount</label>
                                        </td>
                                        <td>
                                            <input type="text" name="net_bill" class="form-control text-right"
                                                value="0" style="text-align: right;" readonly>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <button type="submit" id="saveButton" class="btn btn-primary mt-2">Save</button>
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
            // Function to add a new row
            function addRow() {
                let row = `
            <tr class="rows">
                <td class="product_col">
                    @if ($products)
                    <select class="form-control product product_val select2" name="item_id[]" id="products" required>
                        <option value="">Select Items</option>
                        @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name ?? '' }}</option>
                        @endforeach
                    </select>
                    @endif
                </td>
                <td class="quantity_col">
                    <input type="number" name="quantity[]" class="form-control quantity text-right" min="1" value="1" style="text-align: right;" required>
                </td>
                <td class="purchase_rate_col">
                    <input type="number" name="purchase_price[]" class="form-control purchaseRate text-right" value="1" style="text-align: right;" required>
                </td>
                <td class="expiry_date">
                    <input type="date" name="expiry_date[]" class="form-control text-right">
                </td>
                <input type="hidden" name="amount[]" class="form-control amount text-right" value="0" style="text-align: right;">
                <td class="dis_in_rs_col">
                    <input type="number" name="discount_in_rs[]" class="form-control dis_in_rs text-right" value="0" style="text-align: right;">
                </td>
                <td class="dis_in_percentage_col">
                    <input type="number" name="discount_in_percent[]" class="form-control dis_in_percentage text-right" min="0" value="0" style="text-align: right;">
                </td>
                <td class="net_amount_col">
                    <input type="text" name="net_amount[]" class="form-control net_amount text-right" value="0" style="text-align: right;" readonly required>
                </td>
                <td>
                    <button type="button" class="btn-sm btn-danger fa fa-trash delete_row" title="Remove Row"></button>
                </td>
            </tr>
        `;
                $("#row").append(row);
                $("select.select2").select2();
            }

            // Initial row addition
            addRow();
            $(".add-row").click(addRow);

            // Submit form with validation
            $("#formData").submit(function(e) {
                e.preventDefault();

                if ($("#row").children().length === 0) {
                    toastr.warning('Please add at least one item.');
                    return;
                }

                let formData = $(this).serialize();
                $("#saveButton").attr("disabled", true);

                $.ajax({
                    url: "{{ route('admin.medicine-invoices.store') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        toastr.success('Invoice saved successfully!');
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    },
                    error: function(response) {
                        let errors = response.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });

                        $("#saveButton").attr("disabled", false);
                    }
                });
            });

            // Delete row
            $("body").on("click", ".delete_row", function() {
                $(this).parents("tr").remove();
                calculateTotalAmount();
            });

            // Calculate amount, discount and net amount on input change
            $("body").on("input keyup blur", ".product_val, .quantity, .purchaseRate, .dis_in_percentage",
                function() {
                    let $row = $(this).closest("tr");
                    let qty = parseFloat($row.find(".quantity").val()) || 0;
                    let rate = parseFloat($row.find(".purchaseRate").val()) || 0;
                    let disInPercentage = parseFloat($row.find(".dis_in_percentage").val()) || 0;

                    let amount = qty * rate;
                    let discountAmount = amount * disInPercentage / 100;
                    let finalAmount = amount - discountAmount;

                    $row.find(".amount").val(amount.toFixed(2));
                    $row.find(".dis_in_rs").val(discountAmount.toFixed(2));
                    $row.find(".net_amount").val(finalAmount.toFixed(2));
                    calculateTotalAmount();
                });

            function calculateTotalAmount() {
                let totalDiscount = 0;
                let subtotal = 0;
                let netbill = 0;

                $(".amount").each(function() {
                    subtotal += parseFloat($(this).val()) || 0;
                });
                $(".dis_in_rs").each(function() {
                    totalDiscount += parseFloat($(this).val()) || 0;
                });
                $(".net_amount").each(function() {
                    netbill += parseFloat($(this).val()) || 0;
                });

                $("input[name='subtotal']").val(subtotal.toFixed(2));
                $("input[name='total_discount']").val(totalDiscount.toFixed(2));
                $("input[name='net_bill']").val(netbill.toFixed(2));
            }
        });
    </script>
@endsection
