@extends('layouts.admin')
@section('content')


<div class="row">
<style>
@page { size: auto;  margin: 0mm; }

</style>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                    <center>
                        <h1 class="page-title text-danger">Creditor Accounts Report</h1><br />
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
                <table id="example54" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                <thead>
                    <tr class="text-dark">
                        <th>Account Name</th>
                        <th>Receivable Balance </th>
                        <th> Payable Balance </th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                
                <?php @$tot_rec = 0; $tot_pay = 0;  ?>
                    @foreach($accounts AS $a)

                        
                            <tr class="text-dark">
                                <td><span class="waves-effect waves-light btn btn-primary-light"> {{ $a->name }}</span></td>
                                @if(@$a->opening_balance < 0)
                                <td ><span class="waves-effect waves-light btn btn-info-light">{{ abs(@$a->opening_balance) }}</span></td>
                                <?php @$tot_rec += abs(@$a->opening_balance) ; ?>
                                    <td><span class="waves-effect waves-light btn btn-danger-light">0</span></td>
                                @endif
                                @if(@$a->opening_balance >= 0)
                                    <td ><span class="waves-effect waves-light btn btn-info-light">0</span></td>
                                    <td><span class="waves-effect waves-light btn btn-danger-light">{{ abs(@$a->opening_balance) }}</span></td>
                                    <?php @$tot_pay += abs(@$a->opening_balance) ;  @$tot_rec += 0 ;?>
                                @endif
                                
                            </tr>
                            
                    @endforeach
                </tbody>
                
                <tfoot>
                    <tr class="text-dark">
                        <th>Total :</th>
                        <th><span class="waves-effect waves-light btn btn-success-light">{{@$tot_rec}}</span></th>
                        <th><span class="waves-effect waves-light btn btn-warning-light">{{@$tot_pay}}</span></th>
                    </tr>
                </tfoot>
               
            </table>
            </div>              
        </div>
        <!-- /.box-body -->
        </div>
        <!-- /.box -->          
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box ">
                        <center>
                        <h2 style="color:green;  justify_content:center;"><span> <i class="glyphicon glyphicon-user"></i> </span>{{ @$tot_rec > @$tot_pay ? "Receivable Ammount" : "Payble Ammount" }}</h2>
                    <h4>{{abs(@$tot_rec - @$tot_pay) }}</h4>
                    
                        </center>
                    
                       
                    </div>
                </div>
            </div>
                
            </div>
        </div>
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