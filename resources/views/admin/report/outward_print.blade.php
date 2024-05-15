<table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
    <thead>
        <tr>
            <th>Id.No</th>
            <th>Date</th>
            <th> Account Name </th>
            <th> Item Name </th>
            <th> Posted Weight </th>
            <th> Rate </th>
            <th> Gross Ammount </th>
            <th> Fare </th>
            <th> Net Value </th>
          </tr>
    </thead>
    <tbody>
        @foreach($purchases AS $purchase)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ date('d-m-Y', strtotime($purchase->date)) }}</td>
          <td>{{ $purchase->account->name }}</td>
          <td>{{ $purchase->item->name }}</td>
          <td>{{ $purchase->posted_weight }}</td>
          <td>{{ $purchase->bag_rate }}</td>
          <td></td>
          <td>{{ $purchase->fare }}</td>
          <td>{{ $purchase->net_amount }}</td>
        </tr>
      @endforeach
    </tbody>
</table>