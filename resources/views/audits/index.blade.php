@extends('layouts.admin')

@section('title', __('Audit Logs'))
@section('content-header', __('Audit Logs'))
{{-- @section('content-actions')
    <a href="{{ route('cart.index') }}" class="btn btn-primary">{{ __('cart.title') }}</a>
@endsection --}}
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-7"></div>
                <div class="col-md-5">
                    <form action="{{ route('orders.index') }}">
                        <div class="row">
                            <div class="col-md-5">
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}" />
                            </div>
                            <div class="col-md-5">
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}" />
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-primary" type="submit">{{ __('order.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Event') }}</th>
                        <th>{{ __('Updated') }}</th>
                        {{-- <th>{{ __('ID') }}</th> --}}
                        <th>{{ __('Timestamp') }}</th>
                        <th>{{ __('Updated values') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($audits as $audit)
                        <tr>
                            <td class="px-4 py-2">{{ $audit->user_name }}</td>
                            <td class="px-4 py-2">{{ $audit->event }}</td>
                            <td class="px-4 py-2">{{ $audit->model_display  }}</td>
                            {{-- <td class="px-4 py-2">{{ $audit->auditable_id }}</td> --}}
                            <td class="px-4 py-2">{{ $audit->created_at }}</td>
                            <td>
                                @php
                                    $old = json_decode($audit->old_values, true);
                                    $new = json_decode($audit->new_values, true);
                                @endphp

                                @if ($old && $new)
                                    <ul>
                                        @foreach ($new as $key => $value)
                                            <li>
                                                {{ $key }}: <span
                                                    class="text-red-500">{{ $old[$key] ?? 'N/A' }}</span> → <span
                                                    class="text-green-500">{{ $value }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $audits->render() }}
        </div>
    </div>
@endsection
