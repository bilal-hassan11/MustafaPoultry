@extends('layouts.admin')
@section('content')


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        
                        <h4 class="page-title">Purchase Report</h4>
                    </div>
                </div>
            </div>
                <form action="" id="form">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Category</label>
                            <select class="form-control 
                            select2" name="category_id" id="category_id" required>
                                <option value="">Select item</option>
                                @foreach($Category AS $cat)
                                    <option value="{{ $cat->hashid }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="">From Date</label>
                            <input type="date" class="form-control" name="from_date" id="from_date" required>
                        </div>
                        <div class="col-md-4">
                            <label for="">To Date</label>
                            <input type="date" class="form-control" name="to_date" id="to_date" required>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary float-right mt-2">
                    <button class="btn btn-danger mt-2" id="pdf">PDF</button>
                    <button class="btn btn-warning mt-2" id="print">Print</button>
                </form>
            </div>
        </div>
    </div>
    @if(isset($from_date))
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box ">
                        <center>
                        <h2 style="color:green;  justify_content:center;"><span> <i class="glyphicon glyphicon-gift"></i> </span>{{@$item_name[0]['name']}}</h2>
                    <h4>From {{date('d-M-Y', strtotime($from_date))}} to {{date('d-M-Y', strtotime($to_date))}}</h4>
                        </center>
                    
                       
                    </div>
                </div>
            </div>
                
            </div>
        </div>
    </div>
    @endif
    <div class="col-12">
        <div class="box">
        <div class="box-header with-border">
            <h4 class="box-title">ALL Purchases Report</h4>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                <thead>
                    <tr class="text-dark">
                        <th>Date</th>
                        <th colspan="1"> Description </th>
                        <th> Dr </th>
                        <th> Cr </th>
                        <th> Balance </th>
                        <th> cr/dr </th>
                        
                    </tr>
                </thead>
                <tbody>
                    @php
                        $tot_balance = 0; $tot_deb=0;
                        $tot_credit=0;
                    @endphp
                    @if($purchases != "")
                        @foreach(@$purchases AS $purchase)
                            <tr class="text-dark">
                            <td>{{ date('d-M-Y', strtotime($purchase->created_at)) }}</td>
                            <td >{{ @$purchase->description }}</td>
                            <td><span class="waves-effect waves-light btn btn-danger-light">{{ number_format(@$purchase->debit) }}</span></td>
                            <td><span class="waves-effect waves-light btn btn-success-light">{{  number_format(@$purchase->credit) }}</span></td>
                            @if($purchase->debit == 0)
                                <?php $tot_balance += $purchase->credit; $tot_credit += $purchase->credit;?>
                                <td><span class="waves-effect waves-light btn btn-primary-light">{{ number_format($tot_balance) }}</span></td>
                            @endif
                            @if( @$purchase->credit == 0)
                            <?php $tot_balance -= $purchase->debit; $tot_deb +=$purchase->debit;?>
                                <td><span class="waves-effect waves-light btn btn-primary-light">{{ number_format($tot_balance) }}</span></td>
                            @endif
                            
                            @if($tot_balance > 0)
                                <td><span class="waves-effect waves-light btn btn-info-light">cr</span></td>
                            @endif
                            @if( @$tot_balance < 0)
                                <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
                            @endif
                            <td></td>
                            
                            </tr>
                        @endforeach
                    @endif    
                </tbody>
                <tfoot>
                    <td colspan="2"></td>
                    <td ><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_deb) }}</span></td>
                    <td><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_credit) }}</span></td>
                    <td><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_balance) }}</span></td>

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
@include('admin.partials.datatable')
<script>
    $('#pdf').click(function(event){
        event.preventDefault();
        var form_data = $('form').serialize();
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.item_pdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "item_report.pdf";
                link.click();
                return false;
            },
            error: function(blob){
                console.log(blob);
            }
        });
    });

    // //when click on print
    $('#print').click(function(event){
        event.preventDefault();
        var route = "{{ route('admin.reports.item_print') }}";
        $('form').attr('action', route);
        $('form').attr('target', '_blank');;
        $('#form').submit();

    });
</script>
@endsection