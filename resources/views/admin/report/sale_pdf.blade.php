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
p{
    font-size: 14px;
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
    </style>
  </head>
  <body>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="challan_wrapper">
                    <h3 class="text-center">Tawakkal Marketing Traders | Ledger Statement</h3>
                    <h4 class="text-center"><i class="bi bi-people-fill"></i>{{$account_name->name}} {{$days}}  DAYS</h4>
                  
                    <h5 class="text-center">From {{$from_date}} to {{$to_date}}</h5>

                    <h6 class="text-end">Balance: 30,322,090 Dr.</h6>
                  <br />
                    <table class="table table-bordered border-primary">
                       <thead >
                            <th>Date</th>
                            <th>Description</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                       </thead>
                       <tbody>
                        @php 
                        $net_amount = 0; $tot_cr = 0;
                        @endphp
                        @foreach($sales AS $sale)
                        <tr class="text-dark">
                          <td>{{ date('d-M-Y', strtotime($sale->date)) }}</td>
                          <td>Vehicle # {{ $sale->vehicle_no }}, GP_No # {{$sale->gp_no}} , {{$sale->item->name}} , Bags:{{ @$sale->no_of_bags }}, {{$sale->account->name}} , Fare:{{ $sale->fare }}</td>
                          <td>{{$sale->net_ammount}}</td>
                          <?php $tot_cr += $sale->net_ammount ?>
                          <td>0</td>
                          <td>{{$net_amount += $sale->net_ammount}}  </td>
                          
                        </tr>
                       @endforeach
                    </tbody>
                    </table>

                  
                </div>
            </div>
       
           
        </div>
       
    
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  </body>
</html>