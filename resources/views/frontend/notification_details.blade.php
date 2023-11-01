@extends('frontend.layouts.app')

@section('title', 'Notification_Details')

@section('content')
    <div class="notification-detail">
        <div class="card text-center">
            <div class="card-body">
                <div class="text-center">
                    <img src="{{ asset('img/notification.png') }}" alt="" style="width: 220px">
                </div>
                <h6 class="mb-1">{{ $notification->data['title'] }}
                </h6>
                <p class="mb-1">{{ $notification->data['message'] }}</p>
                <small class="mb-1 text-muted">{{ Carbon\Carbon::parse($notification->created_at)->format('Y-m-d h:i:s A') }}</small>
                <div class="text-center">
                    <a class="btn btn-theme btn-sm" href="{{ $notification->data['web_link'] }}">Web Link</a>
                </div>
            </div>
        </div>
    </div>
@endsection
