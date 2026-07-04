@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">
                        Edit Purchase Medicine Return — Invoice #{{ $medicineInvoice[0]->invoice_no }}
                    </h4>
                    <a href="{{ route('admin.medicine-invoices.purchase_return.index') }}"
                       class="btn btn-secondary btn-sm">
                        <i class="ri-arrow-left-line"></i> Back
                    </a>
                </div>

                <form id="formData">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <input type="hidden" name="type"     value="Purchase Return">
                                <input type="hidden" name="editMode" value="1">
                                <label class="required">Invoice No</label>
                                <input type="text" name="invoice_no" class="form-control"
                                       value="{{ $medicineInvoice[0]->invoice_no }}" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="required">Date</label>
                                <input type="date" name="date" class="form-control"
                                       value="{{ $medicineInvoice[0]->date }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Reference No</label>
                                <input type="text" name="ref_no" class="form-control"
                                       value="{{ $medicineInvoice[0]->ref_no ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Description</label>
                                <input type="text" name="description" class="form-control"
                                       value="{{ $medicineInvoice[0]->description ?? '' }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="required">Account (Supplier)</label>
                                <select class="form-control select2" name="account" required>
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            {{ $account->id == $medicineInvoice[0]->account_id ? 'selected' : '' }}>
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Transport Name</label>
                                <input type="text" name="transport_name" class="form-control"
                                       value="{{ $medicineInvoice[0]->transport_name ?? '' }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>Vehicle No</label>
                                <input type="text" name="vehicle_no" class="form-control"
                                       value="{{ $medicineInvoice[0]->vehicle_no ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Driver Name</label>
                                <input type="text" name="driver_name" class="form-control"
                                       value="{{ $medicineInvoice[0]->driver_name ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Contact No</label>
                                <input type="text" name="contact_no" class="form-control"
                                       value="{{ $medicineInvoice[0]->contact_no ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-4">
                                <label>Builty No</label>
                                <input type="text" name="builty_no" class="form-control"
                                       value="{{ $medicineInvoice[0]->builty_no ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="card-body" style="width: 100%; overflow-x: auto">
                        <table class="table table-bordered text-center" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Item (Available Stock)</th>
                                    <th style="width: 10%;">Quantity</th>
                                    <th style="width: 12%;">Purchase Rate</th>
                                    <th style="width: auto;">Expiry Date</th>
                                    <th style="width: auto;">Dis In (Rs)</th>
                                    <th style="width: auto;">Dis In (%)</th>
                                    <th style="width: auto;">Net Amount</th>
                                </tr>
                            </thead>
                            <tbody id="row"></tbody>
                            <tfoot>
                                <tr style="text-align: right;">
                                    <td colspan="6"><label>Subtotal</label></td>
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
                                    <td colspan="6" style="text-align: right;">Discount</td>
                                    <td>
                                        <input type="text" name="total_discount" class="form-control text-right"
                                               style="text-align: right;" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr style="text-align: right;">
                                    <td colspan="6"><label>Net Amount</label></td>
                                    <td>
                                        <input type="text" name="net_bill" class="form-control text-right"
                                               style="text-align: right;" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        <button type="submit" id="saveButton" class="btn btn-primary mt-2">Update Return</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script type="text/javascript">
        // stockData from getStockInfo() — stdClass cast to array — item_id = real items.id
        let stockData       = {!! json_encode(collect($products)->map(function($p){ return (array)$p; })->values()->toArray()) !!};
        let existingItems   = {!! json_encode($medicineInvoice->toArray()) !!};

        $(document).ready(function () {

            $('select.product_val').select2({ width: '100%' });

            // Populate saved rows
            existingItems.forEach(function (item) { addRow(item); });
            $(".add-row").click(function () { addRow(); });

            function addRow(item) {
                item = item || {};

                let options = stockData.map(function (p) {
                    let label = p.name + (p.expiry_date ? ' – ' + p.expiry_date : '') + ' [Stock: ' + p.quantity + ']';
                    return `<option value="${p.item_id}"
                                data-qty="${p.quantity}"
                                data-price="${p.last_purchase_price || 0}"
                                data-sale_price="${p.last_sale_price || 0}"
                                data-expiry="${p.expiry_date || ''}"
                                ${item.item_id == p.item_id ? 'selected' : ''}>
                                ${label}
                             </option>`;
                }).join('');

                let row = `
                <tr class="rows">
                    <td>
                        <select class="form-control product_val" name="item_id[]" required>
                            <option value="">Select Item</option>
                            ${options}
                        </select>
                        <small class="text-muted stock-label"></small>
                    </td>
                    <td>
                        <input type="number" name="quantity[]" class="form-control quantity"
                               min="0.01" value="${item.quantity ? Math.abs(item.quantity) : 1}"
                               step="any" required>
                    </td>
                    <td>
                        <input type="number" name="purchase_price[]" class="form-control purchaseRate"
                               value="${item.purchase_price || 0}" step="any" required>
                        <input type="hidden" name="sale_price[]" class="saleRate"
                               value="${item.sale_price || 0}">
                    </td>
                    <td>
                        <input type="date" name="expiry_date[]" class="form-control expiry_date"
                               value="${item.expiry_date || ''}">
                    </td>
                    <input type="hidden" name="amount[]" class="amount" value="${item.amount || 0}">
                    <td>
                        <input type="number" name="discount_in_rs[]" class="form-control dis_in_rs"
                               value="${item.discount_in_rs || 0}" step="any">
                    </td>
                    <td>
                        <input type="number" name="discount_in_percent[]" class="form-control dis_in_percentage"
                               min="0" max="100" value="${item.discount_in_percent || 0}" step="any">
                    </td>
                    <td>
                        <input type="text" name="net_amount[]" class="form-control net_amount"
                               value="${item.net_amount || 0}" style="text-align: right;" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn-sm btn-danger fa fa-trash delete_row"
                                title="Remove Row"></button>
                    </td>
                </tr>`;

                $("#row").append(row);

                let $lastSelect = $('select.product_val').last();
                $lastSelect.select2({ width: '100%' });
                if (item.item_id) {
                    // change.select2 updates visual selection without firing our data handler
                    $lastSelect.val(item.item_id).trigger('change.select2');

                    // Restore stock-label display from saved data
                    let match = stockData.find(function(p) {
                        return String(p.item_id) === String(item.item_id);
                    });
                    if (match) {
                        $lastSelect.closest('td').find('.stock-label')
                                   .text('Available: ' + match.quantity);
                        $lastSelect.closest('tr').find('.quantity')
                                   .attr('max', match.quantity)
                                   .attr('title', 'Available stock: ' + match.quantity);
                    }
                }
                // Attach the live-update handler AFTER initial value is set
                $lastSelect.on('change', function () { updateProductDetails($(this)); });
                $(".dis_in_rs").last().on('input', function () { Calculation(true); });

                Calculation();
            }

            function updateProductDetails($sel) {
                let $opt   = $sel.find('option:selected');
                let qty    = $opt.data('qty');
                let price  = $opt.data('price');
                let expiry = $opt.data('expiry');
                let sale   = $opt.data('sale_price');
                let $row   = $sel.closest('tr');

                $row.find('.purchaseRate').val(price);
                $row.find('.saleRate').val(sale);
                $row.find('.expiry_date').val(expiry);
                $row.find('.quantity').attr('max', qty).attr('title', 'Available stock: ' + qty);
                $row.find('.stock-label').text('Available: ' + qty);
                Calculation();
            }

            $("#formData").submit(function (e) {
                e.preventDefault();
                if ($("#row").children().length === 0) {
                    toastr.warning('Please add at least one item.');
                    return;
                }
                $("#saveButton").attr("disabled", true);

                $.ajax({
                    url: "{{ route('admin.medicine-invoices.purchase_return.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function () {
                        Swal.fire({ icon: 'success', title: 'Updated',
                                    text: 'Purchase Medicine Return updated!' })
                            .then(() => {
                                window.location.href = "{{ route('admin.medicine-invoices.purchase_return.index') }}";
                            });
                    },
                    error: function (response) {
                        let errors = response.responseJSON ? response.responseJSON.errors : null;
                        if (errors) {
                            $.each(errors, function (key, value) { toastr.error(value[0]); });
                        } else {
                            toastr.error(response.responseJSON ? response.responseJSON.error : 'Server error.');
                        }
                        $("#saveButton").attr("disabled", false);
                    }
                });
            });

            $("body").on("click", ".delete_row", function () {
                $(this).parents("tr").remove();
                Calculation();
            });

            $("body").on("input keyup blur", ".quantity, .purchaseRate, .dis_in_percentage",
                function () { Calculation(); });

            function Calculation(manualDiscount) {
                let subtotal = 0, totalDiscount = 0, netbill = 0;

                $("tr.rows").each(function () {
                    let $row   = $(this);
                    let qty    = parseFloat($row.find(".quantity").val())     || 0;
                    let rate   = parseFloat($row.find(".purchaseRate").val()) || 0;
                    let amount = qty * rate;
                    let disPct = parseFloat($row.find(".dis_in_percentage").val()) || 0;

                    if (!manualDiscount) {
                        $row.find(".dis_in_rs").val((amount * disPct / 100).toFixed(2));
                    } else {
                        let dis = parseFloat($row.find(".dis_in_rs").val()) || 0;
                        $row.find(".dis_in_percentage").val(amount > 0 ? (dis / amount * 100).toFixed(2) : 0);
                    }

                    let dis = parseFloat($row.find(".dis_in_rs").val()) || 0;
                    let net = amount - dis;
                    $row.find(".amount").val(amount.toFixed(2));
                    $row.find(".net_amount").val(net.toFixed(2));

                    subtotal      += amount;
                    totalDiscount += dis;
                    netbill       += net;
                });

                $("input[name='subtotal']").val(subtotal.toFixed(2));
                $("input[name='total_discount']").val(totalDiscount.toFixed(2));
                $("input[name='net_bill']").val(netbill.toFixed(2));
            }
        });
    </script>
@endsection
