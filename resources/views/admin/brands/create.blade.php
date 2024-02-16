@extends('admin.master')
@section('title', 'Create Brand')
@section('breadcrumb') Create Brand @endsection
@section('content')




@if (count($errors) > 0)
        <div class="alert alert-danger">
          
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Create Brand</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
               <div class="card-body">
<form method="POST" action="{{ route('brands.create') }}"  enctype="multipart/form-data">
                   
                     @csrf
                     
                     <div class="form-group">
                         <label for="name" >{{ __('Name') }} <span class="color_red">*</span></label>

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
                         <label for="file" >{{ __('Logo') }}</label><span class="text-danger">(Max Image dimension width:150 x height:100 pixel, Max: 1MB)</span>

                         <div >
                         <input type="file" name="image" class="form-control" accept="image/png, image/gif, image/jpeg" />
                         @if ($errors->has('image'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('image') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>
                     
                    <div class="form-group mb-0">
                         <div class="">
                             <button type="submit" class="bl-btn">
                                 {{ __('Save') }}
                             </button>
                             </div>
                     </div>
                 </form>
                  
                  
                  
                  
                  
                  
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>






@endsection