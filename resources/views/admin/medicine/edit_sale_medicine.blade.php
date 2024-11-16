@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Sale Medicine</h4>
                </div>
                <form id="formData">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <input type="hidden" name="type" class="form-control text-right" value="Sale">
                                <input type="hidden" name="editMode" class="form-control text-right" value="1">
                                <label for="invoice_no" class="required">Invoice No</label>
                                <input type="text" name="invoice_no" class="form-control"
                                    value="{{ old('invoice_no', $medicineInvoice[0]->invoice_no) ?? '' }}" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="date" class="required">Date</label>
                                <input type="date" name="date" class="form-control"
                                    value="{{ $medicineInvoice[0]->date ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ref_no" class="required">Reference No</label>
                                <input type="text" name="ref_no" class="form-control" placeholder="Reference No"
                                    value="{{ $medicineInvoice[0]->ref_no ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="description" class="required">Description</label>
                                <input type="text" name="description" class="form-control" placeholder="Description"
                                    value="{{ $medicineInvoice[0]->description ?? '' }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account" class="required">Account</label>
                                <select class="form-control select2" name="account" id="account_id">
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            {{ $account->id == $medicineInvoice[0]->account_id ? 'selected' : '' }}>
                                            {{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="transport_name">Transport Name</label>
                                <input type="text" name="transport_name" class="form-control"
                                    placeholder="Transport Name" value="{{ $medicineInvoice[0]->transport_name ?? '' }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="vehicle_no">Vehicle No</label>
                                <input type="text" name="vehicle_no" class="form-control" placeholder="Vehicle No"
                                    value="{{ $medicineInvoice[0]->vehicle_no ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="driver_name">Driver Name</label>
                                <input type="text" name="driver_name" class="form-control" placeholder="Driver Name"
                                    value="{{ $medicineInvoice[0]->driver_name ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="contact_no">Contact No</label>
                                <input type="text" name="contact_no" class="form-control" placeholder="Contact No"
                                    value="{{ $medicineInvoice[0]->contact_no ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-4">
                                <label for="builty_no">Builty No</label>
                                <input type="text" name="builty_no" class="form-control" placeholder="Builty No"
                                    value="{{ $medicineInvoice[0]->builty_no ?? '' }}">
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
@endsection
@section('page-scripts')
    <script type="text/javascript">
        let medicineInvoiceItems = {!! json_encode($medicineInvoice) !!};

        $(document).ready(function() {

            // Initial Select2 initialization for existing rows
            $('select.product_val').select2({
                width: '100%',
            });

            // Function to populate data (if needed)
            populateData(medicineInvoiceItems);

            // Event listener for adding a new row
            $(".add-row").click(addRow);

            // Function to add a new row
            function addRow(item = {}) {
                let row = `
        <tr class="rows">
            <td class="product_col">
                @if ($products)
                <select class="form-control product product_val" name="id[]" required>
                    <option value="">Select Items</option>
                    @foreach ($products as $product)
                        @php
                            $qty = $product->quantity;
                            $expiry_date = $product->expiry_date;
                        @endphp    
                        <option value="{{ $product->id }}" 
                                data-price="{{ $product->last_sale_price }}" 
                                data-purchase_price="{{ $product->average_price }}" 
                                data-qty="{{ $qty }}" 
                                data-expiry_date="{{ $expiry_date }}" 
                                data-item_id="{{ $product->item_id }}"
                                @if ($product->expiry_date) ${item.item_id == {{ $product->item_id }} && item.expiry_date == '{{ $product->expiry_date }}' ? 'selected' : ''}
                                @else
                                    ${item.item_id == '{{ $product->item_id }}' ? 'selected' : '' } @endif>
                            {{ $product->name . ($product->expiry_date ? ' - ' . $product->expiry_date : '') }}
                        </option>
                    @endforeach
                </select>
                @endif
                <input type="hidden" name="item_id[]" class="item_id" value="${item.item_id || ''}">
            </td>
            <td class="quantity_col">
                <input type="number" name="quantity[]" class="form-control quantity text-right" min="1" value="${Math.abs(item.quantity) || 1}" step="any" style="text-align: right;" required>
            </td>
            <input type="hidden" name="purchase_price[]" class="form-control purchaseRate text-right" value="${item.purchase_price || 1}" step="any" style="text-align: right;">
            <td class="sale_rate_col">
                <input type="number" name="sale_price[]" class="form-control saleRate text-right" value="${item.sale_price || 1}" step="any" style="text-align: right;" required>
            </td>
            <td class="expiry_date">
                <input type="text" name="expiry_date[]" class="form-control expiry_date text-right" readonly value="${item.expiry_date || ''}">
            </td>
            <input type="hidden" name="amount[]" class="form-control amount text-right" step="any" style="text-align: right;" value="${item.amount || 0}">
            <td class="dis_in_rs_col">
                <input type="number" name="discount_in_rs[]" class="form-control dis_in_rs text-right" value="${item.discount_in_rs || 0}" step="any" style="text-align: right;">
            </td>
            <td class="dis_in_percentage_col">
                <input type="number" name="discount_in_percent[]" class="form-control dis_in_percentage text-right" min="0" max="100" value="${item.discount_in_percent || 0}" step="any" style="text-align: right;">
            </td>
            <td class="net_amount_col">
                <input type="text" name="net_amount[]" class="form-control net_amount text-right" value="${item.net_amount || 0}" step="any" style="text-align: right;" readonly required>
            </td>
            <td>
                <button type="button" class="btn-sm btn-danger fa fa-trash delete_row" title="Remove Row"></button>
            </td>
        </tr>
        `;
                // Append the new row to the table
                $("#row").append(row);

                // Apply Select2 to only the newly added select element
                $("#row .product_val:last").select2({
                    width: '100%',
                });

                // Attach event handlers to the newly added row's elements
                $("#row .product_val:last").change(function() {
                    updatePriceQty($(this));
                });

                $("#row .dis_in_rs:last").on('input', function() {
                    Calculation(true);
                });
            }



            function populateData(items) {
                items.forEach(item => {
                    addRow(item);
                });
                Calculation();
            }


            // Update price, quantity, and expiry date based on the selected product
            function updatePriceQty($selectElement) {
                let salePrice = $selectElement.find('option:selected').data('price');
                let qty = $selectElement.find('option:selected').data('qty');
                let expirydate = $selectElement.find('option:selected').data('expiry_date');
                let purchasePrice = $selectElement.find('option:selected').data('purchase_price');
                let itemID = $selectElement.find('option:selected').data('item_id');
                $selectElement.closest('tr').find('.saleRate').val(salePrice);
                $selectElement.closest('tr').find('.purchaseRate').val(purchasePrice);
                $selectElement.closest('tr').find('.expiry_date').val(expirydate);
                $selectElement.closest('tr').find('.quantity').attr('max', qty);
                $selectElement.closest('tr').find('.quantity').attr('title', 'Available stock :' + qty);
                $selectElement.closest('tr').find('.saleRate').attr('title', 'Cost Price: ' +
                    purchasePrice);
                $selectElement.closest('tr').find('.item_id').val(itemID);
                Calculation();
            }

            // Form submission
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

            // Event handler for input changes
            $("body").on("input keyup blur", ".quantity, .saleRate, .dis_in_percentage", function() {
                Calculation();
            });

            // Calculation function
            function Calculation(isManualUpdate = false) {
                let subtotal = 0;
                let totalDiscount = 0;
                let netbill = 0;

                $("tr.rows").each(function() {
                    let $row = $(this);
                    let qty = parseFloat($row.find(".quantity").val()) || 0;
                    let rate = parseFloat($row.find(".saleRate").val()) || 0;
                    let amount = qty * rate;
                    let disInPercentage = parseFloat($row.find(".dis_in_percentage").val()) ||
                        0;

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
