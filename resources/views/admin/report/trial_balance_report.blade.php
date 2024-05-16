@extends('layouts.admin')
@section('content')


<div class="row">
<style>
@page { size: auto;  margin: 0mm; }

</style>
<!-- <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                   <div class="col">
					 <div class="card radius-10 border-start border-0 border-4 border-info">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<p class="mb-0 text-secondary">Total Orders</p>
									<h4 class="my-1 text-info">4805</h4>
									<p class="mb-0 font-13">+2.5% from last week</p>
								</div>
								<div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-cart'></i>
								</div>
							</div>
						</div>
					 </div>
				   </div>
				   <div class="col">
					<div class="card radius-10 border-start border-0 border-4 border-danger">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Total Revenue</p>
								   <h4 class="my-1 text-danger">$84,245</h4>
								   <p class="mb-0 font-13">+5.4% from last week</p>
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-wallet'></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div>
				  <div class="col">
					<div class="card radius-10 border-start border-0 border-4 border-success">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Bounce Rate</p>
								   <h4 class="my-1 text-success">34.6%</h4>
								   <p class="mb-0 font-13">-4.5% from last week</p>
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-bar-chart-alt-2' ></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div>
				  <div class="col">
					<div class="card radius-10 border-start border-0 border-4 border-warning">
					   <div class="card-body">
						   <div class="d-flex align-items-center">
							   <div>
								   <p class="mb-0 text-secondary">Total Customers</p>
								   <h4 class="my-1 text-warning">{{ @$tot_customer }}</h4>
								   <p class="mb-0 font-13">+8.4% from last week</p>
							   </div>
							   <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-group'></i>
							   </div>
						   </div>
					   </div>
					</div>
				  </div> 
				</div>end row -->

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                    <center>
                        <h1 class="page-title text-info">Trial Balance Report</h1><br />
                    </center>
                    </div>
                </div>
            </div>
                
            </div>
        </div>
    </div>
    

    <div class="col-12">
        <div class="box">
        <div class="box-header with-border">
            <h4 class="box-title"></h4>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table id="example54" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w 100">
                <thead>
                    <tr class="text-dark">
                        <th>Grand Parent </th>
                        <th>Debit </th>
                        <th> Credit </th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-dark">
                        <td><span class="waves-effect waves-light btn btn-primary-light"> Assets </span></td>
                        <td ><span class="waves-effect waves-light btn btn-info-light">{{ abs(@$assets) }}</span></td>
                        <td><span class="waves-effect waves-light btn btn-danger-light">0</span></td>
                    </tr>
                    <tr class="text-dark">
                        <td><span class="waves-effect waves-light btn btn-primary-light"> Expenses </span></td>
                        <td ><span class="waves-effect waves-light btn btn-info-light">{{ abs(@$expense) }}</span></td>
                        <td><span class="waves-effect waves-light btn btn-danger-light">0</span></td>
                    </tr>
                    <tr class="text-dark">
                        <td><span class="waves-effect waves-light btn btn-primary-light"> Liabilities </span></td>
                        <td ><span class="waves-effect waves-light btn btn-info-light">0</span></td>
                        <td><span class="waves-effect waves-light btn btn-danger-light">{{ abs(@$liabilities) }}</span></td>
                    </tr>
                    <tr class="text-dark">
                        <td><span class="waves-effect waves-light btn btn-primary-light"> Revenue </span></td>
                        <td ><span class="waves-effect waves-light btn btn-info-light">0</span></td>
                        <td><span class="waves-effect waves-light btn btn-danger-light">{{ abs(@$revenue) }}</span></td>
                    </tr>
                    <tr class="text-dark">
                        <td><span class="waves-effect waves-light btn btn-primary-light"> Propritorship </span></td>
                        <td ><span class="waves-effect waves-light btn btn-info-light">0</span></td>
                        <td><span class="waves-effect waves-light btn btn-danger-light">{{ abs(@$propritorship) }}</span></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Total</td>
                        <td><span class="waves-effect waves-light btn btn-success-light">{{abs(@$assets) + abs(@$expense) }}</span></td>
                        <td><span class="waves-effect waves-light btn btn-warning-light">{{ abs(@$liabilities) + abs(@$revenue) + abs(@$propritorship) }}</span></td>
                    </tr>
                </tfoot>
               
            </table>
            </div>              
        </div>
        <!-- /.box-body -->
        </div>
        <!-- /.box -->          
    </div>
    
    
</div>
@endsection

@section('page-scripts')

<script>
    $('#pdf').click(function(event){
        event.preventDefault();
        var form_data = $('form').serialize();
        // console.log(form_data);
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.account_pdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "account_report.pdf";
                link.click();
                return false;
            },
            error: function(blob){
                console.log(blob);
            }
        });
    });
</script>
<script>
  $('#example54').DataTable( {
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
</script>
@endsection