@extends('frontend.layouts.master')

@section('title', 'Register Page')


@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height:100vh">
            <div class="col-md-6 ">
                <div class="card auth_form p-4">
                    <div class="card-body">
                        <div class="login-form">
                            <div class="my-3">
                                <h3 class="text-center">Register Form</h3>
                                <p class="text-center text-muted">Fill the form to Register</p>
                            </div>
                            <form action="{{ route('register') }}" method="post">
                                @csrf

                                @error('terms')
                                @enderror
                                <div class="form-group my-3">
                                    <label class="form-label">Username</label>
                                    <input class="form-control  @error('name') is-invalid @enderror" type="text"
                                        name="name" value="{{ old('name') }}" placeholder="Username">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group my-3">
                                    <label class="form-label">Email Address</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                        name="email" value="{{ old('email') }}" placeholder="Email">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group my-3">
                                    <label>Phone</label>
                                    <input class="form-control @error('phone') is-invalid @enderror" type="number"
                                        name="phone" value="{{ old('number') }}" placeholder="phone">
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group my-3">
                                    <label class="form-label">Password</label>
                                    <input class="form-control @error('password') is-invalid @enderror" type="password"
                                        name="password" placeholder="Password">
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group my-3">
                                    <label>Password Confirmation</label>
                                    <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                        type="password" name="password_confirmation" placeholder="Confirm Password">
                                    @error('password_confirmation')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group my-3">
                                    <button class="form-control btn btn-primary" type="submit">register</button>
                                </div>

                            </form>
                            <div class="register-link">
                                <p>
                                    Already have an account?
                                    <a href="{{ route('auth#login') }}">Sign In</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
