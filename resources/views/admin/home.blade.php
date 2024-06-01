@extends('layouts.admin')

@section('content')
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <!-- CONTAINER -->
            <div class="main-container container-fluid">
                <br /><br />
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                        <div class="row total-sales-card-section">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                <div class="card custom-card overflow-hidden">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h6 class="fw-normal fs-14">Total Sales</h6>
                                                <h3 class="mb-2 number-font fs-24">34,516</h3>
                                                <p class="text-muted mb-0"> <span class="text-primary"> <i
                                                            class="ri-arrow-up-s-line bg-primary text-white rounded-circle fs-13 p-0 fw-semibold align-bottom"></i>
                                                        3%</span> last month </p>
                                            </div>
                                            <div class="col col-auto mt-2">
                                                <div
                                                    class="counter-icon bg-primary-gradient box-shadow-primary rounded-circle ms-auto mb-0">
                                                    <i class="fe fe-trending-up mb-5 "></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                <div class="card custom-card overflow-hidden">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h6 class="fw-normal fs-14">Total Leads</h6>
                                                <h3 class="mb-2 number-font fs-24">56,992</h3>
                                                <p class="text-muted mb-0"> <span class="text-secondary"> <i
                                                            class="ri-arrow-up-s-line bg-secondary text-white rounded-circle fs-13 p-0 fw-semibold align-bottom"></i>
                                                        3%</span> last month </p>
                                            </div>
                                            <div class="col col-auto mt-2">
                                                <div
                                                    class="counter-icon bg-danger-gradient box-shadow-danger rounded-circle  ms-auto mb-0">
                                                    <i class="ri-rocket-line mb-5  "></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                <div class="card custom-card overflow-hidden">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h6 class="fw-normal fs-14">Total Profit</h6>
                                                <h3 class="mb-2 number-font fs-24">$42,567</h3>
                                                <p class="text-muted mb-0"> <span class="text-success"> <i
                                                            class="ri-arrow-down-s-line bg-primary text-white rounded-circle fs-13 p-0 fw-semibold align-bottom"></i>
                                                        0.5%</span> last month </p>
                                            </div>
                                            <div class="col col-auto mt-2">
                                                <div
                                                    class="counter-icon bg-secondary-gradient box-shadow-secondary rounded-circle ms-auto mb-0">
                                                    <i class="fe fe-dollar-sign  mb-5 "></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                <div class="card custom-card overflow-hidden">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h6 class="fw-normal fs-14">Total Cost</h6>
                                                <h3 class="mb-2 number-font fs-24">$34,789</h3>
                                                <p class="text-muted mb-0"> <span class="text-danger"> <i
                                                            class="ri-arrow-down-s-line bg-danger text-white rounded-circle fs-13 p-0 fw-semibold align-bottom"></i>
                                                        0.2%</span> last month </p>
                                            </div>
                                            <div class="col col-auto mt-2">
                                                <div
                                                    class="counter-icon bg-success-gradient box-shadow-success rounded-circle  ms-auto mb-0">
                                                    <i class="fe fe-briefcase mb-5 "></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br /><br />
            </div>
            <div class="row">

                <div class="col-xl-3 col-md-12 col-lg-6">

                    <div class="card custom-card">

                        <div class="card-body text-center">

                            <h6 class="">
                                <span class="text-primary">
                                    <i class="fe fe-file-text mx-2 fs-20 text-primary-shadow  align-middle"></i>
                                </span>
                                Total Feed Purchase
                            </h6>
                            <h4 class="text-dark counter mt-0 mb-3 number-font">{{ @$tot_purchase_feed_ammount }}</h4>
                            <div class="progress h-1 mt-0 mb-2">

                                <div class="progress-bar progress-bar-striped bg-primary w-70" role="progressbar">

                                </div>
                            </div>
                            <div class="row mt-4">

                                <div class="col text-center">

                                    <span class="text-muted"> Bags </span>
                                    <h4 class="fw-normal mt-2 mb-0 number-font1">{{ @$tot_purchase_feed_begs }}</h4>
                                </div>
                                <div class="col text-center">

                                    <span class="text-muted">Ammount</span>
                                    <h4 class="fw-normal mt-2 mb-0 number-font2">{{ @$tot_purchase_feed_ammount }}</h4>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-12 col-lg-6">

                    <div class="card overflow-hidden">

                        <div class="card-body text-center">

                            <h6 class="">
                                <span class="text-secondary">
                                    <i class="ri-group-line mx-2 fs-20 text-secondary-shadow align-middle"></i>
                                </span>
                                Total Feed Sale
                            </h6>
                            <h4 class="text-dark counter mt-0 mb-3 number-font">{{ @$tot_sale_feed_ammount }}</h4>
                            <div class="progress h-1 mt-0 mb-2">

                                <div class="progress-bar progress-bar-striped  bg-secondary w-50" role="progressbar"></div>
                            </div>
                            <div class="row mt-4">


                                <div class="col text-center">

                                    <span class="text-muted">Bags</span>
                                    <h4 class="fw-normal mt-2 mb-0 number-font1">{{ @$tot_sale_feed_begs }}</h4>
                                </div>
                                <div class="col text-center">

                                    <span class="text-muted">Ammount</span>
                                    <h4 class="fw-normal mt-2 mb-0 number-font1">{{ @$tot_sale_feed_ammount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-12 col-lg-6">

                    <div class="card overflow-hidden">

                        <div class="card-body text-center">

                            <h6 class="">
                                <span class="text-success">
                                    <i class="fe fe-award mx-2 fs-20 text-success-shadow  align-middle"></i>
                                </span>
                                Total Feed Sale Return
                            </h6>
                            <h4 class="text-dark counter mt-0 mb-3 number-font">{{ @$tot_purchase_feed_ammount }}</h4>

                            <div class="progress h-1 mt-0 mb-2">

                                <div class="progress-bar progress-bar-striped  bg-success w-60" role="progressbar">

                                </div>
                            </div>
                            <div class="row mt-4">

                                <div class="col text-center">

                                    <span class="text-muted">Bags</span>
                                    <h4 class="fw-normal mt-2 mb-0 number-font1">{{ @$tot_sale_return_feed_begs }}</h4>
                                </div>
                                <div class="col text-center">

                                    <span class="text-muted">Ammount</span>
                                    <h4 class="fw-normal mt-2 mb-0 number-font1">{{ @$tot_sale_return_feed_ammount }}</h4>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-12 col-lg-6">

                    <div class="card overflow-hidden">

                        <div class="card-body text-center">

                            <h6 class="">
                                <span class="text-info">
                                    <i class="fe fe-tag mx-2 fs-20 text-info-shadow  align-middle"></i>
                                </span>
                                Total Feed Purchase Return
                            </h6>
                            <h4 class="text-dark counter mt-0 mb-3 number-font">{{ $tot_purchase_return_feed_ammount }}
                            </h4>
                            <div class="progress h-1 mt-0 mb-2">

                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-info w-40"
                                    role="progressbar"></div>
                            </div>
                            <div class="row mt-4">

                                <div class="col text-center">

                                    <span class="text-muted">Bags</span>
                                    <h4 class="fw-normal mt-2 mb-0 number-font1">{{ $tot_purchase_return_feed_begs }}</h4>
                                </div>
                                <div class="col text-center">

                                    <span class="text-muted">Ammount</span>
                                    <h4 class="fw-normal mt-2 mb-0 number-font1">{{ $tot_purchase_return_feed_ammount }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-cards">
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card custom-card">
                        <div class="card-header pb-0 border-bottom-0">
                            <h3 class="card-title">Total Credit</h3>
                        </div>
                        <div class="card-body pt-0">
                            <h3 class="d-inline-block mb-2">{{ @$tot_credit != null ? @$tot_credit : 0 }}</h3>
                            <div class="progress h-2 mt-2 mb-2">
                                <div class="progress-bar bg-primary w-50" role="progressbar"></div>
                            </div>
                            <div class="float-start">
                                <div class="mt-2">
                                    <i class="fa fa-caret-up text-success"></i>
                                    <span>12% increase</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- COL END -->
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card custom-card">
                        <div class="card-header pb-0 border-bottom-0">
                            <h3 class="card-title">Total Debit</h3>
                        </div>
                        <div class="card-body pt-0">
                            <h3 class="d-inline-block mb-2">{{ @$tot_debit != null ? @$tot_debit : 0 }}</h3>
                            <div class="progress h-2 mt-2 mb-2">
                                <div class="progress-bar bg-success w-50" role="progressbar"></div>
                            </div>
                            <div class="float-start">
                                <div class="mt-2">
                                    <i class="fa fa-caret-down text-success"></i>
                                    <span>5% decrease</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- COL END -->
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card custom-card">
                        <div class="card-header pb-0 border-bottom-0">
                            <h3 class="card-title">Total Expense</h3>
                        </div>
                        <div class="card-body pt-0">
                            <h3 class="d-inline-block mb-2">{{ @$tot_expense != null ? @$tot_expense : 0 }}</h3>
                            <div class="progress h-2 mt-2 mb-2">
                                <div class="progress-bar bg-warning w-50" role="progressbar"></div>
                            </div>
                            <div class="float-start">
                                <div class="mt-2">
                                    <i class="fa fa-caret-up text-warning"></i>
                                    <span>10% increase</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- COL END -->
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card custom-card">
                        <div class="card-header pb-0 border-bottom-0">
                            <h3 class="card-title">Cash In Hand</h3>
                        </div>
                        <div class="card-body pt-0">
                            <h3 class="d-inline-block mb-2">{{ @$tot_cash_in_hand != null ? @$tot_cash_in_hand : 0 }}</h3>
                            <div class="progress h-2 mt-2 mb-2">
                                <div class="progress-bar bg-danger w-50" role="progressbar"></div>
                            </div>
                            <div class="float-start">
                                <div class="mt-2">
                                    <i class="fa fa-caret-down text-danger"></i>
                                    <span>15% decrease</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- COL END -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5>Low Stock Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-fit datatable" id="sellingTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Item</th>
                                            <th>Total Quantity Sold</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lowStockAlertProducts as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->total_quantity_sold }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5>Expired Products</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive deals-table">
                                <table class="table text-nowrap table-hover border table-bordered" id="sellingTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Item</th>
                                            <th>Total Quantity Sold</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expired_items as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->total_quantity_sold }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 col-xl-4">
                        <div class="card custom-card">
                            <div class="card-header border-bottom justify-content-between">

                                <h5 class="card-title">Recent Sales </h5>
                                <div class="col-lg-5 float-right">
                                    <select class="form-control 
                                    select2"
                                        name="sale_category" id="sale_category">
                                        <option value="chick">Chick</option>
                                        <option value="murghi">Murghi</option>
                                        <option value="feed">Feed</option>
                                        <option value="medicine">Medicine</option>
                                        <option value="others">Others</option>
                                    </select>
                                </div>

                            </div>
                            <div class="card-body" id="latest-sale">


                            </div>
                        </div>
                    </div>

                    <!-- COL END -->
                    <div class="col-xl-4 col-md-12">
                        <div class="card custom-card">
                            <div class="card-header">
                                <h5 class="card-title">Recent Purchases</h5>
                                <div class="col-lg-5 float-right">
                                    <select class="form-control 
                            select2" name="category_id"
                                        id="category_id">
                                        <option value="chick">Chick</option>
                                        <option value="chick">Murghi</option>
                                        <option value="chick">Feed</option>
                                        <option value="medicine">Medicine</option>
                                        <option value="others">Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="media mb-3 mt-0">

                                    <div class="media-body">
                                        <a href="javascript:void(0);" class="text-dark">Nathaniel Bustos</a>
                                        <div class="text-muted small">Rate : 40 , Qty :30</div>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm d-block ms-auto">
                                        120
                                    </button>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!-- COL END -->
                    <div class="col-xl-4 col-sm-12 p-l-0 p-r-0 col-md-12">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">Total Return</div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Account</th>
                                                <th>Item</th>
                                                <th>Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>


                                                <td>
                                                    <span>Account Name</span>
                                                </td>
                                                <td><span>Item Name</span></td>
                                                <td><span><i
                                                            class="ri-arrow-up-s-fill me-1 text-success align-middle fs-18"></i>23,379</span>
                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- COL END -->
                </div>
                <!-- <div id="hightChart">

                                                                                                                                    </div>
                                                                                                                                    <div id="consumption_chart">

                                                                                                                                    </div>

                                                                                                                                    <br />
                                                                                                                                    <div id="sale_chart" class="chart"></div>

                                                                                                                                    <div class="map_canvas">
                                                                                                                                    
                                                                                                                                                <canvas id="myChart" width="auto" height="100"></canvas>
                                                                                                                                    </div> -->
                <!-- CONTAINER END -->
            </div>
        </div>
    @endsection
    @section('page-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/10.2.1/highcharts.min.js"></script>
        <!-- Show Graph Data -->
        <script src="https://cdnjs.com/libraries/Chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>



        <script>
            $(document).ready(function() {
                $('#sale_category').change(function() {
                    let category = $(this).val();
                    alert(category);
                    if (category) {
                        $.ajax({
                            url: `{{ route('admin.common.latest.sale', ':category') }}`.replace(
                                ':category', category),
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    console.log(response.data);
                                    response.data.forEach(function(sale) {

                                        salesHtml = `
                                    <div class="clearfix row mb-3 " >
                            
                        
                                        <div class="col">
                                            <div class="float-start">
                                                <h5 class="mb-0 fs-16"><strong>ITem Name</strong></h5>
                                                <small class="text-muted">Rate : 30 , Qty : 20</small>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="float-end">
                                            <h4 class="fw-bold fs-18 mb-0 mt-2 text-primary">Rs 600</h4>
                                            </div>
                                        </div>
                                    </div>      
                                `;
                                    });

                                    $('#latest-sale').html(salesHtml);
                                } else {
                                    salesHtml = `
                                    <div class="clearfix row mb-3 " >
                            
                        
                                        <div class="col">
                                            <div class="float-start">
                                                <h5 class="mb-0 fs-16"><strong>ITem Not Found</strong></h5>
                                                
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="float-end">
                                            <h4 class="fw-bold fs-18 mb-0 mt-2 text-primary">00</h4>
                                            </div>
                                        </div>
                                    </div>      
                                `;
                                    $('#latest-sale').html(salesHtml);
                                }
                            },
                            error: function(xhr, status, error) {
                                $('#latest-sale').html('<p>An error occurred</p>');
                            }
                        });
                    } else {
                        $('#latest-sale').html('');
                    }
                });
            });
        </script>
    @endsection
