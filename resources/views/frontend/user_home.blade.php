
@extends('frontend.layouts.app')

@section('title','Wai Pay')

@section('content')
    <div class="home">
        <div class="row">
            <div class="col-12">
                <div class="profile mb-3">
                    <img src="https://ui-avatars.com/api/?background=5842E3&name={{$auth_user->name}}" alt="">
                    <h6>{{$auth_user->name}}</h6>
                    <p class="text-muted">{{$auth_user->wallet ? number_format($auth_user->wallet->amount) : 0 }} MMK </p>
                </div>
            </div>
            <div class="col-6">
                <div class="card shortcut-box mb-3">
                    <div class="card-body p-3">
                        <img src="{{asset('img/scan.png')}}" alt="">
                        <span>Scan & Pay</span>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card shortcut-box mb-3">
                    <div class="card-body p-3">
                        <img src="{{asset('img/qr.png')}}" alt="">
                        <span>Recieve QR</span>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-3 function-box">
                    <div class="card-body pe-0">
                        <a href="{{route('transfer')}}" class="d-flex justify-content-between">
                            <span><img src="{{asset('img/money-transfer.png')}}" alt="">Transfer</span>
                            <span class="me-3"><i class="fa-solid fa-angle-right"></i></span>
                        </a>
                        <hr>
                        <a href="#" class="d-flex justify-content-between">
                            <span><img src="{{asset('img/wallet.png')}}" alt="">Wallet</span>
                            <span class="me-3"><i class="fa-solid fa-angle-right"></i></span>
                        </a>
                        <hr>
                        <a href="{{route('transaction')}}" class="d-flex justify-content-between">
                            <span><img src="{{asset('img/transaction.png')}}" alt="">Transaction</span>
                            <span class="me-3"><i class="fa-solid fa-angle-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
