<!-- This site is developed by Alphinex solutions {alphinex.com} -->
<!DOCTYPE html>

<html lang="en" class="loading">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta name="author" content="Bilal Feed">

    <title> Reports </title>

    <meta name="apple-mobile-web-app-capable" content="yes">

    <meta name="apple-touch-fullscreen" content="yes">

    <meta name="apple-mobile-web-app-status-bar-style" content="default">
  </head>

  <body data-col="2-columns" class=" 2-columns ">


</div>
    <div class="wrapper">
<div class="main-content">
<img src="{{ ('new_assets') }}/images/main-logo.jpeg" style="width:120px; height:120px; float:left; margin-left:18px; " alt="">
  <br />
  
<center>

    
  <h2 class="text-decoration-underline" style=" margin-left:58px; float:left; display:center;"> <u>Bilal Feed</u> | <u>Ledger Statement</u></h2>
    <br /><br /><br /><br /><br />
    <div>
    <img src="{{ ('admin_assets') }}/images/m1.png" style="width:40px; height:40px; margin-left:-250px;" alt=""><h4 style="color:green; margin-left:-70px;">{{$item_name->name}} {{$days}} Days</h4>
    
    </div>
    <p>From {{$from_date}} to {{$to_date}}</p>
</center>
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title"></h4>
      </div>
      <div class="box-body">
					<div class="table-responsive">
          <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
          <thead>
          <tr class="text-dark">
            <th >Id.No</th>
            <th >Date</th>
            <th > Account Name </th>
            <th > Item Name </th>
            <th > Posted Weight </th>
            <th > Rate </th>
            <th > Gross Ammount </th>
            <th > Fare </th>
            <th > Net Value </th>
          </tr>
            </thead>
            <tbody>
            @foreach($purchases AS $purchase)
        <tr class="text-dark">
          <td >{{ $loop->iteration }}</td>
          <td >{{ date('d-m-Y', strtotime($purchase->date)) }}</td>
          <td >{{ @$purchase->account->name }}</td>
          <td>{{ @$purchase->item->name }}</td>
          <td>{{ $purchase->posted_weight }}</td>
          <td>{{ $purchase->bag_rate }}</td>
          <td></td>
          <td>{{ $purchase->fare }}</td>
          <td>{{ $purchase->net_amount }}</td>
        </tr>
      @endforeach
            </tbody>
            
          </table>
					</div>              
				</div>
      <div class="card-body">

        <div class="card-block mcontainer">

          
        </div>
      </div>

    </div>

  </div>
</div>
<script>

$(document).ready( function () {
  $('.datatable').DataTable();

} );

</script>

</div>



</body>





</html>  