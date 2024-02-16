@extends('admin.master')
@section('title', 'Create Permission')
@section('breadcrumb') Add Permission @endsection
@section('content')




<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Add New Permission</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
<!--                <div class="card-header">Profile Information</div>
-->                <div class="card-body">
                    <form method="POST" action="{{ route('save.permission') }}" aria-label="{{ __('Add') }}"  enctype="multipart/form-data">
                     
                        @csrf

                        <div class="form-group">
                            <label for="name" >{{ __('Name') }}</label>

                            <div >
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required >

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label for="email" >{{ __('Slug') }}</label>

                            <div >
                                <input id="email" type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug" value="{{ old('slug') }}" required>

                                @if ($errors->has('slug'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('slug') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        

                        <div class="form-group mb-0">
                            <div class="">
                                <button type="submit" class="bl-btn">
                                    {{ __('Add') }}
                                </button>
								</div>
                        </div>
                    </form>
                  
                  
                  
                    @include('admin.UserButtons')
                  
                  
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>






@endsection