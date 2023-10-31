@extends('frontend.layouts.app')

@section('title', 'Notification')

@section('content')
    <div class="notification">
        <div class="scrolling-pagination">
            @foreach ($notifications as $notification)
                <a href="{{route('notification_show',$notification->id)}}">
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <h6 class="mb-1"><i class="fa-solid fa-bell me-2 @if(is_null($notification->read_at)) text-danger @endif"></i>{{Illuminate\Support\Str::limit($notification->data['title'],40) }}</h6>
                            <p class="mb-1">{{Illuminate\Support\Str::limit($notification->data['message'],100)}}</p>
                            <p class="mb-1 text-muted">{{Carbon\Carbon::parse($notification->created_at)->format('Y-m-d h:i:s A')}}</p>
                        </div>
                    </div>
                </a>
            @endforeach
            {{ $notifications->links() }}
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('ul.pagination').hide();
        $(function() {
            $('.scrolling-pagination').jscroll({
                autoTrigger: true,
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.scrolling-pagination',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });

       
    </script>

@endsection
