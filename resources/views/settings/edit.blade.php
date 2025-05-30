@extends('layouts.admin')

@section('title', __('settings.Update_Settings'))
@section('content-header', __('settings.Update_Settings'))

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('settings.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="app_name">{{ __('settings.app_name') }}</label>
                    <input type="text" name="app_name" class="form-control @error('app_name') is-invalid @enderror"
                        id="app_name" placeholder="{{ __('settings.App_name') }}"
                        value="{{ old('app_name', $setting->app_name) }}">
                    eroi
                    @error('app_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">{{ __('Phone') }}</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        id="phone" placeholder="{{ __('phone') }}" value="{{ old('phone', $setting->phone) }}">
                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="app_name">{{ __('Email') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" placeholder="{{ __('email') }}" value="{{ old('email', $setting->email) }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">{{ __('Logo') }}</label>
                    <div class="mt-2 mb-2">
                        <img id="image-preview" src="{{ $setting->logo ? Storage::url($setting->logo) : '#' }}"
                            alt="Image Preview" style="max-height: 200px; {{ $setting->logo ? '' : 'display: none;' }}">
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="image" id="image"
                            onchange="previewImage(event)">
                        <label class="custom-file-label" for="image">{{ __('Choose_file') }}</label>
                    </div>

                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="app_description">{{ __('settings.app_description') }}</label>
                    <textarea name="app_description" class="form-control @error('app_description') is-invalid @enderror"
                        id="app_description" placeholder="{{ __('settings.app_description') }}">{{ old('app_description', $setting->app_description) }}</textarea>
                    @error('app_description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="currency_symbol">{{ __('settings.Currency_symbol') }}</label>
                    <input type="text" name="currency_symbol"
                        class="form-control @error('currency_symbol') is-invalid @enderror" id="currency_symbol"
                        placeholder="{{ __('settings.Currency_symbol') }}"
                        value="{{ old('currency_symbol', $setting->currency_symbol) }}">
                    @error('currency_symbol')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="warning_quantity">{{ __('settings.warning_quantity') }}</label>
                    <input type="text" name="warning_quantity"
                        class="form-control @error('warning_quantity') is-invalid @enderror" id="warning_quantity"
                        placeholder="{{ __('settings.warning_quantity') }}"
                        value="{{ old('warning_quantity', $setting->warning_quantity) }}">
                    @error('warning_quantity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ __('settings.Change_Setting') }}</button>
            </form>
        </div>
    </div>
@endsection

@section('js')

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            const fileLabel = input.nextElementSibling;

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);

                // Update label with file name
                fileLabel.innerText = input.files[0].name;
            }
        }
    </script>

@endsection
