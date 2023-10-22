@extends('frontend.layouts.app')

@section('title', 'Password Update')

@section('content')
    <div class="update-password">

        <div class="card mb-3">
            <div class="card-body">
                <div class="text-center">
                    <img src="{{ asset('img/update_password.png') }}" alt="">
                </div>
                <form action="{{ route('store.password') }}" method="post">
                    @csrf

                    <div class="form-group my-3">
                        <label class="form-label" for="">Old Password</label>
                        <input type="password" name="oldPassword"
                            class="form-control @error('oldPassword') is-invalid @enderror">
                        @error('oldPassword')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-3">
                        <label class="form-label" for="">New Password</label>
                        <input type="password" name="newPassword"
                            class="form-control @error('newPassword') is-invalid @enderror">
                        @error('newPassword')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-3">
                        <button class="form-control btn btn-theme btn-block btn-primary" type="submit">Update
                            Password</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

        })
    </script>
@endsection
