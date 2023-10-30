
@extends('frontend.layouts.app')

@section('title','Recieve QR')

@section('content')
    <div class="recieve-qr">
        <div class="card my-card">
            <div class="card-body">
                <p class="text-center"><strong>QR Scan To Pay</strong></p>
                <div class="text-center mb-1">
                    {!! QrCode::size(200)->generate($auth_user->phone) !!}
                </div>
                <p class="text-center mt-2 mb-1"><strong>{{$auth_user->name}}</strong></p>
                <p class="text-center mb-1">{{$auth_user->phone}}</p>
            </div>
        </div>
    </div>
@endsection
