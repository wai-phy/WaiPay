@extends('backend.layouts.app')
@section('title', 'Add Amount')
@section('extra_css')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-wallet"></i>
                    </span>Add Amount
                </h3>
            </div>
            <div class="row ">
                <div class="card">
                    <div class="card-body">
                        @include('backend.layouts.flash')

                        <form action="{{route('add_amount_store')}}" method="post">

                            @csrf

                            <div class="form-group">
                                <label for="">User</label>
                                <select class="user_id form-control" name="user_id">
                                    <option value="">---- Please Choose ----</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }} ({{ $user->phone }}) </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Amount</label>
                                <input type="number" name="amount" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea name="description" class="form-control" cols="10" rows="2"></textarea>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-secondary back-btn text-dark">Cancel</button>
                                <button class="btn btn-success text-dark" type="submit">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <span class=" d-block text-center text-sm-start d-sm-inline-block">@Copyright 2023. All reserved By Wai
                    Pay</span>
                <span class="float-none float-sm-end mt-1 mt-sm-0 text-end">Developer By Wai Phyo Aung</span>
            </div>
        </footer>
        <!-- partial -->
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.user_id form-control').select2({
                placeholder: "---- Please Choose ----",
                allowClear: true,
                theme: 'bootstrap4',
                
            });
        })
    </script>
@endsection
