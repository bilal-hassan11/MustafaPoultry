@extends('layouts.admin')
@section('content')
<div class="main-content app-content mt-6">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
        <!-- PAGE-HEADER END --> <!-- ROW-1 --> 
      
        <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 --> 
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">Add Item Detail</h3>
                </div>
                <div class="card-body">
                
                <form id="formData" method="POST" action="#">
                    @csrf
                    <div class="card-header">
                        <h4>{{ trans('cruds.transferStock.title_singular') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="date" class="required">{{ trans('global.fields.stock_number') }}:</label>
                                <input type="text" name="stock_number" class="form-control" value="" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="date" class="required">{{ trans('global.fields.date') }}:</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="from_warehouse_id" class="required">From Warehouse</label>
                                <select class="form-control select2 warehouse" name="from_warehouse_id" required>
                                   
                                </select>
                            </div>
                            <div class="col-md-4 mb-3 mr-8">
                                <label for=" " class="required">To Warehouse</label>
                                <select class="form-control select2" name="to_warehouse_id" required>
                                    <option value="">Select</option>
                                   
                                </select>
                            </div>
                            <div class="col-md-4 mb-3 ">
                            </div>
                            <div class="col-md-4 mb-3 ">
                                <label for="barcode">Barcode Search</label>
                                <div class="input-group">
                                    <input class="form-control" id="barcode_scanner" name="barcode" placeholder="Scan Barcode"
                                        type="text" value="" autofocus>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success search">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="width: 100%; overflow-x: auto">
                        <table class="table table-bordered text-center add-stock-table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">{{ trans('global.fields.product') }}</th>
                                    <th style="width: 20%;">Unit Type</th>
                                    <th style="width: 20%;">Quantity</th>
                                    <th style="width: 20%;">Total Units</th>
                                    <th style="width: 20%;">{{ trans('global.action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="row">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"></td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-info btn-sm add-row">{{ trans('global.add_row') }}</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <button type="button" class="btn btn-success mt-2" id="submit-form">{{ trans('global.submit') }}</button>
                    </div>
                </form>
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <!-- ROW-5 END -->
        
       
    </div>
    <!-- CONTAINER END --> 
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
                       
                        <select class="form-control product product_val" name="product[]" id="products" required>
                            <option value="">{{ trans('global.fields.select_product') }}</option>
                           
                        </select>
                        
                    </td>
                    <td class="quantity_col">
                        <input type="number" name="quantity[]" class="form-control quantity text-right" min="1" value="1" required>
                    </td>
                    <td class="unit_type">
                        <select name="unit_type_id[]" class="form-control unitType" required></select>
                    </td>
                    <td class="unit_qty">
                        <input type="text" name="unit_qty[]" class="form-control unitQty text-right" readonly>
                    </td>
                    <td class="price">
                        <input type="text" name="price[]" class="form-control sellPrice text-right" readonly>
                    </td>
                    <td class="total">
                        <input type="text" name="total[]" class="form-control total_amount text-right" readonly>
                    </td>
                    <td>
                        <select class="form-control warehouse" name="warehouse_id[]" required>
                            
                        </select>
                    </td>
                    <td>
                        <button type="button" class="waves-effect waves-light btn btn-rounded btn-danger-light delete-row" title="Delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
                $("table tbody").append(row);
                $('select.product_val').select2({
                    width: '100%',
                });
                // Hide delete button for the first row
                if ($("table tbody tr").length > 0) {
                    $("table tbody tr:first .delete-row").hide();
                }
            }

            $(document).on("click", ".delete-row", function() {
                $(this).closest("tr").remove();
            });

            $("#submit-form").click(function(e) {

                let totalPaid = parseFloat($('.total_paid').val());
                let totalBill = parseFloat($('.total_bill').val());

                if (isNaN(totalPaid) || isNaN(totalBill) || totalPaid < 0 || totalBill < 0 ||
                    totalPaid > 999999.99 || totalBill > 999999.99) {
                    toastr.error('Invalid total paid or total bill amount.');
                    return false;
                }

                $(this).prop('disabled', true);


                if (totalPaid > totalBill) {
                    toastr.error('Paid amount should be less than or equal to the Total Bill.');
                    $(this).prop('disabled', false);
                    return;
                }


                let formData = $("#formData").serializeArray();

                $.ajax({
                    url: $("#formData").attr('action'),
                    type: "POST",
                    data: formData,
                    success: function(response) {

                        $("#submit-form").prop('disabled', false);

                        
                    },
                    error: function(xhr) {

                        $("#submit-form").prop('disabled', false);

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            for (const error in xhr.responseJSON.errors) {
                                toastr.error(xhr.responseJSON.errors[error][0]);
                            }
                        } else {
                            toastr.error("Error submitting the form.");
                        }
                    }
                });
            });

            // Search by barcode
            $(document).on("input", "#barcode_scanner", function() {
                searchBarcode(productDetailsArray, "#barcode_scanner", ".product_val", ".add-row");
            });
            $(document).on("click", "#search", function() {
                searchBarcode(productDetailsArray, "#barcode_scanner", ".product_val", ".add-row");
            });

            // Function to search by barcode
            function searchBarcode(productDetailsArray, barcodeInputID, productValSelector, addRowSelector) {
                let newBarcode = $(barcodeInputID).val().trim();
                let product = null;
                if (newBarcode !== "") {
                    $.each(productDetailsArray, function(index, value) {
                        if (value.barcode.trim() === newBarcode) {
                            product = value;
                            return false;
                        }
                    });
                    if (product) {
                        const currentRow = $("table tbody tr:last");
                        if (currentRow.find(productValSelector).val() != '') {
                            $(addRowSelector).trigger('click');
                        }
                        const lastRow = $("table tbody tr:last");
                        lastRow.find(productValSelector).val(product.id).trigger("change");
                        $(barcodeInputID).val('');
                    }
                }
            }


            $(".btn-group button").click(function() {
                const $buttons = $(".btn-group button");
                $buttons.removeClass("btn-primary").addClass("btn-secondary");
                $(this).removeClass("btn-secondary").addClass("btn-primary");

                const paymentType = $(this).attr('id') === 'credit' ? 'Credit' : 'Cash';
                $("input[name='pay_type']").val(paymentType);

                const totalBill = parseFloat($('.total_bill').val());
                const totalPaid = paymentType === "Credit" ? 0.00 : totalBill;
                $('.total_paid').val(totalPaid.toFixed(2));

                if (paymentType == 'Credit') {
                    $('.total_paid').prop('readonly', false);
                } else {
                    $('.total_paid').prop('readonly', true);
                }
            });

            function fetchUnitTypes(productId, warehouseId, unitTypeDropdown) {
                unitTypeDropdown.empty();

                
            }

            $(document).on("change", "select.product_val", function() {
                let productId = $(this).val();
                let unitTypeDropdown = $(this).closest("tr").find(".unitType");
                let warehouseId = $(this).closest("tr").find(".warehouse").val();
                fetchUnitTypes(productId, warehouseId, unitTypeDropdown);
            });

            $(document).on("change", "select.warehouse", function() {
                let productId = $(this).closest("tr").find(".product_val").val();
                let unitTypeDropdown = $(this).closest("tr").find(".unitType");
                let warehouseId = $(this).val();
                fetchUnitTypes(productId, warehouseId, unitTypeDropdown);
            });

            $(document).on("change", "select.unitType", function() {
                updatePricesBasedOnToggle();
                calculation();
            });


            $('#priceToggle').change(function() {
                updatePricesBasedOnToggle();
            });

            function updatePricesBasedOnToggle() {
                let toggleChecked = $('#priceToggle').prop('checked');

                $("table tbody tr").each(function() {
                    let productId = $(this).find(".product_val").val();
                    let unitTypeID = $(this).find(".unitType").val();
                    let priceCol = $(this).find(".sellPrice");

                    if (productId && productDetailsArray[productId]) {
                        let unitTypes = productDetailsArray[productId].product_details;
                        let price = 0;

                        if (unitTypes && unitTypes.length > 0) {
                            let selectedUnitType = unitTypes.find(function(unitType) {
                                return unitType.unit_type_id == unitTypeID;
                            });

                            if (selectedUnitType) {
                                price = toggleChecked ? selectedUnitType.special_selling_price :
                                    selectedUnitType.general_selling_price;
                            } else {
                                price = 0;
                            }

                        }

                        priceCol.val(price);
                        calculation();
                    }
                });
            }

            function calculation() {
                let totalBill = 0;
                let paymentType = $("input[name='pay_type']").val();

                $("table tbody tr").each(function() {
                    let quantity = parseFloat($(this).find(".quantity").val());
                    let unitTypeQty = parseFloat($(this).find(".unitType option:selected").data(
                        "quantity"));
                    let unitQty = isNaN(quantity) || isNaN(unitTypeQty) ? 0 : quantity * unitTypeQty;

                    $(this).find(".unitQty").val(unitQty);

                    let price = parseFloat($(this).find(".sellPrice").val());
                    let total = isNaN(quantity) || isNaN(price) ? 0 : quantity * price;

                    $(this).find(".total_amount").val(total.toFixed(2));
                    totalBill += total;
                });

                $(".total_bill").val(totalBill.toFixed(2));

                let totalPaid = paymentType === 'Credit' ? 0.00 : totalBill;
                $('.total_paid').val(totalPaid.toFixed(2));
            }


            $(document).on("input", ".quantity, .sellPrice", function() {
                $(this).closest("tr").find(".unitType").trigger('change');
                calculation();
            });

        });
    </script>
<script>
    $('#grand_parent_id').change(function(){
        var id = $(this).val();
        var route = "{{ route('admin.common.get_parent_account', ':id') }}";
        route     = route.replace(':id', id);
        
        getAjaxRequests(route, '', 'GET', function(resp){
            $('#parent_id').html(resp.html);
        });
    });
</script>
@endsection