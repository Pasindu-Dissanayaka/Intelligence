@extends('layouts.default')

@section('title')
Chat History
@endsection

@section('content')
<div class='container-fluid'>
    <div class='card card-body vh-100'>
        <div class='card blur shadow-blur vh-100'>
            <div class='card-body overflow-auto overflow-x-hidden'>
                <div class="row mt-4">
                    <div class="col-md-12 text-center">
                        <span class="badge text-dark">@php echo tick()->format('ddd, hh:mma'); @endphp</span>
                    </div>
                </div>

                @foreach ($conversations as $conversation)
                <div class="row justify-content-start mb-4">
                    <div class="col-auto">
                        <div class="card ">
                            <div class="card-body py-2 px-3">
                                <p class="mb-1">
                                    {{ $conversation['message']; }}
                                </p>
                                <div class="d-flex align-items-center text-sm opacity-6">
                                    <i class="ni ni-check-bold text-sm me-1"></i>
                                    <small>{{ $conversation['timestamp'] }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach


            </div>
        </div>
    </div>
</div>
@endsection