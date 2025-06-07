@extends('layouts.admin')
@section('content-header', 'Purchase Orders')
@section('content-actions')
    <a href="javascript:void(0);" id="btn-open" class="btn btn-primary">{{ __('Add Purchase Order') }}</a>
@endsection

@section('content')

    <div style="display: none" id="new-purchase-order" class="container mb-2 ">
        <h2>Supplier: {{ $supplier->first_name }} {{ $supplier->last_name }}</h2>


        <form action="{{ route('purchaseorders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">

            <div id="items-container">
                <div class="row mb-2">
                    <div class="col">
                        <select class="form-control product-select" name="items[0][product_id]">
                            <option value="">-- Select Product --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="items[0][product_name]"
                            placeholder="Or enter new product name">
                    </div>
                    <div class="col">
                        <input type="number" name="items[0][quantity]" class="form-control" placeholder="Quantity">
                    </div>
                    <div class="col">
                        <input type="number" name="items[0][unit_price]" class="form-control" placeholder="Unit Price"
                            step="0.01">
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary" id="add-item">Add Item</button>
            <br><br>
            <button type="submit" class="btn btn-success">Submit Purchase Order</button>
        </form>
    </div>
    {{-- <h2>Purchase Orders</h2> --}}

    @foreach ($supplier->purchaseOrders as $order)
        <div class="mb-4 p-3 border rounded">
            <h4>Order #{{ $order->id }}</h4>
            <p>Total Amount: KES {{ number_format($order->total_amount, 2) }}</p>
            <p>Fulfilled: {{ $order->fulfilled ? 'Yes' : 'No' }}</p>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                {{-- Use product relationship if available, fallback to stored name --}}
                                {{ $item->product->name ?? $item->product_name }}
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>KES {{ number_format($item->unit_price, 2) }}</td>
                            <td>KES {{ number_format($item->sub_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <script>
        let itemIndex = 1;
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.classList.add('row', 'mb-2');
            row.innerHTML = `
            <div class="col">
                <select class="form-control product-select" name="items[${itemIndex}][product_id]">
                    <option value="">-- Select Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="items[${itemIndex}][product_name]" placeholder="Or enter new product name">
            </div>
            <div class="col">
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Quantity">
            </div>
            <div class="col">
                <input type="number" name="items[${itemIndex}][unit_price]" class="form-control" placeholder="Unit Price" step="0.01">
            </div>
        `;
            container.appendChild(row);
            itemIndex++;
        });
    </script>
@endsection

@section('js')
    <script type="module">
        $(document).ready(function() {

            $(document).on('click', '#btn-open', function() {
                console.log("js works")
                $('#new-purchase-order').toggle();
            });
        });
    </script>
@endsection
