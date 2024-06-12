@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="card">
                <div class="card-header">
                    <h4>Adjust Stock In</h4>
                </div>
                <form id="formData">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3 form-group">
                                <input type="hidden" name="type" class="form-control text-right" value="Adjust Stock">
                                <label for="invoice_no" class="required">Stock #</label>
                                <input type="text" name="invoice_no" class="form-control" value="{{ $invoice_no }}"
                                    readonly>
                            </div>
                            <div class="col-md-2 mb-3 form-group">
                                <label for="date" class="required">Date</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-4 mb-3 form-group">
                                <label for="ref_no" class="required">Reference No</label>
                                <input type="text" name="ref_no" class="form-control" placeholder="Reference No">
                            </div>
                            <div class="col-md-4 mb-3 form-group">
                                <label for="description" class="required">Description</label>
                                <input type="text" name="description" class="form-control" placeholder="Description">
                            </div>
                            <input type="hidden" name="stock_type" value="In">
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
                                    <th style="width: auto;">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="row">
                            </tbody>
                            <tfoot>
                                <tr style="text-align: right;">
                                    <td colspan="4">
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
                                    <td colspan="4" style="text-align: right;">
                                        Discount
                                    </td>
                                    <td>
                                        <input type="text" name="total_discount" class="form-control text-right"
                                            value="0" style="text-align: right;" readonly>
                                    </td>
                                </tr>
                                <tr style="text-align: right;">
                                    <td colspan="4">
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
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title mb-0"> Purchase Medicine Filters</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.medicine-invoices.adjust_in') }}" method="GET">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Invoice No</label>
                                        <input type="text" class="form-control" name="invoice_no" id="invoice_no">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Item</label>
                                        <select class="form-control select2" name="item_id" id="item_id">
                                            <option value="">Select Item</option>
                                            @foreach ($products as $item)
                                                <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">From</label>
                                        <input type="date" class="form-control" name="from_date" id="from_date">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">To</label>
                                        <input type="date" class="form-control" name="to_date" id="to-date">
                                    </div>
                                    <div class="col-md-1 mt-6">
                                        <input type="submit" class="btn btn-primary" value="Search">
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <!-- COL END -->
            </div>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title mb-0">All Adjusted Stock Details</h3>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <div id="data-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="example54" class="text-fade table table-bordered"
                                                style="width:100%">
                                                <thead>
                                                    <tr class="text-dark">
                                                        <th>S.No</th>
                                                        <th>Date</th>
                                                        <th>Invoice No</th>
                                                        <th>In/Out</th>
                                                        <th>Item</th>
                                                        <th>Rate</th>
                                                        <th>Quantity</th>
                                                        <th>Ammount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $tot_q = 0;
                                                    $tot_amt = 0; ?>
                                                    @foreach ($purchase_medicine as $purcahse)
                                                        <tr class="text-dark">
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ date('d-M-Y', strtotime($purcahse->date)) }}</td>
                                                            <td>{{ $purcahse->invoice_no }}</td>
                                                            <td><span
                                                                    class="waves-effect waves-light btn btn-rounded btn-info-light">{{ $purcahse->item->name ?? '' }}</span>
                                                            </td>
                                                            <td><span
                                                                    class="waves-effect waves-light btn btn-rounded btn-info-light">{{ $purcahse->stock_type ?? '' }}</span>
                                                            </td>
                                                            <td>{{ number_format(@$purcahse->net_amount / @$purcahse->quantity, 2) }}
                                                            </td>
                                                            <?php $tot_q += $purcahse->quantity; ?>
                                                            <td>{{ $purcahse->quantity }}</td>
                                                            <?php $tot_amt += $purcahse->net_amount; ?>
                                                            <td>{{ $purcahse->net_amount }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="text-dark">
                                                        <th>Total</th>
                                                        <th>-</th>
                                                        <th>-</th>
                                                        <th>-</th>
                                                        <th>-</th>
                                                        <th>-</th>
                                                        <th>{{ $tot_q }}</th>
                                                        <th>{{ $tot_amt }}</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- COL END -->
            </div>
        </div>

    </div>
@endsection
@section('page-scripts')
    <script type="text/javascript">
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
                                    $purchasePrice = $product->last_purchase_price ? $product->last_purchase_price : 1;
                                @endphp
                                <option value="{{ $product->id }}" data-price="{{ $purchasePrice }}">
                                    {{ $product->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @endif
                    </td>
                    <td class="quantity_col">
                        <input type="number" name="quantity[]" class="form-control quantity text-right" min="1" value="1" step="any" style="text-align: right;" required>
                    </td>
                    <td class="purchase_rate_col">
                        <input type="number" name="purchase_price[]" class="form-control purchaseRate text-right" value="1"  step="any" style="text-align: right;" required>
                        <input type="hidden" name="sale_price[]" class="form-control saleRate text-right" value="0" step="any" style="text-align: right;" required>
                    </td>
                    <td class="expiry_date">
                        <input type="date" name="expiry_date[]" class="form-control text-right">
                    </td>
                    <input type="hidden" name="amount[]" class="form-control amount text-right" value="0" step="any" style="text-align: right;">
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

                $(".product_val").last().change(function() {
                    updatePurchasePrice($(this));
                });

                $(".dis_in_rs").last().on('input', function() {
                    Calculation(true);
                });

            }



            function updatePurchasePrice($selectElement) {
                let purchasePrice = $selectElement.find('option:selected').data('price');
                $selectElement.closest('tr').find('.purchaseRate').val(purchasePrice);
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
                    url: "{{ route('admin.medicine-invoices.store_adjustment') }}",
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

            $("body").on("input keyup blur", ".product_val, .quantity, .purchaseRate",
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
                    let rate = parseFloat($row.find(".purchaseRate").val()) || 0;
                    let amount = qty * rate;

                    $row.find(".amount").val(amount.toFixed(2));
                    $row.find(".net_amount").val(amount.toFixed(2));

                    subtotal += amount;
                    netbill += amount;
                });

                $("input[name='subtotal']").val(subtotal.toFixed(2));
                $("input[name='total_discount']").val(totalDiscount.toFixed(2));
                $("input[name='net_bill']").val(netbill.toFixed(2));
            }
        });
    </script>
@endsection
