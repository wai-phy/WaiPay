@extends('frontend.layouts.master')

@section('title','Login Page')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="height:100vh">
        <div class="col-md-6 ">
            <div class="card auth_form p-4">
                <div class="card-body">
                    <div class="login-form">
                        <div class="my-3">
                            <h3 class="text-center">Login Form</h3>
                            <p class="text-center text-muted">Fill the form to Login</p>
                        </div>
                        <form action="{{ route('login')}}" method="post">
                            @csrf
                            <div class="form-group my-3">
                                <label class="form-label">Email Address</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group my-3">
                                <label class="form-label">Password</label>
                                <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group my-3">
                                <button class="form-control btn btn-primary" type="submit">sign in</button>
                            </div>

                        </form>
                        <div class="register-link">
                            <p>
                                Don't you have an account?
                                <a href="{{ route('auth#register')}}">Sign Up Here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
