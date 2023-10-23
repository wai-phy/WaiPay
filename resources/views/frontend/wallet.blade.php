
@extends('frontend.layouts.app')

@section('title','Wallet')

@section('content')
    <div class="wallet">
        <div class="card">
            <div class="card-body my-card">
                <div class="my-3">
                    <span>Balance</span>
                    <h3>{{number_format($auth_user->wallet ? $auth_user->wallet->amount : 0)}} <span>MMK</span></h3>
                </div>
                <div class="my-3">
                    <span>Account Number</span>
                    <h4>{{$auth_user->wallet ? $auth_user->wallet->account_number : '-'}}</h4>
                </div>
                <div class="my-3">
                    <h5>{{$auth_user->name}}</h5>
                </div>
            </div>
        </div>
    </div>
@endsection
