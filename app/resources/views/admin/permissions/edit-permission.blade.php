@extends('admin.master')
@section('title', 'Edit Permissions')
@section('breadcrumb') Edit Permission @endsection
@section('content')




<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Admin Edit Permission</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
<!--                <div class="card-header">Profile Information</div>
-->                <div class="card-body">
                  
                    <form method="POST" action="{{ route('update.permission') }}" aria-label="{{ __('Register') }}" enctype="multipart/form-data">
                    
                        @csrf
                     <input type="hidden" name="permission_id" value="{{ $permission->id }}">
                        <div class="form-group">
                            <label for="name" >{{ __('Name') }}</label>

                            <div >
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $permission->name }}" required >

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label for="slug" >{{ __('Slug') }}</label>

                            <div >
                                <input  id="slug" type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug" value="{{  $permission->slug }}" required>

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
                                    {{ __('Update') }}
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
