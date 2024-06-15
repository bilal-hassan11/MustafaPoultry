@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <!-- Purchase Murghi Section -->
            <div class="card">
                <div class="card-header">
                    <h4>Purchase Murghi</h4>
                </div>
                <form id="purchaseForm">
                    @csrf
                    <input type="hidden" name="type" value="Purchase">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
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
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label for="weight" class="required">Weight</label>
                                <input type="number" name="weight" class="form-control weight" min="0"
                                    step="any">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="weight_detection" class="required">Weight Detection</label>
                                <input type="number" name="weight_detection" class="form-control weight_detection"
                                    min="0" step="any">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="final_weight" class="required">Final Weight</label>
                                <input type="number" name="final_weight" class="form-control final_weight" min="0"
                                    step="any" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="rate" class="required">Rate</label>
                                <input type="number" name="rate" class="form-control rate" min="0"
                                    step="any">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="amount" class="required">Amount</label>
                                <input type="number" name="amount" class="form-control amount" min="0"
                                    step="any" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Sale Murghi Section -->
            <div class="card mt-5">
                <div class="card-header">
                    <h4>Sale Murghi</h4>
                </div>
                <form id="saleForm">
                    @csrf
                    <input type="hidden" name="type" value="Sale">
                    <div class="card-body" style="width: 100%; overflow-x: auto">
                        <table class="table table-bordered text-center" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Account</th>
                                    <th style="width: 10%;">Weight</th>
                                    <th style="width: 10%;">Weight Detection</th>
                                    <th style="width: 10%;">Final Weight</th>
                                    <th style="width: 12%;">Rate</th>
                                    <th style="width: auto;">Amount</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="saleRows">
                                <tr class="saleRow">
                                    <td>
                                        <select class="form-control select2" name="sale_account[]">
                                            <option value="">Select Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="sale_weight[]" class="form-control sale_weight"
                                            min="0" step="any">
                                    </td>
                                    <td>
                                        <input type="number" name="sale_weight_detection[]"
                                            class="form-control sale_weight_detection" min="0" step="any">
                                    </td>
                                    <td>
                                        <input type="number" name="sale_final_weight[]"
                                            class="form-control sale_final_weight" min="0" step="any"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="sale_rate[]" class="form-control sale_rate"
                                            min="0" step="any">
                                    </td>
                                    <td>
                                        <input type="number" name="sale_amount[]" class="form-control sale_amount"
                                            min="0" step="any" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-danger fa fa-trash remove-row"
                                            title="Remove Row"></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" style="text-align: right;">
                                        <label>Total Amount</label>
                                    </td>
                                    <td>
                                        <input type="text" name="total_sale_amount" class="form-control" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-sm btn-info fa fa-plus add-sale-row"
                                            title="Add Row"></button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
            <!-- Submit Button for Both Forms -->
            <div class="card mt-5">
                <div class="card-body text-center">
                    <button type="button" id="submitButton" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Calculate final weight and amount in purchase section
            $(".weight, .weight_detection, .rate").on("input", function() {
                let weight = parseFloat($("input[name='weight']").val()) || 0;
                let weightDetection = parseFloat($("input[name='weight_detection']").val()) || 0;
                let finalWeight = weight - weightDetection;
                $("input[name='final_weight']").val(finalWeight);

                let rate = parseFloat($("input[name='rate']").val()) || 0;
                let amount = finalWeight * rate;
                $("input[name='amount']").val(amount.toFixed(2));
            });

            // Add new sale row
            $(".add-sale-row").click(function() {
                let newRow = `
                <tr class="saleRow">
                    <td>
                        <select class="form-control select2" name="sale_account[]">
                            <option value="">Select Account</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="sale_weight[]" class="form-control sale_weight" min="0" step="any">
                    </td>
                    <td>
                        <input type="number" name="sale_weight_detection[]" class="form-control sale_weight_detection" min="0" step="any">
                    </td>
                    <td>
                        <input type="number" name="sale_final_weight[]" class="form-control sale_final_weight" min="0" step="any" readonly>
                    </td>
                    <td>
                        <input type="number" name="sale_rate[]" class="form-control sale_rate" min="0" step="any">
                    </td>
                    <td>
                        <input type="number" name="sale_amount[]" class="form-control sale_amount" min="0" step="any" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn-sm btn-danger fa fa-trash remove-row" title="Remove Row"></button>
                    </td>
                </tr>`;
                $("#saleRows").append(newRow);
                $('.select2').select2({
                    width: '100%'
                });
            });

            // Calculate final weight and amount in sale section
            $("body").on("input", ".sale_weight, .sale_weight_detection, .sale_rate", function() {
                let $row = $(this).closest("tr");
                let weight = parseFloat($row.find(".sale_weight").val()) || 0;
                let weightDetection = parseFloat($row.find(".sale_weight_detection").val()) || 0;
                let finalWeight = weight - weightDetection;
                $row.find(".sale_final_weight").val(finalWeight);

                let rate = parseFloat($row.find(".sale_rate").val()) || 0;
                let amount = finalWeight * rate;
                $row.find(".sale_amount").val(amount.toFixed(2));

                calculateSaleTotal();
            });

            // Remove row
            $("body").on("click", ".remove-row", function() {
                $(this).closest("tr").remove();
                calculateSaleTotal();
            });

            // Calculate total amount for sale section
            function calculateSaleTotal() {
                let total = 0;
                $("#saleRows .sale_amount").each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                $("input[name='total_sale_amount']").val(total.toFixed(2));
            }

            // Submit both forms
            $("#submitButton").click(function() {
                let purchaseData = $("#purchaseForm").serialize();
                let saleData = $("#saleForm").serialize();
                let data = purchaseData + '&' + saleData;

                $.ajax({
                    url: "{{ route('admin.murghi-invoices.purchase_sale') }}", // Adjust the route as needed
                    method: "POST",
                    data: data,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data saved successfully!',
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
                    }
                });
            });
        });
    </script>
@endsection
