@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Sale Feed</h4>
                </div>
                <form id="formData">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <input type="hidden" name="type"     value="Sale">
                                <input type="hidden" name="editMode" value="1">
                                <label for="invoice_no" class="required">Invoice No</label>
                                <input type="text" name="invoice_no" class="form-control"
                                       value="{{ old('invoice_no', $FeedInvoice[0]->invoice_no) }}" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="date" class="required">Date</label>
                                <input type="date" name="date" class="form-control"
                                       value="{{ $FeedInvoice[0]->date }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ref_no">Reference No</label>
                                <input type="text" name="ref_no" class="form-control"
                                       placeholder="Reference No" value="{{ $FeedInvoice[0]->ref_no ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="description">Description</label>
                                <input type="text" name="description" class="form-control"
                                       placeholder="Description" value="{{ $FeedInvoice[0]->description ?? '' }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account" class="required">Account</label>
                                <select class="form-control select2" name="account" id="account_id">
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            {{ $account->id == $FeedInvoice[0]->account_id ? 'selected' : '' }}>
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" style="width: 100%; overflow-x: auto;">
                        <table class="table table-bordered text-center" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width:25%">Item</th>
                                    <th style="width:8%">Quantity</th>
                                    <th style="width:10%">Rate</th>
                                    <th style="width:8%">Expiry</th>
                                    <th style="width:10%">Dis (Rs)</th>
                                    <th style="width:10%">Dis (%)</th>
                                    <th style="width:10%">Commission (%)</th>
                                    <th style="width:10%">Net Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="row"></tbody>
                            <tfoot>
                                <tr style="text-align:right;">
                                    <td colspan="7"><label>Subtotal</label></td>
                                    <td>
                                        <input type="text" name="subtotal" class="form-control text-right"
                                               style="text-align:right;" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-info fa fa-plus add-row"
                                                title="Add Row"></button>
                                    </td>
                                </tr>
                                <tr style="text-align:right;">
                                    <td colspan="7">Discount</td>
                                    <td>
                                        <input type="text" name="total_discount" class="form-control text-right"
                                               style="text-align:right;" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr style="text-align:right;">
                                    <td colspan="7">Commission</td>
                                    <td>
                                        <input type="text" name="total_commission" class="form-control text-right"
                                               style="text-align:right;" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr style="text-align:right;">
                                    <td colspan="7"><label>Net Amount</label></td>
                                    <td>
                                        <input type="text" name="net_bill" class="form-control text-right"
                                               style="text-align:right;" readonly>
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
    // products = stock collection objects (item_id, name, quantity, sale_price, purchase_price, expiry_date)
    let stockData       = {!! json_encode(collect($products)->values()->toArray()) !!};
    let FeedInvoiceItems = {!! json_encode($FeedInvoice) !!};

    $(document).ready(function () {

        $('select.product_val').select2({ width: '100%' });

        // Populate existing rows first
        FeedInvoiceItems.forEach(function (item) { addRow(item); });

        $(".add-row").click(function () { addRow(); });

        function addRow(item) {
            item = item || {};

            let options = stockData.map(function (p) {
                let label = p.name + (p.expiry_date ? ' – ' + p.expiry_date : '');
                return `<option value="${p.id}"
                            data-price="${p.sale_price || p.last_sale_price || 0}"
                            data-purchase_price="${p.purchase_price || p.last_purchase_price || 0}"
                            data-qty="${p.quantity || 0}"
                            data-expiry_date="${p.expiry_date || ''}"
                            data-item_id="${p.item_id || p.id}"
                            ${item.item_id == (p.item_id || p.id) ? 'selected' : ''}>
                            ${label}
                         </option>`;
            }).join('');

            let row = `
            <tr class="rows">
                <td>
                    <select class="form-control product product_val" name="id[]" required>
                        <option value="">Select Items</option>
                        ${options}
                    </select>
                    <input type="hidden" name="item_id[]" class="item_id"
                           value="${item.item_id || ''}">
                </td>
                <td>
                    <input type="number" name="quantity[]" class="form-control quantity"
                           min="1" value="${item.quantity ? Math.abs(item.quantity) : 1}" step="any" required>
                </td>
                <td>
                    <input type="hidden" name="purchase_price[]" class="purchaseRate"
                           value="${item.purchase_price || 0}">
                    <input type="number" name="sale_price[]" class="form-control saleRate"
                           value="${item.sale_price || 0}" step="any" required>
                </td>
                <td>
                    <input type="text" name="expiry_date[]" class="form-control expiry_date"
                           value="${item.expiry_date || ''}" readonly>
                </td>
                <input type="hidden" name="amount[]" class="amount" value="${item.amount || 0}">
                <td>
                    <input type="number" name="discount_in_rs[]" class="form-control dis_in_rs"
                           value="${item.discount_in_rs || 0}" step="any">
                    <small class="text-muted dis_amount_label">Rs 0.00</small>
                </td>
                <td>
                    <input type="number" name="discount_in_percent[]" class="form-control dis_in_percentage"
                           min="0" max="100" value="${item.discount_in_percent || 0}" step="any">
                </td>
                <td>
                    <input type="number" name="commission_percent[]" class="form-control commission_percent"
                           min="0" max="100" value="${item.commission_percent || 0}" step="any">
                    <small class="text-muted commission_label">Rs 0.00</small>
                </td>
                <td>
                    <input type="text" name="net_amount[]" class="form-control net_amount"
                           value="${item.net_amount || 0}" readonly required>
                </td>
                <td>
                    <button type="button" class="btn-sm btn-danger fa fa-trash delete_row"
                            title="Remove Row"></button>
                </td>
            </tr>`;

            $('#row').append(row);

            let $lastSelect = $('select.product_val').last();
            $lastSelect.select2({ width: '100%' });

            // Pre-select the item if we have item_id, match by item_id data attr
            if (item.item_id) {
                // Find the option whose data-item_id equals item.item_id
                $lastSelect.find('option').each(function () {
                    if ($(this).data('item_id') == item.item_id) {
                        $lastSelect.val($(this).val()).trigger('change.select2');
                        return false;
                    }
                });
            }

            $lastSelect.on('change', function () { updatePriceQty($(this)); });
            $(".dis_in_rs").last().on('input', function () { Calculation(true); });

            Calculation();
        }

        function updatePriceQty($sel) {
            let $opt  = $sel.find('option:selected');
            let $row  = $sel.closest('tr');
            $row.find('.saleRate').val($opt.data('price'));
            $row.find('.purchaseRate').val($opt.data('purchase_price'));
            $row.find('.expiry_date').val($opt.data('expiry_date'));
            $row.find('.quantity').attr('max', $opt.data('qty'))
                                  .attr('title', 'Available stock: ' + $opt.data('qty'));
            $row.find('.item_id').val($opt.data('item_id'));
            Calculation();
        }

        // Submit
        $('#formData').submit(function (e) {
            e.preventDefault();
            if ($('#row').children().length === 0) {
                toastr.warning('Please add at least one item.');
                return;
            }
            $('#saveButton').attr('disabled', true);
            $.ajax({
                url: "{{ route('admin.feed-invoices.store') }}",
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

        $('body').on('click', '.delete_row', function () {
            $(this).closest('tr').remove();
            Calculation();
        });

        function debounce(fn, delay) {
            let t;
            return function () { clearTimeout(t); t = setTimeout(fn, delay); };
        }
        let dCalc = debounce(function () { Calculation(); }, 300);
        $('body').on('input',  '.quantity, .saleRate, .dis_in_percentage, .commission_percent', dCalc);
        $('body').on('blur',   '.quantity, .saleRate, .dis_in_percentage, .commission_percent',
            function () { Calculation(); });

        function Calculation(isManualUpdate) {
            let subtotal = 0, totalDiscount = 0, totalCommission = 0, netbill = 0;

            $('tr.rows').each(function () {
                let $row        = $(this);
                let qty         = parseFloat($row.find('.quantity').val())          || 0;
                let rate        = parseFloat($row.find('.saleRate').val())           || 0;
                let amount      = qty * rate;
                let disPct      = parseFloat($row.find('.dis_in_percentage').val())  || 0;

                if (!isManualUpdate) {
                    $row.find('.dis_in_rs').val((amount * disPct / 100).toFixed(2));
                } else {
                    let disAmt  = parseFloat($row.find('.dis_in_rs').val()) || 0;
                    $row.find('.dis_in_percentage')
                        .val(amount > 0 ? (disAmt / amount * 100).toFixed(2) : 0);
                }

                let disRs       = parseFloat($row.find('.dis_in_rs').val())          || 0;
                let afterDis    = amount - disRs;
                let commPct     = parseFloat($row.find('.commission_percent').val())  || 0;
                let commAmt     = afterDis * commPct / 100;
                let finalAmt    = afterDis + commAmt;

                $row.find('.dis_amount_label').text('Rs ' + disRs.toFixed(2));
                $row.find('.commission_label').text('Rs ' + commAmt.toFixed(2));
                $row.find('.amount').val(amount.toFixed(2));
                $row.find('.net_amount').val(finalAmt.toFixed(2));

                subtotal        += amount;
                totalDiscount   += disRs;
                totalCommission += commAmt;
                netbill         += finalAmt;
            });

            $("input[name='subtotal']").val(subtotal.toFixed(2));
            $("input[name='total_discount']").val(totalDiscount.toFixed(2));
            $("input[name='total_commission']").val(totalCommission.toFixed(2));
            $("input[name='net_bill']").val(netbill.toFixed(2));
        }
    });
</script>
@endsection
