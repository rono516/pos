@extends('layouts.admin')

@section('title', __('product.Edit_Product'))
@section('content-header', __('product.Edit_Product'))

@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">{{ __('product.Name') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        id="name" placeholder="{{ __('product.Name') }}" value="{{ old('name', $product->name) }}">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="description">{{ __('product.Description') }}</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description"
                        placeholder="{{ __('product.Description') }}">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">{{ __('product.Image') }}</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="image" id="image">
                        <label class="custom-file-label" for="image">{{ __('product.Choose_file') }}</label>
                    </div>
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="barcode">{{ __('product.Barcode') }}</label>
                    <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                        id="barcode" placeholder="{{ __('product.Barcode') }}"
                        value="{{ old('barcode', $product->barcode) }}">
                    @error('barcode')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">{{ __('product.Price') }}</label>
                    <input type="text" name="price" class="form-control @error('price') is-invalid @enderror"
                        id="price" placeholder="{{ __('product.Price') }}"
                        value="{{ old('price', $product->price) }}">
                    @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quantity">{{ __('product.Quantity') }}</label>
                    <input type="text" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                        id="quantity" placeholder="{{ __('product.Quantity') }}"
                        value="{{ old('quantity', $product->quantity) }}">
                    @error('quantity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="totalprice">{{ __('Total Price') }}</label>
                    <input type="number" name="totalprice" class="form-control @error('totalprice') is-invalid @enderror"
                        id="totalprice" placeholder="{{ __('Total Price') }}"
                        value="{{ old('totalprice', $product->totalprice) }}" readonly>
                    @error('totalprice')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">{{ __('product.Status') }}</label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                        <option value="1" {{ old('status', $product->status) === 1 ? 'selected' : '' }}>
                            {{ __('common.Active') }}</option>
                        <option value="0" {{ old('status', $product->status) === 0 ? 'selected' : '' }}>
                            {{ __('common.Inactive') }}</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="batchno">{{ __('Batch No') }}</label>
                    <input type="text" name="batchno" class="form-control @error('batchno') is-invalid @enderror"
                        id="batchno" placeholder="{{ __('Batch No') }}" value="{{ old('batchno', $product->batchno) }}">
                    @error('batchno')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="expiry">{{ __('Expiry Date') }}</label>
                    <input type="date" name="expiry" class="form-control @error('expiry') is-invalid @enderror"
                        id="expiry" placeholder="{{ __('Expiry Date') }}"
                        value="{{ old('expiry', $product->expiry ? date('Y-m-d', strtotime($product->expiry)) : '') }}">
                    @error('expiry')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="shelf">{{ __('Shelf') }}</label>
                    <select name="shelf" class="form-control @error('shelf') is-invalid @enderror" id="shelf">
                        {{-- <option value="DDA">DDA</option>
                        <option value="Shelf 1">Shelf 1</option>
                        <option value="Shelf 2">Shelf 2</option> --}}
                        <option value="DDA" {{ old('shelf', $product->shelf) == 'DDA' ? 'selected' : '' }}>DDA</option>
                        <option value="Shelf 1" {{ old('shelf', $product->shelf) == 'Shelf 1' ? 'selected' : '' }}>Shelf 1
                        </option>
                        <option value="Shelf 2" {{ old('shelf', $product->shelf) == 'Shelf 2' ? 'selected' : '' }}>Shelf 2
                        </option>
                    </select>
                    @error('shelf')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button class="btn btn-primary" type="submit">{{ __('common.Update') }}</button>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script>
        function calculateTotal() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            document.getElementById('totalprice').value = (price * quantity).toFixed(2);
        }

        document.getElementById('price').addEventListener('input', calculateTotal);
        document.getElementById('quantity').addEventListener('input', calculateTotal);
    </script>
@endsection
