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
                <div class="card-header">
                    <h3 class="card-title mb-0"> Filters</h3>
                </div>
                <div class="card-body">
                <form action="{{ route('admin.reports.murghi_item_wise_stock_report') }}" method="GET">
                              @csrf
                          <div class="row">
                            <div class="col-md-4">
                                <select class="form-control select2" name="item_id" id="item_id" required>
                                    <option value="" >----Select Item----</option>
                                @foreach($items AS $i)
                                    <option value="{{ $i->hashid }}" >{{ $i->name }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                              <input type="date" class="form-control" name="from_date" value="{{ (isset($is_update)) ? date('d-m-Y', strtotime($from_date)) : date('d-m-Y') }}" id="from_date" required>
                            </div>
                            <div class="col-md-3">
                              <input type="date" class="form-control" name="to_date" value="{{ (isset($is_update)) ? date('d-m-Y', strtotime($to_date)) : date('d-m-Y') }}" id="to_date" required>
                            </div>
                            
                            <div class="col-md-2">
                              <input type="submit" class="btn btn-primary" value="Search">
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
                  
                    <div class="card-body">
                        <center><h1 class="page-title text-primary">Murghi Item Stock Report</h1><br /></center>
                    
                    </div>
                </div>
            </div>
          
        </div>
        @if($item_name == true)
            <?php $stock = 0; ?>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card ">
                    
                        <div class="card-body">
                        <center>

                        <h2 style="color:green;  justify_content:center;"><span> <i class="glyphicon glyphicon-gift"></i> </span>{{@$item[0]['name']}}</h2>
                        <h4>From {{date('d-M-Y', strtotime($from_date))}} to {{date('d-M-Y', strtotime($to_date))}}</h4>
                        </center>
                        
                        <?php $stock += @$item_opening; ?>
                        <h4 style="color:red; float:right;">Opening Stock : {{number_format(@$item_opening)}}</h4>
                        
                        </div>
                    </div>
                </div>
            
            </div>
            <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card ">
                    <div class="card-header">
                        <h3 class="card-title mb-0">All Purchase Murghi Detail</h3>
                    </div>
                    <div class="card-body">
                    <table id="example" class="text-fade table table-bordered" style="width:100%">
                        <thead>
                            <tr class="text-dark">
                                <th>ID</th>
                                <th>Date</th>
                                
                                <th>Account Name</th>
                                <th>Description</th>
                                <th>Purchase Quantity</th>
                                <th>Current Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                            <?php @$tot_pf_val = 0; ?>
                                    
                            @foreach($purchase_murghi AS $pf)
                                <tr class="text-dark">
                                    <th>{{ @$pf->id }}</th>
                                    <th>{{date('d-M-Y', strtotime(@$pf->date))}}</th>
                                    <td> {{ @$pf->account->name }}</td>
                                    <td><span class="waves-effect waves-light btn btn-danger-light"> Item Name : {{ @$pf->item->name }} , Rate : {{ @$pf->rate }} , Weight : {{ @$pf->final_weight }} kg </span> </td>
                                    <?php @$tot_pf_val += @$pf->final_weight; ?>
                                    <?php $stock += @$pf->final_weight;  ?>
                                    <td> {{ @$pf->final_weight }}</td>
                                    <td>{{@$stock}}</td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                        <tr class="text-dark">
                            <th colspan="4">Total :</th>
                            <th>{{ @$tot_pf_val }}</th>
                            <th> 0 </th>
                        </tr>
                    </table>
                    
                </div>
                </div>
            </div>
            <!-- COL END --> 
            </div>
            <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card ">
                    <div class="card-header">
                        <h3 class="card-title mb-0">All sale Murghi Detail</h3>
                    </div>
                    <div class="card-body">
                    <table id="example" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                            <tr class="text-dark">
                                <th>ID</th>
                                <th>Date</th>
                                <th>Account Name</th>
                                <th>Description</th>
                                <th>Sale Quantity</th>
                                <th>Current Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                            <?php @$tot_sf_val = 0; ?>
                            @foreach($sale_murghi AS $sf)
                                <tr class="text-dark">
                                    <th>{{ @$sf->id }}</th>
                                    <th>{{date('d-M-Y', strtotime(@$sf->date))}}</th>
                                    <td> {{ @$sf->account->name }}</td>
                                    <td><span class="waves-effect waves-light btn btn-danger-light"> Item Name : {{ @$sf->item->name }} , Rate : {{ @$sf->rate }} , Weight : {{ @$sf->quantity }} kg </span> </td>
                                    <td> {{@$sf->quantity}}</td>
                                    <?php $stock -= @$sf->quantity;  ?>
                                    <?php @$tot_sf_val += @$sf->quantity; ?>
                                    <td> {{ @$stock }}</td>
                                    
                                    
                                </tr>
                            @endforeach
                        </tbody>
                        <tr class="text-dark">
                            <th colspan="4">Total :</th>
                            
                            <th>{{ @$tot_sf_val }}</th>
                            <th>0</th>
                        </tr>
                    </table>
                    
                </div>
                </div>
            </div>
            <!-- COL END --> 
            </div>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card ">
                    
                        <div class="card-body">
                        <h4 style="color:blue; float:right;">Closing Stock : {{number_format(@$stock)}} </h4>
                        
                        </div>
                    </div>
                </div>
            
            </div>
        @endif
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
            url: "{{ route('admin.reports.DayBookPdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "DayBook-Report.pdf";
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