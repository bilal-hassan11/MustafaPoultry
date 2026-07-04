@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Purchase Medicine</h4>
                </div>
                <form id="formData">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <input type="hidden" name="type"     value="Purchase">
                                <input type="hidden" name="editMode" value="1">
                                <label for="invoice_no" class="required">Invoice No</label>
                                <input type="text" name="invoice_no" class="form-control"
                                    value="{{ old('invoice_no', $medicineInvoice[0]->invoice_no) }}" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="date" class="required">Date</label>
                                <input type="date" name="date" class="form-control"
                                    value="{{ $medicineInvoice[0]->date }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ref_no">Reference No</label>
                                <input type="text" name="ref_no" class="form-control"
                                    value="{{ $medicineInvoice[0]->ref_no ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="description">Description</label>
                                <input type="text" name="description" class="form-control"
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
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="transport_name">Transport Name</label>
                                <input type="text" name="transport_name" class="form-control"
                                    value="{{ $medicineInvoice[0]->transport_name ?? '' }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="vehicle_no">Vehicle No</label>
                                <input type="text" name="vehicle_no" class="form-control"
                                    value="{{ $medicineInvoice[0]->vehicle_no ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="driver_name">Driver Name</label>
                                <input type="text" name="driver_name" class="form-control"
                                    value="{{ $medicineInvoice[0]->driver_name ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="contact_no">Contact No</label>
                                <input type="text" name="contact_no" class="form-control"
                                    value="{{ $medicineInvoice[0]->contact_no ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-4">
                                <label for="builty_no">Builty No</label>
                                <input type="text" name="builty_no" class="form-control"
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
                            <tbody id="row"></tbody>
                            <tfoot>
                                <tr style="text-align: right;">
                                    <td colspan="6"><label>Subtotal</label></td>
                                    <td>
                                        <input type="text" name="subtotal" class="form-control"
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
                                        <input type="text" name="total_discount" class="form-control"
                                               style="text-align: right;" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr style="text-align: right;">
                                    <td colspan="6"><label>Net Amount</label></td>
                                    <td>
                                        <input type="text" name="net_bill" class="form-control"
                                               style="text-align: right;" readonly>
                                    </td>
                                    <td></td>
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
<script>
    // products keyed by id — used for the "add blank row" dropdown
    let productsList     = {!! json_encode($products->values()->toArray()) !!};
    // saved invoice rows — contain the real saved values we must restore
    let savedItems       = {!! json_encode($medicineInvoice->toArray()) !!};

    $(document).ready(function () {

        // Populate saved rows first, then wire the add-row button
        savedItems.forEach(item => addRow(item, true));
        $(".add-row").click(function () { addRow({}, false); });

        /**
         * @param {Object}  item        – saved invoice row (or {} for a blank new row)
         * @param {boolean} isPopulate  – true = restoring saved data, skip price override
         */
        function addRow(item, isPopulate) {
            item       = item       || {};
            isPopulate = isPopulate !== undefined ? isPopulate : false;

            // Build <option> list from plain Items
            let opts = productsList.map(p =>
                `<option value="${p.id}"
                    data-price="${p.purchase_price || 0}"
                    data-sale_price="${p.sale_price || 0}">
                    ${p.name}
                 </option>`
            ).join('');

            let row = `
            <tr class="rows">
                <td>
                    <select class="form-control product_val" name="item_id[]" required>
                        <option value="">Select Item</option>
                        ${opts}
                    </select>
                </td>
                <td>
                    <input type="number" name="quantity[]"
                           class="form-control quantity text-right"
                           min="1" value="${item.quantity || 1}" step="any"
                           style="text-align:right;" required>
                </td>
                <td>
                    <input type="number" name="purchase_price[]"
                           class="form-control purchaseRate text-right"
                           value="${item.purchase_price || 0}" step="any"
                           style="text-align:right;" required>
                    <input type="hidden" name="sale_price[]"
                           class="saleRate" value="${item.sale_price || 0}">
                </td>
                <td>
                    <input type="date" name="expiry_date[]"
                           class="form-control expiry_date"
                           value="${item.expiry_date || ''}">
                </td>
                <input type="hidden" name="amount[]"
                       class="amount" value="${item.amount || 0}">
                <td>
                    <input type="number" name="discount_in_rs[]"
                           class="form-control dis_in_rs text-right"
                           value="${item.discount_in_rs || 0}" step="any"
                           style="text-align:right;">
                </td>
                <td>
                    <input type="number" name="discount_in_percent[]"
                           class="form-control dis_in_percentage text-right"
                           min="0" max="100" value="${item.discount_in_percent || 0}"
                           step="any" style="text-align:right;">
                </td>
                <td>
                    <input type="text" name="net_amount[]"
                           class="form-control net_amount text-right"
                           value="${item.net_amount || 0}" step="any"
                           style="text-align:right;" readonly required>
                </td>
                <td>
                    <button type="button"
                            class="btn-sm btn-danger fa fa-trash delete_row"
                            title="Remove Row"></button>
                </td>
            </tr>`;

            $('#row').append(row);

            let $sel = $('#row .product_val:last').select2({ width: '100%' });

            if (isPopulate && item.item_id) {
                // Restore saved selection — use trigger('change.select2') so Select2's
                // UI updates BUT our data-update handler does NOT fire, preserving
                // the saved purchase_price / expiry_date values.
                $sel.val(item.item_id).trigger('change.select2');
            }

            // Wire the change handler AFTER setting the initial value so it only
            // fires on genuine user-driven changes going forward.
            $sel.on('change', function () {
                // Only update price/expiry when the user picks a different item
                let $opt = $(this).find('option:selected');
                let $row = $(this).closest('tr');
                $row.find('.purchaseRate').val($opt.data('price'));
                $row.find('.saleRate').val($opt.data('sale_price'));
                Calculation();
            });

            $(".dis_in_rs").last().on('input', function () {
                let $r = $(this).closest('tr');
                let amt = parseFloat($r.find('.amount').val()) || 0;
                let dis = parseFloat($r.find('.dis_in_rs').val()) || 0;
                $r.find('.dis_in_percentage').val(amt > 0 ? (dis / amt * 100).toFixed(2) : 0);
                Calculation();
            });

            $(".dis_in_percentage").last().on('input', function () {
                let $r = $(this).closest('tr');
                let amt = parseFloat($r.find('.amount').val()) || 0;
                let pct = parseFloat($r.find('.dis_in_percentage').val()) || 0;
                $r.find('.dis_in_rs').val((amt * pct / 100).toFixed(2));
                Calculation();
            });

            Calculation();
        }

        // ── Form submit ──────────────────────────────────────────────────────
        $('#formData').submit(function (e) {
            e.preventDefault();
            if ($('#row').children().length === 0) {
                toastr.warning('Please add at least one item.');
                return;
            }
            $('#saveButton').attr('disabled', true);
            $.ajax({
                url: "{{ route('admin.medicine-invoices.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function () {
                    Swal.fire({ icon: 'success', title: 'Success', text: 'Invoice updated successfully!' })
                        .then(() => { setTimeout(() => window.location.reload(), 500); });
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                    if (errors) { $.each(errors, function (k, v) { toastr.error(v[0]); }); }
                    else { toastr.error(xhr.responseJSON ? xhr.responseJSON.error : 'Server error.'); }
                    $('#saveButton').attr('disabled', false);
                }
            });
        });

        // ── Row deletion ─────────────────────────────────────────────────────
        $('body').on('click', '.delete_row', function () {
            $(this).closest('tr').remove();
            Calculation();
        });

        // ── Live recalculation ───────────────────────────────────────────────
        $('body').on('input keyup blur', '.quantity, .purchaseRate', function () {
            Calculation();
        });

        // ── Totals ───────────────────────────────────────────────────────────
        function Calculation() {
            let subtotal = 0, totalDiscount = 0, netbill = 0;

            $('tr.rows').each(function () {
                let $row     = $(this);
                let qty      = parseFloat($row.find('.quantity').val())     || 0;
                let rate     = parseFloat($row.find('.purchaseRate').val()) || 0;
                let amount   = qty * rate;
                let disRs    = parseFloat($row.find('.dis_in_rs').val())    || 0;
                let final    = amount - disRs;

                $row.find('.amount').val(amount.toFixed(2));
                $row.find('.net_amount').val(final.toFixed(2));

                subtotal      += amount;
                totalDiscount += disRs;
                netbill       += final;
            });

            $("input[name='subtotal']").val(subtotal.toFixed(2));
            $("input[name='total_discount']").val(totalDiscount.toFixed(2));
            $("input[name='net_bill']").val(netbill.toFixed(2));
        }
    });
</script>
@endsection
