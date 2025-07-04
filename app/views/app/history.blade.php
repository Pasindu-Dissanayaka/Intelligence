@extends('layouts.default')

@section('title')
Chat History
@endsection

@section('content')
<div class='container-fluid'>
    <div class='card card-body vh-100'>
        <div class='card blur shadow-blur vh-100'>
            <div class='card-body overflow-auto overflow-x-hidden'>
                @foreach ($conversations as $conversation)
                    @if($conversation['is_bot']==true)
                    <div class="row justify-content-start mb-4">
                        <div class="col-auto">
                            <div class="card ">
                                <div class="card-body py-2 px-3">
                                    <p class="mb-1">
                                        {{ $conversation['message']; }}
                                    </p>
                                    <div class="d-flex align-items-center text-sm opacity-6">
                                        <i class="fa-solid fa-check text-sm me-1"></i>
                                        <small>{{ tick($conversation['sent_at'])->format('h:mma'); }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row justify-content-end text-right mb-4">
                        <div class="col-auto">
                            <div class="card bg-gradient-dark">
                                <div class="card-body py-2 px-3 text-white">
                                    <p class="mb-1">
                                        {{ $conversation['message']; }}<br>
                                    </p>
                                    <div class="d-flex align-items-center justify-content-end text-sm opacity-6">
                                        <i class="fa-solid fa-check text-sm me-1"></i>
                                        <small>{{ tick($conversation['sent_at'])->format('h:mma'); }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection