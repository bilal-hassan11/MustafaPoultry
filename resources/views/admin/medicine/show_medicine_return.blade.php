@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="card shadow-sm">

                    {{-- Header --}}
                    <div class="card-header bg-primary text-white">
                        <div class="row align-items-start">
                            <div class="col-md-8">
                                <h3 class="mb-1">{{ $type }} Invoice</h3>
                                <h5 class="mb-1">Invoice # {{ $medicineInvoice[0]->invoice_no }}</h5>
                                <p class="mb-0">Date: {{ date('d-M-Y', strtotime($medicineInvoice[0]->date)) }}</p>
                                <p class="mb-0">Account: {{ $medicineInvoice[0]->account->name ?? '-' }}</p>
                                @if ($medicineInvoice[0]->description)
                                    <p class="mb-0">Description: {{ $medicineInvoice[0]->description }}</p>
                                @endif
                                @if ($medicineInvoice[0]->ref_no)
                                    <p class="mb-0">Ref No: {{ $medicineInvoice[0]->ref_no }}</p>
                                @endif
                            </div>
                            <div class="col-md-4 text-end mt-2">
                                <a href="{{ url()->previous() }}" class="btn btn-light btn-sm mb-1">
                                    <i class="ri-arrow-left-line"></i> Back
                                </a>
                                @if ($type === 'Purchase Return')
                                    <a href="{{ route('admin.medicine-invoices.purchase_return.edit', $medicineInvoice[0]->invoice_no) }}"
                                       class="btn btn-warning btn-sm mb-1">
                                        <i class="ri-edit-line"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.medicine-invoices.purchase_return.show', [$medicineInvoice[0]->invoice_no, 'generate_pdf' => 1]) }}"
                                       class="btn btn-light btn-sm mb-1" target="_blank">
                                        <i class="ri-download-2-line"></i> PDF
                                    </a>
                                @else
                                    <a href="{{ route('admin.medicine-invoices.sale_return.edit', $medicineInvoice[0]->invoice_no) }}"
                                       class="btn btn-warning btn-sm mb-1">
                                        <i class="ri-edit-line"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.medicine-invoices.sale_return.show', [$medicineInvoice[0]->invoice_no, 'generate_pdf' => 1]) }}"
                                       class="btn btn-light btn-sm mb-1" target="_blank">
                                        <i class="ri-download-2-line"></i> PDF
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Transport info --}}
                    @if ($medicineInvoice[0]->transport_name || $medicineInvoice[0]->vehicle_no || $medicineInvoice[0]->driver_name)
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="text-muted small">Transport Name</label>
                                    <p>{{ $medicineInvoice[0]->transport_name ?? '-' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <label class="text-muted small">Vehicle No</label>
                                    <p>{{ $medicineInvoice[0]->vehicle_no ?? '-' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted small">Driver Name</label>
                                    <p>{{ $medicineInvoice[0]->driver_name ?? '-' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <label class="text-muted small">Contact No</label>
                                    <p>{{ $medicineInvoice[0]->contact_no ?? '-' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <label class="text-muted small">Builty No</label>
                                    <p>{{ $medicineInvoice[0]->builty_no ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Previous balance --}}
                    <div class="card-body pb-0">
                        <div class="alert alert-secondary py-2 mb-0">
                            <strong>Previous Balance:</strong> Rs {{ number_format($previous_balance ?? 0, 2) }}
                        </div>
                    </div>

                    {{-- Line items --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th class="text-end">Quantity</th>
                                        <th class="text-end">Rate</th>
                                        <th>Expiry</th>
                                        <th class="text-end">Discount (Rs)</th>
                                        <th class="text-end">Discount (%)</th>
                                        @if ($type === 'Sale Return')
                                            <th class="text-end">Commission (%)</th>
                                        @endif
                                        <th class="text-end">Net Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($medicineInvoice as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->item->name ?? '-' }}</td>
                                            <td class="text-end">{{ abs($item->quantity) }}</td>
                                            <td class="text-end">
                                                Rs {{ number_format($type === 'Purchase Return' ? $item->purchase_price : $item->sale_price, 2) }}
                                            </td>
                                            <td>{{ $item->expiry_date ?? '-' }}</td>
                                            <td class="text-end">Rs {{ number_format($item->discount_in_rs, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->discount_in_percent, 2) }}%</td>
                                            @if ($type === 'Sale Return')
                                                <td class="text-end">{{ number_format($item->commission_percent ?? 0, 2) }}%</td>
                                            @endif
                                            <td class="text-end">Rs {{ number_format($item->net_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="text-end">
                                    <tr>
                                        <th colspan="{{ $type === 'Sale Return' ? 8 : 7 }}">Subtotal</th>
                                        <th>Rs {{ number_format($medicineInvoice->sum('amount'), 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="{{ $type === 'Sale Return' ? 8 : 7 }}">Total Discount</th>
                                        <th>Rs {{ number_format($medicineInvoice->sum('discount_in_rs'), 2) }}</th>
                                    </tr>
                                    @if ($type === 'Sale Return')
                                        <tr>
                                            <th colspan="8">Total Commission</th>
                                            <th>Rs {{ number_format($medicineInvoice->sum('commission_amount'), 2) }}</th>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th colspan="{{ $type === 'Sale Return' ? 8 : 7 }}">Net Amount</th>
                                        <th>Rs {{ number_format($medicineInvoice->sum('net_amount'), 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="{{ $type === 'Sale Return' ? 8 : 7 }}">Closing Balance</th>
                                        <th>Rs {{ number_format(($previous_balance ?? 0) + $medicineInvoice->sum('net_amount'), 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer text-muted small">
                        @if ($type === 'Purchase Return')
                            <i class="ri-information-line"></i>
                            Purchase Return removes stock. These quantities have been deducted from inventory.
                        @else
                            <i class="ri-information-line"></i>
                            Sale Return adds stock back. These quantities have been returned to inventory.
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
