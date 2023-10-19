
@extends('backend.layouts.app')
@section('title','user update')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-6 offset-2">
                   <div class="card">
                    <div class="card-body bg-gradient-primary">
                        <div class="mb-5 text-center text-primary">
                            <h3 class="fs-2">Update Admin Form</h3>
                        </div>
                        <form action="{{route('users.update',$user->id)}}" method="post">
                            @csrf
                            @method('PUT')
                            @error('fail')
                                <div class="alert bg-danger alert-danger mt-1 mb-2">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="" class="form-label">Name</label>
                                <input type="text" value="{{$user->name}}" name="name" class="form-control" placeholder="Enter Name">
                                @error('name')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Email</label>
                                <input type="email" value="{{$user->email}}" name="email" class="form-control" placeholder="Enter Email">
                                @error('email')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Phone</label>
                                <input type="number" value="{{$user->phone}}" name="phone" class="form-control" placeholder="Enter Phone">
                                @error('phone')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="hidden"  name="role" value="{{$user->role}}" class="form-control" >
                            </div>

                            <div class="form-group text-center">
                                <button class="btn btn-secondary back-btn text-dark">Cancel</button>
                                <button class="btn btn-success text-dark" type="submit">Update</button>
                            </div>
                        </form>
                    </div>
                   </div>
                </div>
            </div>
        </div>
      </div>

    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <footer class="footer">
      <div class="container-fluid d-flex justify-content-between">
        <span class=" d-block text-center text-sm-start d-sm-inline-block">@Copyright 2023 By Wai Phyo Aung</span>
        <span class="float-none float-sm-end mt-1 mt-sm-0 text-end"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin template</a> from Bootstrapdash.com</span>
      </div>
    </footer>
    <!-- partial -->
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function(){

      })
    </script>
@endsection



