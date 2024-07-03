
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ledger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <style>
       .challan_details {
    display: flex;
    /* align-items: center; */
    justify-content: space-between;
    margin: 25px 0px;
    width: 100%;
}
.bottom{
    margin-top: 100px;
}
table,th,td{
    border: 1px solid #000;
    padding: 6px;
    font-size: 14px;
}
p{
    font-size: 13px;
}
h3{
    margin-top: 50px;
    text-decoration: underline;
}
h4{
    margin-top: 15px;
}
h5{
    margin: 30px 0px;
    color: rgb(24, 94, 225);
    font-size: 18px;
}
.cr{
    color: rgb(24, 94, 225);
}
.dr{
    color: rgb(255, 229, 71);
}
.debit{
    color: rgb(60, 179, 113);
}
.credit{
    color: rgb(255, 0, 0);
}
.opening{
    color: rgb(106, 90, 205);
}
.ac{
    color: rgb(23, 194, 69);
}

    </style>
  </head>
  <body style=" background-repeat: no-repeat;  background-position: center center; background-size: contain;">
<div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="challan_wrapper">
                    <center>
                    <table style="border: none;">
                    
                    <tr style="border: none;">
                        <td style="border: none;"><span><img src="{{ asset('new_assets') }}/images/mustafa-poultry.jpg" alt="" width="150" height=""></span></td>
                        <td style="border: none;">
                            <h2 class="text-center">Tawakkal Marketing Traders | CashFlow Statement</h2>
                            <h3 class="text-center ac"><i class="bi bi-people-fill"></i>CashFlow Report</h3>
                            
                        </td>
                    </tr>
                  
                   </table>
                 </center>

                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
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
                        
                       
                    </tr>
                </tfoot>
                    </table>

                  
                </div>
            </div>
       
           
        </div>
       
    
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  </body>
  </html>

