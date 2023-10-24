@extends('frontend.layouts.app')

@section('title', 'Transfer')

@section('content')
    <div class="transfer">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('transfer_confirm') }}" method="get" autocomplete="off">
                    {{-- @csrf --}}
                    <div class="form-group">
                        <label for="">From</label>
                        <p class="mb-1 text-muted">{{ $auth_user->name }} </p>
                        <p class="mb-1 text-muted">{{ $auth_user->phone }}</p>
                    </div>
                    <div class="form-group">
                        <label for="">To <span class="text-success to-account-info"></span> </label>
                        <div class="input-group">
                            <input type="text" value="{{ old('to_phone') }}" name="to_phone"
                                class="form-control to_phone @error('to_phone') is-invalid @enderror">
                            <span class="input-group-text btn verify-btn" id="basic-addon2"><i
                                    class="fa-solid fa-check-circle"></i></span>
                        </div>
                        @error('to_phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Amount (MMK)</label>
                        <input type="number" value="{{ old('amount') }}" name="amount"
                            class="form-control @error('amount') is-invalid @enderror">
                        @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" class="form-control" cols="10" rows="2">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="form-control btn btn-theme btn-block btn-primary mt-3">Continue</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.verify-btn').on('click', function() {
                var phone = $('.to_phone').val();
                $.ajax({
                    url: '/to-account-info?phone=' + phone ,
                    type: 'GET',
                    success: function(res){
                        if(res.status == 'success'){
                            $('.to-account-info').text('('+res.data['name']+')');
                        }else{
                            $('.to-account-info').text('('+res.message+')');
                        }
                    }

                })
            })
        })
    </script>
@endsection
