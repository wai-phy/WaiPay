@extends('frontend.layouts.app')

@section('title', 'Scan & Pay Transfer')

@section('content')
    <div class="scan-pay-transfer">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('scan_and_pay_transfer_confirm') }}" method="get" id="form-submit" autocomplete="off">
                    {{-- @csrf --}}
                    @include('frontend.layouts.flash')
                    <div class="form-group">
                        <label for="">From</label>
                        <p class="mb-1 text-muted">{{ $from_account->name }} </p>
                        <p class="mb-1 text-muted">{{ $from_account->phone }}</p>
                    </div>
                    <input type="hidden" name="hash_value" class="hash-value" value="">
                    <input type="hidden" name="to_phone" class="to_phone" value="{{ $to_account->phone }}">
                    <div class="form-group">
                        <label for="">To</label>
                        <p class="mb-1 text-muted">{{ $to_account->name }} </p>
                        <p class="mb-1 text-muted">{{ $to_account->phone }}</p>
                    </div>
                    <div class="form-group">
                        <label for="">Amount (MMK)</label>
                        <input type="number" value="{{ old('amount') }}" name="amount"
                            class="form-control amount @error('amount') is-invalid @enderror">
                        @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" class="form-control description" cols="10" rows="2">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="form-control submit-btn btn btn-theme btn-block btn-primary mt-3">Continue</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
           
            $('.submit-btn').on('click',function(e){
                e.preventDefault();

                var to_phone = $('.to_phone').val();
                var amount = $('.amount').val();
                var description = $('.description').val();

                $.ajax({
                    url: `/transfer_hash?to_phone=${to_phone}&amount=${amount}&description=${description}` ,
                    type: 'GET',
                    success: function(res){
                        if(res.status == 'success'){
                           $('.hash-value').val(res.data);
                           $('#form-submit').submit();
                        }
                    }

                })

            })
        })
    </script>
@endsection
