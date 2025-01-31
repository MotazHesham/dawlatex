@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Edit Seller Information')}}</h5>
</div>

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Seller Information')}}</h5>
        </div>

        <div class="card-body">
          <form action="{{ route('sellers.update', $shop->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" value="{{$shop->user->name}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email Address')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Email Address')}}" id="email" name="email" class="form-control" value="{{$shop->user->email}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="client_identity">{{ translate('Client Identity')}}</label>
                    <div class="col-sm-9">
                        <select name="client_identity" id="client_identity" class="form-control rounded-0" required>
                            <option value >أختر الهوية</option>
                            <option value="مصنع" @if($shop->user->client_identity == 'مصنع') selected @endif>مصنع</option>
                            <option value="تاجر" @if($shop->user->client_identity == 'تاجر') selected @endif>تاجر</option>
                            <option value="وسيط" @if($shop->user->client_identity == 'وسيط') selected @endif>وسيط</option>
                        </select> 
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
