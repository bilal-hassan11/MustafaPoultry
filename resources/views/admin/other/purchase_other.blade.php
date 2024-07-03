@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="card">
                <div class="card-header">
                    <h4>Purchase other Items</h4>
                </div>
                <form id="formData">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <input type="hidden" name="type" class="form-control text-right" value="Purchase">
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
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card ">
                        <div class="card-header">
                            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                                <div class="ms-auto pageheader-btn"> 
                                    <a class="modal-effect btn btn-primary d-grid  me-2" data-bs-effect="effect-newspaper" data-bs-toggle="modal" href="#modaldemo8">Pending Purchases</a>
                                </div> 
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.other-invoices.purchase') }}" method="GET">
                                @csrf
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="">Accounts</label>
                                        <select class="form-control select2" name="account_id" id="account_id2">
                                            <option value="">Select Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->hashid }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                <div class="col-xl-12">
                    
                    
                    <div class="card-body">
                        
                        <div class="modal fade" id="modaldemo8" style="display: none" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                                <div class="modal-content modal-content-demo">
                                    <div class="modal-header">
                                        <h6 class="modal-title">Pending Others Items Purchases</h6>
                                        <button
                                        aria-label="Close"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        ></button>
                                    </div>
                                    <div class="modal-body text-start">
                                    <table id="example54" class="text-fade table table-bordered" >
                                        <thead>
                                            <tr class="text-dark">
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pending_Other as $pending)
                                                <tr class="text-dark">
                                                    
                                                    <td style="width: 15%; !important"><span
                                                            class="waves-effect waves-light btn btn-rounded btn-danger-light">{{ $pending->account->name ?? '' , $pending->item->name ?? '' , abs($pending->quantity) }}</span>
                                                    </td>

                                                    <td style="width: 20%; !important">
                                                        
                                                        <a class="btn btn-outline-info rounded-pill btn-wave mr-2"
                                                            href="{{ route('admin.other-invoices.edit.purchase', ['invoice_no' => $pending->invoice_no]) }}"
                                                            title="Edit">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                        
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-light" data-bs-dismiss="modal">
                                        Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title mb-0">All Purchase Other Items Detail</h3>
                        </div>
                        <div class="card-body">
                            <table id="example54" class="text-fade table table-bordered" style="width:100%">
                                <thead>
                                    <tr class="text-dark">
                                        <th>S.No</th>
                                        <th>Date</th>
                                        <th>Invoice No</th>
                                        <th>Account Name</th>
                                        <th>Item</th>
                                        <th>Rate</th>
                                        <th>Quantity</th>
                                        <th>Net Ammount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $tot_q = 0;
                                    $tot_amt = 0; ?>
                                    @foreach ($purchase_Other as $purcahse)
                                        <tr class="text-dark">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d-M-Y', strtotime($purcahse->date)) }}</td>
                                            <td>{{ $purcahse->invoice_no }}</td>
                                            <td><span
                                                    class="waves-effect waves-light btn btn-rounded btn-danger-light">{{ $purcahse->account->name ?? '' }}</span>
                                            </td>

                                            <td><span
                                                    class="waves-effect waves-light btn btn-rounded btn-info-light">{{ $purcahse->item->name ?? '' }}</span>
                                            </td>
                                            <td>{{ number_format(@$purcahse->net_amount / @$purcahse->quantity, 2) }}</td>
                                            <?php $tot_q += $purcahse->quantity; ?>
                                            <td>{{ $purcahse->quantity }}</td>
                                            <?php $tot_amt += $purcahse->net_amount; ?>
                                            <td>{{ $purcahse->net_amount }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a class="btn btn-outline-info rounded-pill btn-wave mr-3"
                                                        href="{{ route('admin.other-invoices.purchase.show', ['invoice_no' => $purcahse->invoice_no]) }}"
                                                        title="View">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    <a class="btn btn-outline-info rounded-pill btn-wave mr-2"
                                                        href="{{ route('admin.other-invoices.edit.purchase', ['invoice_no' => $purcahse->invoice_no]) }}"
                                                        title="Edit">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <a href="{{ route('admin.other-invoices.purchase.show', ['invoice_no' => $purcahse->invoice_no, 'generate_pdf' => 1]) }}"
                                                        class="btn btn-outline-info rounded-pill btn-wave" target="_blank"
                                                        title="Download">
                                                        <i class="ri-download-2-line"></i>
                                                    </a>
                                                </div>
                                            </td>
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
                                        <th>-</th>
                                    </tr>
                                </tfoot>
                            </table>

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
                    url: "{{ route('admin.other-invoices.store') }}",
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

            $("body").on("input keyup blur", ".product_val, .quantity, .purchaseRate, .dis_in_percentage",
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
