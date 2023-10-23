
@extends('frontend.layouts.app')

@section('title','Transfer Confirmation')

@section('content')
    <div class="transfer mb-3">
        <div class="card">
            <div class="card-body">
                <form action="{{route('transfer_confirm')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="" class="mb-0"><strong>From</strong></label>
                        <p class="mb-1 text-muted">{{$from_account->name}} </p>
                        <p class="mb-1 text-muted">{{$from_account->phone}}</p>
                    </div>
                    <div class="form-group">
                        <label for="" class="mb-0"><strong class="me-2">To</strong></label>
                        <p class="mb-1 text-muted">{{$to_account->name}}</p>
                        <p class="mb-1 text-muted">{{$to_account->phone}} </p>
                    </div>
                    <div class="form-group">
                        <label for="" class="mb-0"><strong>Amount (MMK)</strong></label>
                        <p class="mb-1 text-muted">{{number_format($amount)}} </p>
                    </div>
                    <div class="form-group">
                        <label for="" class="mb-0"><strong>Description</strong></label>
                        <p class="mb-1 text-muted">{{$description}} </p>
                    </div>
                        <button type="submit" class="form-control btn btn-theme btn-block btn-primary mt-3">Confirm</button>
                </form>
            </div>
        </div>
    </div>
@endsection
