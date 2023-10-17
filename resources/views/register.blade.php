@extends('layouts.master')

@section('title','Register Page')

@section('content')
<div class="login-form">
    <form action="{{route('register')}}" method="post">
        @csrf

        @error('terms')
            
        @enderror
        <div class="form-group">
            <label>Username</label>
            <input class="au-input au-input--full  @error('name') is-invalid @enderror" type="text" name="name" placeholder="Username">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input class="au-input au-input--full @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input class="au-input au-input--full @error('phone') is-invalid @enderror" type="number" name="phone" placeholder="phone">
            @error('phone')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input class="au-input au-input--full @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password">
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Password Confirmation</label>
            <input class="au-input au-input--full @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" placeholder="Confirm Password">
            @error('password_confirmation')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        
        <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">register</button>
        
    </form>
    <div class="register-link">
        <p>
            Already have account?
            <a href="{{ route('auth#login') }}">Sign In</a>
        </p>
    </div>
</div>
@endsection
                    