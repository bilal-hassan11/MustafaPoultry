@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="card">
                <div class="card-header">
                    <h4>Sale Medicine</h4>
                </div>
                <form id="formData">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <input type="hidden" name="type" class="form-control text-right" value="Sale">
                                <label for="invoice_no" class="required">Invoice No</label>
                                <input type="text" name="invoice_no" class="form-control" value="{{ $invoice_no }}"
                                    readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="date" class="required">Date</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ref_no" class="required">Reference No</label>
                                <input type="text" name="ref_no" class="form-control" placeholder="Reference No">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="description" class="required">Description</label>
                                <input type="text" name="description" class="form-control" placeholder="Description">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account" class="required">Account</label>
                                <select class="form-control select2" name="account" id="account_id">
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
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
                                    <th style="width: 10%;">Quantity</th>
                                    <th style="width: 12%;">Rate</th>
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
                                        <input type="text" name="subtotal" class="form-control text-right" value="0"
                                            style="text-align: right;" readonly>
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
                                        <input type="text" name="net_bill" class="form-control text-right" value="0"
                                            style="text-align: right;" readonly>
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
@endsection
@section('page-scripts')
    <script type="text/javascript">
        let productDetailsArray = {!! json_encode($products->keyBy('id')->toArray()) !!};
        $(document).ready(function() {

            $('select.product_val').select2({
                width: '100%',
            });

            addRow();

            $(".add-row").click(addRow);

            function addRow() {
                let row = `
                <tr class="rows">
                    <td class="product_col">
                        @if ($products)
                        <select class="form-control product product_val" name="item_id[]" id="products" required>
                            <option value="">Select Items</option>
                            @foreach ($products as $product)
                                @php
                                    $latestInvoice = $product->latestMedicineInvoice;
                                    $salePrice = $latestInvoice ? $latestInvoice->sale_price : 0;
                                    $qty = $product->quantity;
                                    $purchasePrice = $qty != 0 ? $product->rate / $qty : 0;
                                @endphp    
                                <option value="{{ $product->item_id }}" data-price="{{ $salePrice }}" data-purchase_price="{{ $purchasePrice }}" data-qty="{{ $qty }}">
                                    {{ $product->item->name . ' - ' . $product->expiry_date ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @endif
                    </td>
                    <td class="quantity_col">
                        <input type="number" name="quantity[]" class="form-control quantity text-right" min="1" value="1" step="any" style="text-align: right;" required>
                    </td>
                    <input type="hidden" name="purchase_price[]" class="form-control purchaseRate text-right" value="1"  step="any" style="text-align: right;">
                                   <td class="sale_rate_col">
                        <input type="number" name="sale_price[]" class="form-control saleRate text-right" value="1"  step="any" style="text-align: right;" required>
                    </td>
                    <td class="expiry_date">
                        <input type="text" name="expiry_date[]" class="form-control text-right" readonly>
                    </td>
                    <input type="hidden" name="amount[]" class="form-control amount text-right" value="0" step="any" style="text-align: right;">
                    <td class="dis_in_rs_col">
                        <input type="number" name="discount_in_rs[]" class="form-control dis_in_rs text-right" value="0" step="any" style="text-align: right;">
                    </td>
                    <td class="dis_in_percentage_col">
                        <input type="number" name="discount_in_percent[]" class="form-control dis_in_percentage text-right" min="0" max="100" value="0" step="any" style="text-align: right;">
                    </td>
                    <td class="net_amount_col">
                        <input type="text" name="net_amount[]" class="form-control net_amount text-right" value="0" step="any" style="text-align: right;" readonly required>
                    </td>
                    <td>
                        <button type="button" class="btn-sm btn-danger fa fa-trash delete_row" title="Remove Row"></button>
                    </td>
                </tr>
                `;
                $("#row").append(row);
                $('select.product_val').select2({
                    width: '100%',
                });

            }

            $(".product_val").last().change(function() {
                updatePriceQty($(this));

            });

            $(".dis_in_rs").last().on('input', function() {
                Calculation(true);
            });

            function updatePriceQty($selectElement) {
                let salePrice = $selectElement.find('option:selected').data('price');
                let purchasePrice = $selectElement.find('option:selected').data('purchase_price');
                $selectElement.closest('tr').find('.saleRate').val(salePrice);
                $selectElement.closest('tr').find('.purchaseRate').val(purchasePrice);
                let qty = $selectElement.find('option:selected').data('qty');
                $selectElement.closest('tr').find('.quantity').attr('max', qty);
                $selectElement.closest('tr').find('.saleRate').attr('min', purchasePrice);
                Calculation();
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
                    url: "{{ route('admin.medicine-invoices.store-sale') }}",
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

            // Delete row
            $("body").on("click", ".delete_row", function() {
                $(this).parents("tr").remove();
                Calculation();
            });

            $("body").on("input keyup blur", ".product_val, .quantity, .saleRate, .dis_in_percentage",
                function() {
                    Calculation();
                });

            function Calculation(isManualUpdate = false) {
                let subtotal = 0;
                let totalDiscount = 0;
                let netbill = 0;

                $("tr.rows").each(function() {
                    let $row = $(this);
                    let qty = parseFloat($row.find(".quantity").val()) || 0;
                    let rate = parseFloat($row.find(".saleRate").val()) || 0;
                    let amount = qty * rate;
                    let disInPercentage = parseFloat($row.find(".dis_in_percentage").val()) || 0;

                    if (!isManualUpdate) {
                        let discountAmount = amount * disInPercentage / 100;
                        $row.find(".dis_in_rs").val(discountAmount.toFixed(2));
                    } else {
                        let discountAmount = parseFloat($row.find(".dis_in_rs").val()) || 0;
                        let discountPercentage = (discountAmount / amount) * 100;
                        $row.find(".dis_in_percentage").val(discountPercentage.toFixed(2));
                    }

                    let finalAmount = amount - parseFloat($row.find(".dis_in_rs").val()) || 0;
                    $row.find(".amount").val(amount.toFixed(2));
                    $row.find(".net_amount").val(finalAmount.toFixed(2));

                    subtotal += amount;
                    totalDiscount += parseFloat($row.find(".dis_in_rs").val()) || 0;
                    netbill += finalAmount;
                });

                $("input[name='subtotal']").val(subtotal.toFixed(2));
                $("input[name='total_discount']").val(totalDiscount.toFixed(2));
                $("input[name='net_bill']").val(netbill.toFixed(2));
            }
        });
    </script>
@endsection
