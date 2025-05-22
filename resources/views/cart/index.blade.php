@extends('layouts.admin')

@section('title', __('order.title'))

@section('content')
    <div id="cart"></div>
    {{-- <div id="cart">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Scan Barcode...">
                    </div>
                    <div class="col">
                        <select name="" id="" class="form-control">
                            <option value="">General Customer</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
    </div> --}}

@endsection
