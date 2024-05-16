<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('new_assets') }}/invoice_css/style.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    
    <title>Invoice</title>
	<style>
        img{
            float:right;
            margin-top:5px;
            margin-right:88px;    
        }
		@page { size: auto;  margin: 0mm; }
	</style>
</head>
<body>
    <div class="container">
        <div class="row"> 
            <center>
                <h1>Al Mustafa Poultry </h1><br /><br /><br />
            </center>
        </div>
    </div>
    

    <section class="invoice-info">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="invoice-info">
                        <p>Invoice To</p>
                        <p >Account Name : {{$dcs[0]->account->name}}</p>
                        <p>Address : {{$dcs[0]->account->address}}</p>
                        
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="invi-date">
                        <p>invoice : {{$dcs[0]->invoice_no}}</p>
                        <p>Date : {{ date('d-M-Y', strtotime($dcs[0]->date))  }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="invoice-table">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 m-0 p-0">
                    <table class="table">
                        <thead>
                          <tr class="table-heading" >
                            <th scope="col">SL</th>
                            <th scope="col">Description</th>
                            <th scope="col">Price</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Total</th>
                          </tr>
                        </thead>
                        <tbody>
							<?php $net = 0; ?>
						@foreach($dcs as $s)
							<tr scope="row">
								<td>{{ $loop->iteration }}</td>
								
								<td> <span class="waves-effect waves-light btn btn-warning-light">{{ @$s->item->name }}</span></td>
								<td>{{  number_format((@$s->sale_ammount + @$s->profit)/@$s->quantity, 2) }} </td>
								
								<td>{{ @$s->quantity }}</td>
								<td>{{ @$s->sale_ammount + @$s->profit }}</td>
								
								<?php	$net +=  @$s->sale_ammount + @$s->profit;?>
							</tr>
						@endforeach
                          
                          <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td></td>
                            <td class="table-total" >Total:</td>
                            <td class="table-total-price" >{{$net}}</td>
                          </tr>
                          
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
    </section>


    <!-- <section class="payment-info">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="payment-info-div">
                        <h5>Payment Info</h5>
                        <ul>
                            <li>Account 123456789</li>
                            <li>Account Name  Bilal</li>
                            <li>Bank Details   Lorem, ipsum dolor.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <section class="terms-conditions">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="terms-cond-div">
                        <h5>Terms & Conditions</h5>
                        <ul>
                            <li>If You Find Any Query Than Contact Under 1 Week Otherwise Any Changes Would Not Be Accepted! </li>
                          
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 float-left">
                    <div class="sign ">
                        
                        <p class="text-start" ><ul>Signature </ul></p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <footer>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center p-0">
                    <div class="thanks">
                        <h3>Thank You For Your Business</h3>
                    </div>
                </div>
            </div>
        </div>
    </footer>















    
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>