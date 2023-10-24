@extends('frontend.layouts.app')

@section('title', 'Transfer Confirmation')

@section('content')
    <div class="transfer mb-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('transfer_complete') }}" method="post" id="form">
                    @csrf
                    <input type="hidden" name="to_phone" value="{{ $to_account->phone }}">
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <input type="hidden" name="description" value="{{ $description }}">
                    <div class="form-group">
                        <label for="" class="mb-0"><strong>From</strong></label>
                        <p class="mb-1 text-muted">{{ $from_account->name }} </p>
                        <p class="mb-1 text-muted">{{ $from_account->phone }}</p>
                    </div>
                    <div class="form-group">
                        <label for="" class="mb-0"><strong class="me-2">To</strong></label>
                        <p class="mb-1 text-muted">{{ $to_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $to_account->phone }} </p>
                    </div>
                    <div class="form-group">
                        <label for="" class="mb-0"><strong>Amount (MMK)</strong></label>
                        <p class="mb-1 text-muted">{{ number_format($amount) }} </p>
                    </div>
                    <div class="form-group">
                        <label for="" class="mb-0"><strong>Description</strong></label>
                        <p class="mb-1 text-muted">{{ $description }} </p>
                    </div>
                    <button type="submit"
                        class="form-control confirm-btn btn btn-theme btn-block btn-primary mt-3">Confirm</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.confirm-btn').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Please fill password !',
                    icon: 'info',
                    html: '<input type="password" name="password" class="form-control text-center password">',
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        var password = $('.password').val();
                        $.ajax({
                            url: '/password_check?password=' + password,
                            type: 'GET',
                            success: function(res) {
                                if (res.status == 'success') {
                                    $('#form').submit();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: res.message,

                                    })
                                }
                            }

                        })
                    }
                })
            })
        })
    </script>
@endsection
