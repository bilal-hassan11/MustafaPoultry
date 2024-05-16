@extends('layouts.admin')
@section('content')
<div class="main-content app-content mt-5">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
        <!-- ROW-5 END -->
        
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0"> Purchase Feed Filters</h3>
                </div>
                <div class="card-body">
                <form action="" id="form">
                        <div class="row">
                            <div class="col-md-5">
                                <input type="date" class="form-control" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($from_date)) : date('Y-m-d') }}" name="from_date" id="from_date">
                            </div> 
                            <div class="col-md-5">
                                <input type="date" class="form-control" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($to_date)) : date('Y-m-d') }}" name="to_date" id="to_date">
                            </div>
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-primary ">
                                <button class="btn btn-danger" id="pdf">PDF</button>
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
                    <h3 class="card-title mb-0">All Item Stock Reports</h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                            <th>Item</th>
                            <th> Opening Stock </th>
                            <th> Purchase </th>
                            <th> Consume </th>
                            <th> Return </th>
                            <th>Available Stock </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr class="text-dark">
                                <td><span class="waves-effect waves-light btn btn-primary-light"> {{ @$item->name }} </span></td>
                                <td><span class="waves-effect waves-light btn btn-primary-light"> {{ @$item->opening_stock }} </span></td>
                                <td ><span class="waves-effect waves-light btn btn-info-light">{{ @$item->purchase_sum_quantity == 0 ? 0 : $item->purchase_sum_quantity + $item->opening_stock }}</span></td>
                                <td><span class="waves-effect waves-light btn btn-danger-light">{{ @$item->sale_feed_sum_quantity == 0 ? 0 : $item->sale_feed_sum_quantity }}</span></td>
                                <td><span class="waves-effect waves-light btn btn-danger-light">{{ @$item->return_feed_sum_quantity == 0 ? 0 : $item->return_feed_sum_quantity }}</span></td>
                                
                                <td><span class="waves-effect waves-light btn btn-danger-light">{{ (@$item->purchase_sum_quantity + @$item->return_feed_sum_quantity + @$item->opening_stock) - $item->sale_feed_sum_quantity }}</span></td>
                                
                            </tr>
                        @endforeach       
                    </tbody>
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

@endsection