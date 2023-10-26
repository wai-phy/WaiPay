@extends('frontend.layouts.app')

@section('title', 'Transaction_Details')

@section('content')
    <div class="transaction-detail">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="{{ asset('img/checked.png') }}" alt="">
                </div>
                @if (session('transfer_success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('transfer_success') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @if ($transaction->type == 1)
                    <h6 class="text-center text-success mb-5">{{ number_format($transaction->amount) }} MMK</h6>
                @elseif ($transaction->type == 2)
                    <h6 class="text-center text-danger mb-5">{{ number_format($transaction->amount) }} MMK</h6>
                @endif
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Trx ID</p>
                    <p class="mb-0">{{ $transaction->trx_id }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Reference Number</p>
                    <p class="mb-0">{{ $transaction->ref_no }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Type</p>
                    <p class="mb-0">
                        @if ($transaction->type == 1)
                            <span class="badge bg-success badge-pill ">Income</span>
                        @elseif ($transaction->type == 2)
                            <span class="badge bg-danger badge-pill ">Expense</span>
                        @endif
                    </p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Amount</p>
                    <p class="mb-0">{{ number_format($transaction->amount) }} MMK</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Date and Time</p>
                    <p class="mb-0">{{ $transaction->created_at }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    @if ($transaction->type == 1)
                        <p class="mb-0 text-muted">From</p>
                    @elseif ($transaction->type == 2)
                        <p class="mb-0 text-muted">To</p>
                    @endif
                    <p class="mb-0">{{$transaction->source ? $transaction->source->name : '-' }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Description</p>
                    <p class="mb-0">{{ $transaction->description }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
