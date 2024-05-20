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
                    <div class="card custom-card overflow-hidden"> <div class="card-body"> 
                        <div class="row"> <div class="col"> <h6 class="fw-normal fs-14">Total Sales</h6> <h3 class="mb-2 number-font fs-24">34,516</h3> <p class="text-muted mb-0"> <span class="text-primary"> <i class="ri-arrow-up-s-line bg-primary text-white rounded-circle fs-13 p-0 fw-semibold align-bottom"></i> 3%</span> last month </p></div> 
                            <div class="col col-auto mt-2"> <div class="counter-icon bg-primary-gradient box-shadow-primary rounded-circle ms-auto mb-0"> <i class="fe fe-trending-up mb-5 "></i> 
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
                                <div class="col"> <h6 class="fw-normal fs-14">Total Leads</h6> <h3 class="mb-2 number-font fs-24">56,992</h3> <p class="text-muted mb-0"> <span class="text-secondary"> <i class="ri-arrow-up-s-line bg-secondary text-white rounded-circle fs-13 p-0 fw-semibold align-bottom"></i> 3%</span> last month </p></div> 
                                    <div class="col col-auto mt-2"> 
                                        <div class="counter-icon bg-danger-gradient box-shadow-danger rounded-circle  ms-auto mb-0"> <i class="ri-rocket-line mb-5  "></i>
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
                                                <p class="text-muted mb-0"> <span class="text-success"> <i class="ri-arrow-down-s-line bg-primary text-white rounded-circle fs-13 p-0 fw-semibold align-bottom"></i> 0.5%</span> last month </p>
                                            </div> 
                                            <div class="col col-auto mt-2"> 
                                                <div class="counter-icon bg-secondary-gradient box-shadow-secondary rounded-circle ms-auto mb-0"> 
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
                                                 <p class="text-muted mb-0"> <span class="text-danger"> <i class="ri-arrow-down-s-line bg-danger text-white rounded-circle fs-13 p-0 fw-semibold align-bottom"></i> 0.2%</span> last month </p>
                                                </div> 
                                                <div class="col col-auto mt-2"> 
                                                    <div class="counter-icon bg-success-gradient box-shadow-success rounded-circle  ms-auto mb-0"> 
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
                    <h4 class="text-dark counter mt-0 mb-3 number-font">{{@$tot_purchase_feed_ammount}}</h4>
                    <div class="progress h-1 mt-0 mb-2">
                    
                    <div
                        class="progress-bar progress-bar-striped bg-primary w-70"
                        role="progressbar"
                    >
                        
                    </div>
                    </div>
                    <div class="row mt-4">
                    
                    <div class="col text-center">
                        
                        <span class="text-muted"> Bags </span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">{{ @$tot_purchase_feed_begs }}</h4>
                    </div>
                    <div class="col text-center">
                        
                        <span class="text-muted">Ammount</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font2">{{@$tot_purchase_feed_ammount}}</h4>
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
                    <h4 class="text-dark counter mt-0 mb-3 number-font">{{@$tot_sale_feed_ammount}}</h4>
                    <div class="progress h-1 mt-0 mb-2">
                    
                    <div
                        class="progress-bar progress-bar-striped  bg-secondary w-50"
                        role="progressbar"
                    ></div>
                    </div>
                    <div class="row mt-4">
                    
                    
                    <div class="col text-center">
                        
                        <span class="text-muted">Bags</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">{{ @$tot_sale_feed_begs }}</h4>
                    </div>
                    <div class="col text-center">
                        
                        <span class="text-muted">Ammount</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">{{@$tot_sale_feed_ammount}}</h4>
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
                    Total Feed Return 
                    </h6>
                    <h4 class="text-dark counter mt-0 mb-3 number-font">{{@$tot_Return_feed_ammount}}</h4>
                    <div class="progress h-1 mt-0 mb-2">
                    
                    <div
                        class="progress-bar progress-bar-striped  bg-success w-60"
                        role="progressbar"
                    >
                        
                    </div>
                    </div>
                    <div class="row mt-4">
                    
                    <div class="col text-center">
                        
                        <span class="text-muted">Bags</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">{{@$tot_Return_feed_begs}}</h4>
                    </div>
                    <div class="col text-center">
                        
                        <span class="text-muted">Ammount</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">{{@$tot_Return_feed_ammount}}</h4>
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
                    Total Profit
                    </h6>
                    <h4 class="text-dark counter mt-0 mb-3 number-font">{{ (@$tot_purchase_feed_ammount + @$tot_Return_feed_ammount) - @$tot_sale_feed_ammount }}</h4>
                    <div class="progress h-1 mt-0 mb-2">
                    
                    <div
                        class="progress-bar progress-bar-striped progress-bar-animated bg-info w-40"
                        role="progressbar"
                    ></div>
                    </div>
                    <div class="row mt-4">
                    
                    <div class="col text-center">
                        
                        <span class="text-muted">Weekly</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">35</h4>
                    </div>
                    <div class="col text-center">
                        
                        <span class="text-muted">Monthly</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">56</h4>
                    </div>
                    <div class="col text-center">
                        
                        <span class="text-muted">Total</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">91</h4>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
    
            <div class="col-xl-3 col-md-12 col-lg-6">
                
                <div class="card custom-card">
                
                <div class="card-body text-center">
                    
                    <h6 class="">
                    <span class="text-primary">
                        <i class="fe fe-file-text mx-2 fs-20 text-primary-shadow  align-middle"></i>
                    </span>
                    Total Chick Purchase
                    </h6>
                    <h4 class="text-dark counter mt-0 mb-3 number-font">{{@$tot_purchase_chick_ammount}}</h4>
                    <div class="progress h-1 mt-0 mb-2">
                    
                    <div
                        class="progress-bar progress-bar-striped bg-primary w-70"
                        role="progressbar"
                    >
                        
                    </div>
                    </div>
                    <div class="row mt-4">
                    
                    <div class="col text-center">
                        
                        <span class="text-muted"> Chicks </span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">{{ @$tot_purchase_chick_qty }}</h4>
                    </div>
                    <div class="col text-center">
                        
                        <span class="text-muted">Ammount</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font2">{{@$tot_purchase_chick_ammount}}</h4>
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
                    <h4 class="text-dark counter mt-0 mb-3 number-font">{{@$tot_sale_feed_ammount}}</h4>
                    <div class="progress h-1 mt-0 mb-2">
                    
                    <div
                        class="progress-bar progress-bar-striped  bg-secondary w-50"
                        role="progressbar"
                    ></div>
                    </div>
                    <div class="row mt-4">
                    
                    
                    <div class="col text-center">
                        
                        <span class="text-muted">Bags</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">{{ @$tot_sale_feed_begs }}</h4>
                    </div>
                    <div class="col text-center">
                        
                        <span class="text-muted">Ammount</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">{{@$tot_sale_feed_ammount}}</h4>
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
                    Total Feed Return 
                    </h6>
                    <h4 class="text-dark counter mt-0 mb-3 number-font">{{@$tot_Return_feed_ammount}}</h4>
                    <div class="progress h-1 mt-0 mb-2">
                    
                    <div
                        class="progress-bar progress-bar-striped  bg-success w-60"
                        role="progressbar"
                    >
                        
                    </div>
                    </div>
                    <div class="row mt-4">
                    
                    <div class="col text-center">
                        
                        <span class="text-muted">Bags</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">{{@$tot_Return_feed_begs}}</h4>
                    </div>
                    <div class="col text-center">
                        
                        <span class="text-muted">Ammount</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">{{@$tot_Return_feed_ammount}}</h4>
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
                    Total Profit
                    </h6>
                    <h4 class="text-dark counter mt-0 mb-3 number-font">{{ (@$tot_purchase_feed_ammount + @$tot_Return_feed_ammount) - @$tot_sale_feed_ammount }}</h4>
                    <div class="progress h-1 mt-0 mb-2">
                    
                    <div
                        class="progress-bar progress-bar-striped progress-bar-animated bg-info w-40"
                        role="progressbar"
                    ></div>
                    </div>
                    <div class="row mt-4">
                    
                    <div class="col text-center">
                        
                        <span class="text-muted">Weekly</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">35</h4>
                    </div>
                    <div class="col text-center">
                        
                        <span class="text-muted">Monthly</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">56</h4>
                    </div>
                    <div class="col text-center">
                        
                        <span class="text-muted">Total</span>
                        <h4 class="fw-normal mt-2 mb-0 number-font1">91</h4>
                    </div>
                    </div>
                </div>
                </div>
            </div>
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
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels[0]) ?>,
        datasets: [{
            label: '',
            data: <?php echo json_encode($prices[0]); ?>,
            backgroundColor: [
                'rgba(31, 58, 147, 1)',
                'rgba(37, 116, 169, 1)',
                'rgba(92, 151, 191, 1)',
                'rgb(200, 247, 197)',
                'rgb(77, 175, 124)',
                'rgb(30, 130, 76)'
            ],
            borderColor: [
                'rgba(31, 58, 147, 1)',
                'rgba(37, 116, 169, 1)',
                'rgba(92, 151, 191, 1)',
                'rgb(200, 247, 197)',
                'rgb(77, 175, 124)',
                'rgb(30, 130, 76)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                max: 100,
                min: 0,
                ticks: {
                    stepSize: 40
                }
            }
        },
        plugins: {
            title: {
                display: false,
                text: 'Custom Chart Title'
            },
            legend: {
                display: false,
            }
        }
    }
});
</script>


<script>
  var sale_count = <?php echo json_encode($sale) ?>;
  var total_bags = <?php echo json_encode($sale_bags) ?>;
  var consumption_count = <?php echo json_encode($consumption) ?>;
  var consumption_qty = <?php echo json_encode($consumption_qty) ?>
  
  Highcharts.chart('hightChart', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Monthly sales graph'
    },
    subtitle: {
        text: 'Source: ' +
            '<a href="https://en.wikipedia.org/wiki/List_of_cities_by_average_temperature" ' +
            'target="_blank">Wikipedia.com</a>'
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'Total sale and no of bags'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: true
        }
    },
    series: [{
        name: 'Total Sale',
        data: sale_count,
        color: "#FFA500"
    },{
        name: 'Total no of bags',
        data: total_bags
    }]
    
});
Highcharts.chart('consumption_chart', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Monthly Consumption graph'
    },
    subtitle: {
        text: 'Source: ' +
            '<a href="https://en.wikipedia.org/wiki/List_of_cities_by_average_temperature" ' +
            'target="_blank">Wikipedia.com</a>'
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'Total consumption and stock'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: true
        }
    },
    series: [{
        name: 'Total Consumption',
        data: consumption_count,
        color: "#FFA500"
    },{
        name: 'Total Stock',
        data: consumption_qty
    }]
    
});
    
</script>
@endsection