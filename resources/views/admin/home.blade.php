@extends('layouts.admin')

@section('content')


<br /><br />
<div class="row">
                <!-- Total Active Item -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<span style="color:green; font-size:40px;"> <i class="fa-solid fa-truck"></i></span>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40">{{$active_item}} <small class="text-danger ps-10"><i class="mdi mdi-arrow-down text-danger"></i> -7.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-success btn-sm float-end">Active Item</a>
								</div>
								<div class="col-12">
									<div id="sparkline0" class="mt-40 mr-55 float-right"><a href="{{ route('admin.details.active_item')}}"> View details </a><i class=" ti-arrow-circle-right"></i></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total Item Consume Monthly  -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc NEO fs-40" title="ETH"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> {{$item_consumption}} Kg<small class="text-success ps-10"><i class="mdi mdi-arrow-up text-success"></i> +5.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-danger btn-sm float-end">Items Consume Monthly</a>
								</div>
								<div class="col-12">
									<div id="sparkline1" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total Inward Vehicle Daily -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
								<span style="color:green; font-size:40px;"> <i class="fa-solid fa-truck"></i></span>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> {{$inward_vehicle_daily}} <small class="text-danger ps-10"><i class="mdi mdi-arrow-down text-danger"></i> -4.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-success btn-sm float-end">Inward vehicle Daily</a>
								</div>
								<div class="col-12">
									<div id="sparkline2" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total Inward Vehicle Monthly -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc LTC fs-40" title="LTC"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> {{$inward_vehicle_monthly}} <small class="text-success ps-10"><i class="mdi mdi-arrow-up text-success"></i> +5.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-danger btn-sm float-end"><strong>Inward Vehicle Monthly</strong></a>
								</div>
								<div class="col-12">
									<div id="sparkline3" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total Outward Vehicle Daily -->
                <div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc NEO fs-40" title="ETH"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> {{$outward_vehicle_daily}}  <small class="text-success ps-10"><i class="mdi mdi-arrow-up text-success"></i> +5.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-danger btn-sm float-end">Outward Vehicle daily</a>
								</div>
								<div class="col-12">
									<div id="sparkline1" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total Outward Vehicle Monthly -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc DASH fs-40" title="DASH"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> {{$outward_vehicle_monthly}} <small class="text-danger ps-10"><i class="mdi mdi-arrow-down text-danger"></i> -4.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-success btn-sm float-end">Outward Vehicle Monthly</a>
								</div>
								<div class="col-12">
									<div id="sparkline2" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total Vehicle On waiting -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc LTC fs-40" title="LTC"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> {{$waiting_list}} <small class="text-success ps-10"><i class="mdi mdi-arrow-up text-success"></i> +5.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-danger btn-sm float-end"><strong>Waiting Vehicle</strong></a>
								</div>
								<div class="col-12">
									<div id="sparkline3" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>

                 <!-- Total Formulas of Items -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc BTC fs-40" title="BTC"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> {{$item_formulas}} <small class="text-danger ps-10"><i class="mdi mdi-arrow-down text-danger"></i> -7.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-success btn-sm float-end">Items Formulas</a>
								</div>
								<div class="col-12">
									<div id="sparkline0" class="mt-40 mr-55 float-right"> View details <i class=" ti-arrow-circle-right"></i></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total Item Quantity Purchase Monthly  -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc NEO fs-40" title="ETH"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> {{$item_purchase}} kg<small class="text-success ps-10"><i class="mdi mdi-arrow-up text-success"></i> +5.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-danger btn-sm float-end">Total Purchase Qty</a>
								</div>
								<div class="col-12">
									<div id="sparkline1" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total Item Purchases Monthly  -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc NEO fs-40" title="ETH"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40">{{$item_purchase_ammount}} <small class="text-success ps-10"><i class="mdi mdi-arrow-up text-success"></i> +5.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-danger btn-sm float-end">Total Purchase </a>
								</div>
								<div class="col-12">
									<div id="sparkline1" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total  Active Accounts -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc DASH fs-40" title="DASH"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> {{$active_accounts}} <small class="text-danger ps-10"><i class="mdi mdi-arrow-down text-danger"></i> -4.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-success btn-sm float-end">Active Accounts</a>
								</div>
								<div class="col-12">
									<div id="sparkline2" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total Staff/Users Monthly -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc LTC fs-40" title="LTC"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> {{$active_users}} <small class="text-success ps-10"><i class="mdi mdi-arrow-up text-success"></i> +5.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-danger btn-sm float-end"><strong>Total Users</strong></a>
								</div>
								<div class="col-12">
									<div id="sparkline3" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total  Orders Pending -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc DASH fs-40" title="DASH"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> 458 <small class="text-danger ps-10"><i class="mdi mdi-arrow-down text-danger"></i> -4.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-success btn-sm float-end">Pending Orders</a>
								</div>
								<div class="col-12">
									<div id="sparkline2" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- Total Orders Monthly -->
				<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
									<i class="cc LTC fs-40" title="LTC"></i>
								</div>
								<div class="col-6">
									<h4 class="counter text-center mb-0 fs-40"> 145 <small class="text-success ps-10"><i class="mdi mdi-arrow-up text-success"></i> +5.45%</small></h4>
								</div>
								<div class="col-3">
									<a href="#" class="btn btn-danger btn-sm float-end"><strong>Total Orders Monthly</strong></a>
								</div>
								<div class="col-12">
									<div id="sparkline3" class="mt-40"></div>
								</div>
							</div>
						</div>
					</div>
				</div>

                
			</div>
            <!-- Order List -->
            <!-- <div class="col-12">
				<div class="box">
				  <div class="box-body tickers-block">
					  <ul id="webticker-1">
						<li><i class="cc BTC"></i> BTC <span class="text-warning"> $11.039232</span> <i class="fa fa-angle-up text-success"></i><span class="text-success">1.11%</span></li> 
						<li><i class="cc ETH"></i> ETH <span class="text-warning"> $1.2792</span> <i class="fa fa-angle-up text-success"></i><span class="text-success">2.11%</span></li> 
						<li><i class="cc GAME"></i> GAME <span class="text-warning"> $11.039232</span> <i class="fa fa-angle-down text-danger"></i><span class="text-danger">-1.11%</span></li> 
						<li><i class="cc LBC"></i> LBC <span class="text-warning"> $0.588418</span> <i class="fa fa-angle-up text-success"></i><span class="text-success">3.01%</span></li> 
						<li><i class="cc NEO"></i> NEO <span class="text-warning"> $161.511</span> <i class="fa fa-angle-up text-success"></i><span class="text-success">2.10%</span></li> 
						<li><i class="cc STEEM"></i> STE <span class="text-warning"> $0.551955</span> <i class="fa fa-angle-up text-success"></i><span class="text-success">4.01%</span></li> 
						<li><i class="cc LTC"></i> LIT <span class="text-warning"> $177.80</span> <i class="fa fa-angle-down text-danger"></i><span class="text-danger">-0.11%</span></li> 
						<li><i class="cc NOTE"></i> NOTE <span class="text-warning"> $13.399</span> <i class="fa fa-angle-down text-danger"></i><span class="text-danger">-4.11%</span></li>
						<li><i class="cc MINT"></i> MINT <span class="text-warning"> $0.880694</span> <i class="fa fa-angle-down text-danger"></i><span class="text-danger">-0.31%</span></li> 
						<li><i class="cc IOTA"></i> IOT <span class="text-warning"> $2.555</span> <i class="fa fa-angle-up text-success"></i><span class="text-success">3.11%</span></li> 
						<li><i class="cc DASH"></i> DAS <span class="text-warning"> $769.22</span> <i class="fa fa-angle-up text-success"></i><span class="text-success">2.11%</span></li>   
					  </ul>
				  </div>
			  </div>
			</div> -->
            <div class="col-xl-6 ">
				  <div class="box">
					<div class="box-header">
					  <h4 class="box-title">Resent Purchases</h4>
					</div>
					<div class="box-body">
						<div class="table-responsive">
						  <table class="table">
                          <thead>							  	
                                <tr >
                                    <th >#</th>
                                    <th >Date</th>
                                    <th > Account Name </th>
                                    <th > Item Name </th>
                                    <th > Rate</th>
                                    <th > other charges </th>
                                    
                                </tr>
							</thead>
							<tbody class="text-fade">
                            
                                @foreach($total_purchases AS $p)
                                    <tr>
                                        <td >{{ $loop->iteration }}</td>
                                        <td >{{ date('d-m-Y', strtotime($p->date)) }}</td>
                                        <td ><span class="badge bg-info">{{ @$p->account->name }}</span></td>
                                        <td >{{ $p->item->name }}</td>
                                        <td ><span class="badge bg-danger">{{ $p->bag_rate }}</span></td>
                                        <td >{{ $p->others_charges }}</td>
                                        
                                    </tr>
                                @endforeach
                                
								
							  </tbody>
							</table>
						</div>
					</div>
				  </div>
				</div>
				<div class="col-xl-6 ">
				  <div class="box">
					<div class="box-header">
					  <h4 class="box-title">Recent Sales Orders</h4>
					</div>
					<div class="box-body">
						<div class="table-responsive">
						  <table class="table">
							<thead>							  	
                                <tr >
                                    <th >#</th>
                                    <th >Date</th>
                                    <th > Account Name </th>
                                    <th > Item Name </th>
                                    <th > No of Begs </th>
                                    <th > Fare </th>
                                    
                                </tr>
							</thead>
							<tbody class="text-fade">
                            
                                @foreach($total_sales AS $s)
                                    <tr>
                                        <td >{{ $loop->iteration }}</td>
                                        <td >{{ date('d-m-Y', strtotime($s->date)) }}</td>
                                        <td ><span class="badge bg-info">{{ $s->account->name }}</span></td>
                                        <td >{{ @$s->item->name }}</td>
                                        <td ><span class="badge bg-danger">{{ $s->no_of_bags }}</span></td>
                                        <td >{{ $s->fare }}</td>
                                        
                                    </tr>
                                @endforeach
                                
								
							  </tbody>
							</table>
						</div>
					</div>
					<!-- /.box-body -->
				  </div>
				  <!-- /.box -->

				</div>


<div id="hightChart">

</div>
<div id="consumption_chart">

</div>
<!-- start page title -->
{{-- <div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right d-none">
                <form class="form-inline">
                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control border-white" id="dash-daterange">
                            <div class="input-group-append">
                                <span class="input-group-text bg-blue border-blue text-white">
                                    <i class="mdi mdi-calendar-range font-13"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="javascript: void(0);" class="btn btn-blue btn-sm ml-2">
                        <i class="mdi mdi-autorenew"></i>
                    </a>
                    <a href="javascript: void(0);" class="btn btn-blue btn-sm ml-1">
                        <i class="mdi mdi-filter-variant"></i>
                    </a>
                </form>
            </div>
            <div class="text-center">
                <h1 class="page-title mb-0 mt-2" style="line-height: normal; font-size: 35px;">Minister's Monitoring Unit</h1>
                <h4>Department of Health, Government of Sindh</h4>
            </div>
        </div>
    </div>
</div> --}}
<!-- end page title -->

{{-- <div class="row">
    <h3 class="page-title" style="font-size: 18px;">STATISTICS FOR THE MONTH</h3>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <p class="mb-3">Total Facilities Monitored</p>
                    <div class="col-6">
                        <h3 class="text-dark mt-1"><span data-plugin="counterup">{{number_format($grand_total_visit ?? 0)}}</span></h3>
                    </div>
                    <div class="col-6 text-end">
                        <i class="fas fa-chart-bar avatar-title text-primary" style="font-size: 25px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <p class="mb-3">Facilities Monitored Today</p>
                    <div class="col-6">
                        <h3 class="text-dark mt-1"><span data-plugin="counterup">{{number_format($total_visit_today ?? 0)}}</span></h3>
                    </div>
                    <div class="col-6 text-end">
                        <i class="fas fa-chart-bar avatar-title text-danger" style="font-size: 25px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <p class="mb-3">Facilities Monitored This Week</p>
                    <div class="col-6">
                        <h3 class="text-dark mt-1"><span data-plugin="counterup">{{number_format($total_visit_week ?? 0)}}</span></h3>
                    </div>
                    <div class="col-6 text-end">
                        <i class="fas fa-chart-bar avatar-title text-primary" style="font-size: 25px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <p class="mb-3">Fully Functional Facilities</p>
                    <div class="col-6">
                        <h3 class="text-dark mt-1"><span data-plugin="counterup"></span>%</h3>
                    </div>
                    <div class="col-6 text-end">
                        <i class="fas fa-chart-bar avatar-title text-danger" style="font-size: 25px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <p class="mb-3">BEmONC Functionality</p>
                    <div class="col-6">
                        <h3 class="text-dark mt-1"><span data-plugin="counterup"></span>%</h3>
                    </div>
                    <div class="col-6 text-end">
                        <i class="fas fa-chart-bar avatar-title text-primary" style="font-size: 25px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <p class="mb-3">ANC/PNC Functionality</p>
                    <div class="col-6">
                        <h3 class="text-dark mt-1"><span data-plugin="counterup">{{number_format($anc_percentage,2)}}</span>%</h3>
                    </div>
                    <div class="col-6 text-end">
                        <i class="fas fa-chart-bar avatar-title text-danger" style="font-size: 25px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <p class="mb-3">Medicine Availablity</p>
                    <div class="col-6">
                        <h3 class="text-dark mt-1"><span data-plugin="counterup"></span>%</h3>
                    </div>
                    <div class="col-6 text-end">
                        <i class="fas fa-chart-bar avatar-title text-primary" style="font-size: 25px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <p class="mb-3">Contraceptive Availablity</p>
                    <div class="col-6">
                        <h3 class="text-dark mt-1"><span data-plugin="counterup"></span>%</h3>
                    </div>
                    <div class="col-6 text-end">
                        <i class="fas fa-chart-bar avatar-title text-primary" style="font-size: 25px"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

{{-- <div class="row mt-2">
    <div class="col-md-6">
        <h3 class="page-title mb-3" style="font-size: 18px;">VISIT COVERAGE</h3>
        <div id="visit_cov" style="width:100%; height:300px;"></div>
    </div>
    <div class="col-md-6">
        <h3 class="page-title mb-3" style="font-size: 18px;">FULLY FUNCTIONAL FACILITIES</h3>
        <div id="fun_facil" style="width: 100%; height: 300px;" ></div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-12">
        <h3 class="page-title mb-3" style="font-size: 18px;">CLOSED FACILITIES</h3>
        <div id="close_facil" style="width:100%; height:300px;"></div>
    </div>
</div> --}}
<br />
<div id="sale_chart" class="chart"></div>

<div class="map_canvas">
  
            <canvas id="myChart" width="auto" height="100"></canvas>
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