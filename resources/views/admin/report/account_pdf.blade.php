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
                            <h2 class="text-center"><u>Tawakkal Marketing Traders | Ledger Statement</u></h3><br />
                            <h5 class="text-center ac"><i class="bi bi-people-fill"></i>{{ $account_name->name }}</h5>
                          
                            <h5 class="text-center">From {{$from_date}} to {{$to_date}}</h5>
        
                            
                        </td>
                    </tr>
                  
                   </table>
                 </center>

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
                                    
                                    <?php $tot_balance += $ac->credit - $ac->debit ;?>
                                    <td><span class="waves-effect waves-light btn btn-primary-light">{{ number_format(abs($tot_balance),2) }}</span></td>
                                    
                                    @if($tot_balance > 0)
                                    <td><span class="waves-effect waves-light btn btn-info-light">cr</span></td>
                                    <?php @$tot_bal += @$tot_balance; ?>
                                    @endif
                                    @if( @$tot_balance <= 0)
                                    <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
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
       
    
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  </body>
  </html>

