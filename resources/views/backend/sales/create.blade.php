@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Add New Order') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('orders.store_manual_order') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="name">
                            {{ translate('Name') }} <span class="text-danger">*</span>
                        </label> 
                        <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" name="name" value="{{ old('name') }}" placeholder="{{ translate('Name') }}" required>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">
                            {{ translate('Name') }} <span class="text-danger">*</span>
                        </label> 
                        <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" name="name" value="{{ old('name') }}" placeholder="{{ translate('Name') }}" required>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif 
                    </div>
                </div> 
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
