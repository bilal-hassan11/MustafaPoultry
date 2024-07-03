@extends('layouts.admin')
@section('content')

<div class="main-content app-content mt-5">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card ">
                  
                    <div class="card-body">
                    <center><h2 style="color:blue;  justify_content:center;"><span>  </span>CashFlow Report</h2></center>

                    
                    </div>
                </div>
            </div>
          
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0"> CashFlow Filters</h3>
                </div>
                <div class="card-body">
                <form action="" id="form">
                    <div class="row">
                        
                        <div class="col-md-8">
                            <label for="">Date</label>
                            <input type="date" class="form-control" name="to_date" id="to_date">
                        </div>
                        <div class="col-md-2 mt-3">
                        <input type="submit" class="btn btn-primary float-right mt-4">
                        <button class="btn btn-danger mt-4" id="pdf">PDF</button>
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
                    <h3 class="card-title mb-0">All CashFlow Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                            <th>Date</th>
                            <th colspan="1"> Narration </th>
                            <th> Cr </th>
                            <th> Dr </th>
                            <th> Balance </th>
                            <th> cr/dr </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    
                        @if(@$cashbook != "")
                        
                            
                                    @php
                                        $tot_balance = 0; $tot_payment=0;
                                        $tot_receipt=0; $tot_bal = 0;
                                    @endphp
                                    <tr class="text-dark">
                                        <td> {{ date('d-M-Y', strtotime($to_date)) }}</td>
                                        <td > Opening Balance </td>
                                        
                                        
                                            <?php $tot_balance += @$account_opening; ?>
                                            <?php $tot_payment += $account_opening ;?>
                                            
                                            <td><span class="waves-effect waves-light btn btn-primary-light">0</span></td>
                                            <td><span class="waves-effect waves-light btn btn-success-light">{{ @$account_opening }}</span></td>
                                            <td><span class="waves-effect waves-light btn btn-primary-light">{{ $tot_balance }}</span></td>
                                        
                                            <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
                                            
                                        
                                        <td></td>
                                    </tr>
                                    <?php $tot_balance = $tot_balance ; ?>
                                    @foreach($cashbook AS $c)
                                        
                                        <tr class="text-dark">
                                            <td> {{ date('d-M-Y', strtotime($c->entry_date)) }}</td>
                                            <td >{{ @$c->narration }}</td>
                                            <?php $tot_payment += $c->payment_ammount ;?>
                                            <td><span class="waves-effect waves-light btn btn-danger-light">{{ number_format(@$c->payment_ammount) }}</span></td>
                                            <?php $tot_receipt += $c->receipt_ammount;?>
                                            <td><span class="waves-effect waves-light btn btn-success-light">{{  number_format(@$c->receipt_ammount) }}</span></td>
                                            
                                            <?php $tot_balance += $c->receipt_ammount - $c->payment_ammount ;?>
                                            <td><span class="waves-effect waves-light btn btn-primary-light">{{ number_format($tot_balance) }}</span></td>
                                            
                                            @if($tot_balance > 0)
                                            <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
                                            <?php @$tot_bal += @$tot_balance; ?>
                                            @endif
                                            @if( @$tot_balance <= 0)
                                            <td><span class="waves-effect waves-light btn btn-info-light">cr</span></td>
                                                <?php @$tot_bal += @$tot_balance; ?>
                                            @endif
                                            
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    <tr class="text-dark">
                                        <th>{{ date('d-M-Y', strtotime($to_date)) }}</th>
                                        <th colspan="1"> Expense </th>
                                        <th><span class="waves-effect waves-light btn btn-primary-light">{{ @$day_exp }}</span>  </th>
                                        <th><span class="waves-effect waves-light btn btn-primary-light"> 0</span> </th>
                                        <th> <span class="waves-effect waves-light btn btn-primary-light">{{ @$tot_balance - @$day_exp }} </span> </th>
                                        <th> <span class="waves-effect waves-light btn btn-primary-light">dr</span> </th>
                                        
                                    </tr>
                                @endif
                            
                        
                        
                    </tbody>
                    <tfoot>
                        <tr class="text-dark">
                            <td>Total:</td>
                            <td colspan="1">-</td>
                            
                            <td><span class="waves-effect waves-light btn btn-danger-light">{{ number_format(@$debit) }}</span></td>
                            <td><span class="waves-effect waves-light btn btn-success-light">{{  number_format(@$credit) }}</span></td>
                            <?php @$account_opening + @$debit  ?>
                            
                            <td><span class="waves-effect waves-light btn btn-primary-light">{{  number_format(@$tot_balance - @$day_exp) }} Closing Balance</span></td>
                            
                            @if(@$tot_balance > 0)
                            <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
                            <?php @$tot_bal += @$tot_balance; ?>
                            @endif
                            @if( @$tot_balance <= 0)
                            <td><span class="waves-effect waves-light btn btn-info-light">cr</span></td>
                                <?php @$tot_bal += @$tot_balance; ?>
                            @endif
                            
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
    </div>
    <!-- CONTAINER END --> 
  </div>
</div>



@endsection

@section('page-scripts')
<script>
  $('#example54').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
</script>
<script>
    $('#pdf').click(function(event){
        event.preventDefault();
        var form_data = $('form').serialize();
        // console.log(form_data);
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.cashflowreportpdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "CashFlow-Report.pdf";
                link.click();
                return false;
            },
            error: function(blob){
                console.log(blob);
            }
        });
    });
</script>

@endsection