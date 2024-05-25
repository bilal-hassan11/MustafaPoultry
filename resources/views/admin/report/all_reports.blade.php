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
                    <h3 class="card-title mb-0">{{@$title}} Filters</h3>
                </div>
                <div class="card-body">
                <form action="{{ route('admin.reports.all_reports_request') }}" id="form">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-control 
                            select2" name="account_id" id="account_id" >
                                <option value="">Select Account</option>
                                @foreach($acounts AS $ac)
                                    <option value="{{ $ac->hashid }}" @if(@$account_name[0]->id == $ac->id) selected @endif >{{ $ac->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control select2" name="item_id" id="item_id">
                                <option value="" >Select Item </option>
                            @foreach($items AS $i)
                                <option value="{{ $i->hashid }}" @if(@$item_name[0]->id == $i->id) selected @endif >{{ $i->name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($from_date)) : date('Y-m-d') }}" name="from_date" id="from_date">
                        </div> 
                        <div class="col-md-2">
                            <input type="date" class="form-control" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($to_date)) : date('Y-m-d') }}" name="to_date" id="to_date">
                        </div>
                        <div class="col-md-2">
                            <input type="submit" class="btn btn-primary ">
                            <input type="hidden" value="{{$id}}" name="id" class="btn btn-primary ">
                            
                            <button class="btn btn-danger" id="pdf">PDF</button>
                        </div>
                    </div>
                    
                    <!-- <button class="btn btn-warning mt-2" id="print">Print</button> -->
                </form>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        @if(isset($from_date))
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-body">
                <center>
                        <h2 style="color:green;  justify_content:center;"><span> <i class="glyphicon glyphicon-gift"></i> </span>{{ $title }}</h2>
                    <h4>From {{date('d-M-Y', strtotime($from_date))}} to {{date('d-M-Y', strtotime($to_date))}}</h4>
                        </center>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        @endif
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All {{@$title}} </h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                <thead>
                        <tr class="text-dark">
                            <th> Date  </th>
                            <th> Account Name </th>
                            <th> Item Name </th>
                            <th> Rate </th>
                            <th> Quantity </th>
                            <th> Net Value </th>
                            <th> Action </th>

                         </tr>
                    </thead>
                    <tbody>
                        @if( @$all_reports_values != "")
                            @foreach(@$all_reports_values AS $all)
                                <tr class="text-dark">
                                  <td><span class="waves-effect waves-light btn btn-rounded btn-primary-light">{{ date('d-m-y', strtotime(@$all->date)) }}</span></td>
                                  <td ><span class="waves-effect waves-light btn btn-outline btn-success">{{ @$all->account->name }}</span></td>
                                  <td><span class="waves-effect waves-light btn btn-outline btn-danger">{{ @$all->item->name }}</span></td>
                                  <td>{{    number_format(@$all->net_amount / @$all->quantity ,2) }}</td>
                                  <td>{{ @$all->quantity }}</td>
                                  <td ><span class="waves-effect waves-light btn btn-outline btn-success">{{ @$all->net_amount }}</span></td>
                                  <td>
                                        <button class="btn btn-outline-info  rounded-pill btn-wave" type="button" >
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <button class="btn btn-outline-info  rounded-pill btn-wave" type="button" >
                                            <i class="ri-download-2-line"></i>
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        @endif  
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
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.all_reports_pdf') }}",
            data: form_data,
           
            success: function(response){
                
            },
            error: function(blob){
                console.log(blob);
            }
        });
    });

   
</script>
@endsection