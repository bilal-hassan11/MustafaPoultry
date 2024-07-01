@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Purchase Murghi</h4>
                </div>
                <form id="formData">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <input type="hidden" name="type" class="form-control text-right" value="Purchase">
                                <input type="hidden" name="editMode" class="form-control text-right" value="1">
                                <label for="invoice_no" class="required">Invoice No</label>
                                <input type="text" name="invoice_no" class="form-control"
                                    value="{{ old('invoice_no', $MurghiInvoice[0]->invoice_no) }}" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="date" class="required">Date</label>
                                <input type="date" name="date" class="form-control"
                                    value="{{ $MurghiInvoice[0]->date }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ref_no" class="required">Reference No</label>
                                <input type="text" name="ref_no" class="form-control" placeholder="Reference No"
                                    value="{{ $MurghiInvoice[0]->ref_no ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="description" class="required">Description</label>
                                <input type="text" name="description" class="form-control" placeholder="Description"
                                    value="{{ $MurghiInvoice[0]->description ?? '' }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account" class="required">Account</label>
                                <select class="form-control select2" name="account" id="account_id">
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            {{ $account->id == $MurghiInvoice[0]->account_id ? 'selected' : '' }}>
                                            {{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="width: 100%; overflow-x: auto">
                        <table class="table table-bordered text-center" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Item</th>
                                    <th style="width: 10%;">Weight</th>
                                    <th style="width: 10%;">Weight Detection</th>
                                    <th style="width: 10%;">Final Weight</th>
                                    <th style="width: 12%;">Rate</th>
                                    <th style="width: auto;">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="row">
                                <!-- Rows will be dynamically added here -->
                            </tbody>
                            <tfoot>
                                <tr style="text-align: right;">
                                    <td colspan="5">
                                        <label>Subtotal</label>
                                    </td>
                                    <td>
                                        <input type="text" name="subtotal" class="form-control text-right"
                                            style="text-align: right;" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-info fa fa-plus add-row"
                                            title="Add Row"></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="text-align: right;">
                                        Discount
                                    </td>
                                    <td>
                                        <input type="text" name="total_discount" class="form-control text-right"
                                            style="text-align: right;" readonly>
                                    </td>
                                </tr>
                                <tr style="text-align: right;">
                                    <td colspan="5">
                                        <label>Net Amount</label>
                                    </td>
                                    <td>
                                        <input type="text" name="net_bill" class="form-control text-right"
                                            style="text-align: right;" readonly>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <button type="submit" id="saveButton" class="btn btn-primary mt-2">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script type="text/javascript">
        let productDetailsArray = {!! json_encode($products->keyBy('id')->toArray()) !!};
        let MurghiInvoiceItems = {!! json_encode($MurghiInvoice) !!};

        $(document).ready(function() {
            $('select.product_val').select2({
                width: '100%',
            });

            populateData(MurghiInvoiceItems);

            $(".add-row").click(addRow);

            function addRow(item = {}) {
                let row = `
                <tr class="rows">
                    <td class="product_col">
                        <select class="form-control product product_val" name="item_id[]" required>
                            <option value="">Select Items</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->last_purchase_price ? $product->last_purchase_price : 1 }}">
                                    {{ $product->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                               <td class="weight_col">
                        <input type="number" name="weight[]" class="form-control weight text-right" min="1" value="${item.weight || 1}" step="any" style="text-align: right;" required>
                    </td>
                    <td class="weight_detection_col">
                        <input type="number" name="weight_detection[]" class="form-control weight_detection text-right" min="1" value="${item.weight_detection || 1}" step="any" style="text-align: right;" required>
                    </td>
                    <td class="quantity_col">
                        <input type="number" name="quantity[]" class="form-control quantity text-right" min="1" value="${item.quantity || 1}" step="any" style="text-align: right;" readonly required>
                    </td>
                    <td class="purchase_rate_col">
                        <input type="number" name="purchase_price[]" class="form-control purchaseRate text-right" value="${item.purchase_price || 1}" step="any" style="text-align: right;" required>
                        <input type="hidden" name="sale_price[]" class="form-control saleRate text-right" value="0" step="any" style="text-align: right;" required>
                    </td>
                    <input type="hidden" name="amount[]" class="form-control amount text-right" value="${item.amount || 0}" step="any" style="text-align: right;">
                    <input type="hidden" name="expiry_date[]" class="form-control text-right">
                    <input type="hidden" name="discount_in_rs[]" class="form-control dis_in_rs text-right" value="0" step="any" style="text-align: right;">
                    <input type="hidden" name="discount_in_percent[]" class="form-control dis_in_percentage text-right" min="0" max="100" value="0" step="any" style="text-align: right;">
                
                    <td class="net_amount_col">
                        <input type="text" name="net_amount[]" class="form-control net_amount text-right" value="${item.net_amount || 0}" step="any" style="text-align: right;" readonly required>
                    </td>
                    <td>
                        <button type="button" class="btn-sm btn-danger fa fa-trash delete_row" title="Remove Row"></button>
                    </td>
                </tr>
                `;
                $("#row").append(row);
                $('select.product_val').last().select2({
                    width: '100%',
                }).val(item.item_id).trigger('change');

                $(".product_val").last().change(function() {
                    updatePurchasePrice($(this));
                });

                $(".dis_in_percentage").last().on('input', function() {
                    let $row = $(this).closest('tr');
                    updateDiscountPercentage($row);
                });

                $(".dis_in_rs").last().on('input', function() {
                    let $row = $(this).closest('tr');
                    updateDiscountRs($row);
                });

                Calculation();
            }

            function updatePurchasePrice($selectElement) {
                let purchasePrice = $selectElement.find('option:selected').data('price');
                $selectElement.closest('tr').find('.purchaseRate').val(purchasePrice);
                Calculation();
            }

            function populateData(items) {
                items.forEach(item => {
                    addRow(item);
                });
            }

            $("#formData").submit(function(e) {
                e.preventDefault();

                if ($("#row").children().length === 0) {
                    toastr.warning('Please add at least one item.');
                    return;
                }

                let formData = $(this).serialize();
                $("#saveButton").attr("disabled", true);

                $.ajax({
                    url: "{{ route('admin.murghi-invoices.store') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Invoice saved successfully!',
                        }).then(() => {
                            setTimeout(function() {
                                window.location.reload();
                            }, 500);
                        });
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

            $("body").on("click", ".delete_row", function() {
                $(this).parents("tr").remove();
                Calculation();
            });

            $("body").on("input keyup blur", ".product_val, .quantity, .purchaseRate, .weight, .weight_detection",
                function() {
                    Calculation();
                });

            function Calculation() {
                let subtotal = 0;
                let totalDiscount = 0;
                let netbill = 0;

                $("tr.rows").each(function() {
                    let $row = $(this);
                    let weight = parseFloat($row.find(".weight").val()) || 0;
                    let weight_detection = parseFloat($row.find(".weight_detection").val()) || 0;
                    let qty = weight - weight_detection;
                    let rate = parseFloat($row.find(".purchaseRate").val()) || 0;
                    let amount = qty * rate;

                    let discountInRs = parseFloat($row.find(".dis_in_rs").val()) || 0;
                    // let discountInPercentage = parseFloat($row.find(".dis_in_percentage").val()) || 0;

                    // let discountAmount = (amount * discountInPercentage) / 100 + discountInRs;
                    let finalAmount = amount - discountInRs;

                    $row.find(".amount").val(amount.toFixed(2));
                    $row.find(".net_amount").val(finalAmount.toFixed(2));
                    $row.find(".quantity").val(qty.toFixed(2));

                    subtotal += amount;
                    totalDiscount += discountInRs;
                    netbill += finalAmount;
                });

                $("input[name='subtotal']").val(subtotal.toFixed(2));
                $("input[name='total_discount']").val(totalDiscount.toFixed(2));
                $("input[name='net_bill']").val(netbill.toFixed(2));
            }

            function updateDiscountPercentage($row) {
                let amount = parseFloat($row.find(".amount").val()) || 0;
                let discountInPercentage = parseFloat($row.find(".dis_in_percentage").val()) || 0;
                let discountInRs = (amount * discountInPercentage) / 100;

                $row.find(".dis_in_rs").val(discountInRs.toFixed(2));
                Calculation();
            }

            function updateDiscountRs($row) {
                let amount = parseFloat($row.find(".amount").val()) || 0;
                let discountInRs = parseFloat($row.find(".dis_in_rs").val()) || 0;
                let discountInPercentage = (discountInRs / amount) * 100;

                $row.find(".dis_in_percentage").val(discountInPercentage.toFixed(2));
                Calculation();
            }
        });
    </script>
@endsection
