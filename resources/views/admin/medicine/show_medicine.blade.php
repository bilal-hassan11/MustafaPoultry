@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="row">
                            <div class="col-md-8">
                                <h3>Invoice #{{ $medicineInvoice[0]->invoice_no }}</h3>
                                <p class="mb-0">Date: {{ $medicineInvoice[0]->date }}</p>
                                <p class="mb-0">Account: {{ $medicineInvoice[0]->account->name }}</p>
                                <p class="mb-0">Description: {{ $medicineInvoice[0]->description }}</p>
                            </div>
                            <div class="col-md-4" style="text-align: right;">
                                <a class="btn btn-secondary mb-3" href="{{ url()->previous() }}">
                                    Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Purchase Price</th>
                                    <th>Expiry</th>
                                    <th>Discount (Rs)</th>
                                    <th>Discount (%)</th>
                                    <th>Net Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotal = 0;
                                    $totalDiscountRs = 0;
                                @endphp
                                @foreach ($medicineInvoice as $index => $item)
                                    <tr>
                                        <td style="text-align: center;">{{ $index + 1 }}</td>
                                        <td>{{ $item->item->name }}</td>
                                        <td style="text-align: right;">{{ $item->quantity }}</td>
                                        <td style="text-align: right;">Rs {{ number_format($item->purchase_price, 2) }}</td>
                                        <td>{{ $item->expiry_date ?? '' }}</td>
                                        <td style="text-align: right;">Rs {{ number_format($item->discount_in_rs, 2) }}
                                        </td>
                                        <td style="text-align: right;">{{ number_format($item->discount_in_percent, 2) }}%
                                        </td>
                                        <td style="text-align: right;">Rs {{ number_format($item->net_amount, 2) }}</td>
                                    </tr>
                                    @php
                                        $subtotal += $item->quantity * $item->purchase_price;
                                        $totalDiscountRs += $item->discount_in_rs;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot style="text-align: right;">
                                <tr>
                                    <th colspan="7">Subtotal</th>
                                    <th>Rs {{ number_format($subtotal, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="7">Total Discount</th>
                                    <th>Rs {{ number_format($totalDiscountRs, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="7">Net Amount</th>
                                    <th>Rs {{ number_format($subtotal - $totalDiscountRs, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
