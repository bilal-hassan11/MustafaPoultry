@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Create Purchase Feed Return</h4>
                    <a href="{{ route('admin.feed-invoices.purchase_return.index') }}" class="btn btn-secondary btn-sm">
                        <i class="ri-arrow-left-line"></i> Back
                    </a>
                </div>

                <form id="formData">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <input type="hidden" name="type" value="Purchase Return">
                                <label class="required">Invoice No</label>
                                <input type="text" name="invoice_no" class="form-control"
                                       value="{{ $invoice_no }}" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="required">Date</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Reference No</label>
                                <input type="text" name="ref_no" class="form-control" placeholder="Reference No">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Description</label>
                                <input type="text" name="description" class="form-control" placeholder="Description">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="required">Account (Supplier)</label>
                                <select class="form-control select2" name="account" required>
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" style="overflow-x: auto;">
                        <table class="table table-bordered text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:30%">Item (Available Stock)</th>
                                    <th style="width:10%">Quantity</th>
                                    <th style="width:12%">Purchase Rate</th>
                                    <th>Expiry Date</th>
                                    <th>Dis (Rs)</th>
                                    <th>Dis (%)</th>
                                    <th>Net Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="row"></tbody>
                            <tfoot>
                                <tr style="text-align:right;">
                                    <td colspan="6"><label>Subtotal</label></td>
                                    <td>
                                        <input type="text" name="subtotal" class="form-control text-right"
                                               value="0" style="text-align:right;" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-info fa fa-plus add-row"
                                                title="Add Row"></button>
                                    </td>
                                </tr>
                                <tr style="text-align:right;">
                                    <td colspan="6">Discount</td>
                                    <td>
                                        <input type="text" name="total_discount" class="form-control text-right"
                                               value="0" style="text-align:right;" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr style="text-align:right;">
                                    <td colspan="6"><label>Net Amount</label></td>
                                    <td>
                                        <input type="text" name="net_bill" class="form-control text-right"
                                               value="0" style="text-align:right;" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>

                        <button type="submit" id="saveButton" class="btn btn-primary mt-2">Save Return</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
<script>
    // Stock data: id = stock item index, item_id = real items.id, quantity = available stock
    let stockData = {!! json_encode($products->values()->toArray()) !!};

    $(document).ready(function () {

        $('select.product_val').select2({ width: '100%' });
        addRow();
        $(".add-row").click(addRow);

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
                </td>
                <td>
                    <input type="number" name="quantity[]" class="form-control quantity"
                           min="0.01" value="${item.quantity ? Math.abs(item.quantity) : 1}" step="any" required>
                    <small class="text-muted stock-label"></small>
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
                           value="${item.net_amount || 0}" style="text-align:right;" readonly>
                </td>
                <td>
                    <button type="button" class="btn-sm btn-danger fa fa-trash delete_row"></button>
                </td>
            </tr>`;

            $('#row').append(row);

            let $lastSelect = $('select.product_val').last();
            $lastSelect.select2({ width: '100%' });
            if (item.item_id) {
                $lastSelect.val(item.item_id).trigger('change');
            }
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

        // Submit
        $('#formData').submit(function (e) {
            e.preventDefault();

            if ($('#row').children().length === 0) {
                toastr.warning('Please add at least one item.');
                return;
            }

            // Client-side stock validation
            let valid = true;
            $('tr.rows').each(function () {
                let $row  = $(this);
                let qty   = parseFloat($row.find('.quantity').val()) || 0;
                let max   = parseFloat($row.find('.quantity').attr('max')) || Infinity;
                if (qty > max) {
                    toastr.error('Quantity exceeds available stock (' + max + ') for a selected item.');
                    valid = false;
                    return false;
                }
            });
            if (!valid) return;

            $('#saveButton').attr('disabled', true);

            $.ajax({
                url: "{{ route('admin.feed-invoices.purchase_return.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    Swal.fire({ icon: 'success', title: 'Success', text: 'Purchase Feed Return saved!' })
                        .then(() => window.location.href = "{{ route('admin.feed-invoices.purchase_return.index') }}");
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                    if (errors) {
                        $.each(errors, function (k, v) { toastr.error(v[0]); });
                    } else {
                        toastr.error(xhr.responseJSON ? xhr.responseJSON.error : 'Server error.');
                    }
                    $('#saveButton').attr('disabled', false);
                }
            });
        });

        // Delete row
        $('body').on('click', '.delete_row', function () {
            $(this).closest('tr').remove();
            Calculation();
        });

        // Recalculate on input changes
        $('body').on('input keyup blur', '.quantity, .purchaseRate, .dis_in_percentage', function () {
            Calculation();
        });

        function Calculation(manualDiscount) {
            let subtotal = 0, totalDiscount = 0, netbill = 0;

            $('tr.rows').each(function () {
                let $row       = $(this);
                let qty        = parseFloat($row.find('.quantity').val())   || 0;
                let rate       = parseFloat($row.find('.purchaseRate').val()) || 0;
                let amount     = qty * rate;
                let disPct     = parseFloat($row.find('.dis_in_percentage').val()) || 0;

                if (!manualDiscount) {
                    let disAmt = amount * disPct / 100;
                    $row.find('.dis_in_rs').val(disAmt.toFixed(2));
                } else {
                    let disAmt = parseFloat($row.find('.dis_in_rs').val()) || 0;
                    $row.find('.dis_in_percentage').val(amount > 0 ? (disAmt / amount * 100).toFixed(2) : 0);
                }

                let dis      = parseFloat($row.find('.dis_in_rs').val()) || 0;
                let net      = amount - dis;
                $row.find('.amount').val(amount.toFixed(2));
                $row.find('.net_amount').val(net.toFixed(2));

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
