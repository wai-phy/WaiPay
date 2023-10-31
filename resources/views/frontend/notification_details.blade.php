@extends('frontend.layouts.app')

@section('title', 'Notification_Details')

@section('content')
    <div class="notification-detail">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-1">{{$notification->data['title']}}
                </h6>
                <p class="mb-1">{{ $notification->data['message']}}</p>
                <small class="mb-1 text-muted">{{ Carbon\Carbon::parse($notification->created_at)->format('Y-m-d h:i:s A') }}</small>
            </div>
        </div>
    </div>
@endsection
