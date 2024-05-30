@extends('layouts.admin')
@section('content')
   

<div class="main-content app-content mt-5">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
       
        <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 --> 
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card ">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Expense Report</h3>
                    </div>
                    <div class="card-body">
                    
                        <div class="card-block">
                            <div class="item_row">
                            
                                <form action="" id="form">
                                    <div class="row">
                                        
                                        <div class="col-md-8">
                                            <label for="">To Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date">
                                        </div>
                                        <div class="col-md-2">
                                        <input type="submit" class="btn btn-primary float-right mt-4">
                                        <button class="btn btn-danger mt-4" id="pdf">PDF</button>
                                        </div>

                                    </div>
                                    
                                </form>
                            <br /><br />
                            </div>

                        </div>
                    </div>
                </div>
            </div>
          <!-- COL END --> 
        </div>
        <!-- ROW-5 END -->
        
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Expenses Detail</h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                            <th>Date</th>
                            <th colspan="1"> Category </th>
                            <th> Ammount </th>
                            <th> Remarks </th>
                            
                            
                        </tr>
                    </thead>
                    <tbody>
                        @if(@$day_exp != "")
                            @php $tot_balance = 0;  @endphp
                            @foreach($day_exp AS $c)
                                
                                <tr class="text-dark">
                                    <td> {{ date('d-M-Y', strtotime($c->date)) }}</td>
                                    <td ><span class="waves-effect waves-light btn btn-success-light">{{ @$c->category->name }}</span></td>
                                    <?php $tot_balance += @$c->ammount ; ?>
                                    <td ><span class="waves-effect waves-light btn btn-danger-light">{{ @$c->ammount }}</span></td>
                                    <td >{{ @$c->remarks }}</td>
                                </tr>
                            @endforeach
                                    
                        @endif
                        
                    </tbody>
                    <tfoot>
                        <tr class="text-dark">
                            <td>Total:</td>
                            <td>-</td>
                            <td><span class="waves-effect waves-light btn btn-info-light">{{@$tot_balance}}</span></td>
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
       
        
       
       
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.expensereportpdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                 
                link.href = window.URL.createObjectURL(blob);
                link.download = "Expense.pdf";
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