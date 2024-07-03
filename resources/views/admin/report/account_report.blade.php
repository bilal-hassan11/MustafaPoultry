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
                    <form action="" id="form">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-dark">Account</label>
                                <select class="form-control select2" name="parent_id" id="parent_id">
                                    <option value="" >Select Account </option>
                                @foreach($accounts AS $account)
                                    <option value="{{ $account->hashid }}" >{{ $account->name }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">From Date</label>
                                <input type="date" class="form-control" name="from_date" id="from_date">
                            </div>
                            <div class="col-md-2">
                                <label for="">To Date</label>
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
        @if(isset($from_date))
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-body">
                    <center>
                        <h2 style="color:green;  justify_content:center;"><span> <i class="glyphicon glyphicon-user"></i> </span>{{@$party_name[0]['name']}}</h2>
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
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                            <th>Date</th>
                            <th>Type</th>
                            <th colspan="1"> Description </th>
                            <th> Dr </th>
                            <th> Cr </th>
                            <th> Balance </th>
                            <th> cr/dr </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        
                        @if(@$account_ledger != "")
                        
                            
                                @php
                                    $tot_balance = 0; $tot_deb=0;
                                    $tot_credit=0; $tot_bal = 0;
                                @endphp
                                <tr class="text-dark">
                                    <td> {{ date('d-M-Y', strtotime($from_date)) }}</td>
                                    <td > - </td>
                                    <td > Opening Balance </td>
                                    @if(@$account_opening[0]->account_nature == "credit")
                                        <?php $tot_balance -= $account_opening[0]->opening_balance;?>
                                        <td><span class="waves-effect waves-light btn btn-primary-light">0</span></td>
                                        <td><span class="waves-effect waves-light btn btn-danger-light"> {{ $account_opening[0]->opening_balance }}</span></td>
                                        
                                        <td><span class="waves-effect waves-light btn btn-primary-light">{{ $account_opening[0]->opening_balance }}</span></td>
                                    
                                    @endif
                                    @if(@$account_opening[0]->account_nature == "debit")
                                        <?php $tot_balance += $account_opening[0]->opening_balance ?>
                                        <td><span class="waves-effect waves-light btn btn-success-light">{{ $account_opening[0]->opening_balance }}</span></td>
                                        <td><span class="waves-effect waves-light btn btn-primary-light">0</span></td>
                                        
                                        <td><span class="waves-effect waves-light btn btn-primary-light">{{ $tot_balance }}</span></td>
                                    @endif
                                    @if(@$account_opening[0]->account_nature == "debit")
                                        <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
                                        <?php @$tot_bal += @$tot_balance; ?>
                                        @endif
                                    @if(@$account_opening[0]->account_nature == "credit")
                                        <td><span class="waves-effect waves-light btn btn-info-light">cr</span></td>
                                        <?php @$tot_bal += @$tot_balance; ?>
                                    @endif
                                    
                                    
                                </tr>

                                <?php $tot_balance = $account_opening[0]->opening_balance ; ?>

                                @foreach($account_ledger AS $ac)
                                <?php @$tot_deb += $ac->debit; $tot_credit += $ac->credit;  ?>
                                    <tr class="text-dark">
                                        <td> {{ date('d-M-Y', strtotime($ac->date)) }}</td>
                                        <td ><span class="waves-effect waves-light btn btn-danger-light"> {{ @$ac->type }}</td>
                                        <td >{{ @$ac->description }}</td>
                                        <td><span class="waves-effect waves-light btn btn-danger-light">{{ number_format(abs(@$ac->debit),2) }}</span></td>
                                        <td><span class="waves-effect waves-light btn btn-success-light">{{  number_format(abs(@$ac->credit),2) }}</span></td>
                                        @if(@$account_opening[0]->account_nature == "credit")
                                        <?php $tot_balance += $ac->credit - $ac->debit ;?>
                                        @endif
                                        @if(@$account_opening[0]->account_nature == "debit")
                                        <?php $tot_balance += $ac->debit - $ac->credit ;?>
                                        @endif
                                        
                                        <td><span class="waves-effect waves-light btn btn-primary-light">{{ number_format(abs($tot_balance),2) }}</span></td>
                                        
                                        @if($tot_balance > 0)
                                        <td><span class="waves-effect waves-light btn btn-info-light">dr</span></td>
                                        <?php @$tot_bal += @$tot_balance; ?>
                                        @endif
                                        @if( @$tot_balance <= 0)
                                        <td><span class="waves-effect waves-light btn btn-primary-light">cr</span></td>
                                            <?php @$tot_bal += @$tot_balance; ?>
                                        @endif
                                    </tr>
                                @endforeach
                              
                        
                        @endif    
                    </tbody>
                    <tfoot>
                        <td colspan="3"></td>
                        @if(@$account_opening[0]->account_nature == "debit")
                            <td ><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_deb + $account_opening[0]->opening_balance ) }}</span></td>
                            <td><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_credit) }}</span></td>
                            <td><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(  @$tot_balance) }}</span></td>

                        @endif

                        @if(@$account_opening[0]->account_nature == "credit")
                            <td ><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_deb  ) }}</span></td>
                            <td><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_credit + $account_opening[0]->opening_balance) }}</span></td>
                            <td><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_balance) }}</span></td>

                        @endif
                        
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
@include('admin.partials.datatable')
<script>
    $('#pdf').click(function(event){
        event.preventDefault();
        var form_data = $('form').serialize();
        
        let from_date =  document.getElementById("from_date");
        let  get_from_date = new Date(from_date.value);
        let to_date =  document.getElementById("to_date");
        let  get_to_date = new Date(to_date.value);

        // Calculate the difference in milliseconds
        var differenceInMs = get_to_date - get_from_date;
        var differenceInDays = differenceInMs / (1000 * 60 * 60 * 24);

        var selectBox = document.getElementById("parent_id");
        var selectedOption = selectBox.options[selectBox.selectedIndex];
        var optionText = selectedOption.innerText;

        
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.account_pdf')}}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                var fileName =  optionText +" "+ differenceInDays +" "+"Days.pdf";
                link.href = window.URL.createObjectURL(blob);
                link.download = fileName;
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