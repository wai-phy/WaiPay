
@extends('frontend.layouts.app')

@section('title','Transfer')

@section('content')
    <div class="transfer">
        <div class="card">
            <div class="card-body">
                <form action="{{route('transfer_confirm')}}" method="post" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <label for="">From</label>
                        <p class="mb-1 text-muted">{{$auth_user->name}} </p>
                        <p class="mb-1 text-muted">{{$auth_user->phone}}</p>
                    </div>
                    <div class="form-group">
                        <label for="">To</label>
                        <input type="text" value="{{old('to_phone')}}" name="to_phone" class="form-control @error('to_phone') is-invalid @enderror">
                        @error('to_phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Amount (MMK)</label>
                        <input type="number" value="{{old('amount')}}" name="amount" class="form-control @error('amount') is-invalid @enderror" >
                        @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" class="form-control" cols="10" rows="2" >{{old('description')}}</textarea>
                    </div>
                        <button type="submit" class="form-control btn btn-theme btn-block btn-primary mt-3">Continue</button>
                </form>
            </div>
        </div>
    </div>
@endsection
