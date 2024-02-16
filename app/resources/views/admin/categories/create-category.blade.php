@extends('admin.master')
@section('title', 'Create Category')
@section('breadcrumb') Create Category @endsection
@section('content')




 
    
    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('success')) <div class="alert alert-success">{{Session::get('success') }}</div> @endif
       
        <h2>Create category</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
<!--                <div class="card-header">Profile Information</div>
-->                <div class="card-body">
<form method="POST" action="{{ route('saveCategory') }}" aria-label="{{ __('Register') }}"  enctype="multipart/form-data">
                   
                     @csrf
                     
                     <div class="form-group">
                         <label for="name" >{{ __('Category name') }} <span class="color_red">*</span></label>

                         <div >
                             <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required >

                             @if ($errors->has('name'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('name') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>
                 
                     <!-- <div class="form-group">
                         <label for="slug" >{{ __('Category Slug') }} <span class="color_red">*</span></label>

                         <div >
                             <input id="slug" type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug" value="{{ old('slug') }}" required>

                             @if ($errors->has('slug'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('slug') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div> -->
                    @if((app('request')->input('category_id'))==null)
                     <!-- <div class="form-group">
                         <label for="parent_id" >{{ __('Select parent category') }} <span class="color_red">*</span></label>

                         <div >
                             <select type="text" name="parent_id" class="form-control">
                                     <option value="">None</option>
                                     @if($categories)
                                         @foreach($categories as $category)
                                            <?php $dash=''; ?>
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                            @if(count($category->subcategory))
                                                 @include('admin/categories/subCategoryList-option',['subcategories' => $category->subcategory])
                                            @endif
                                         @endforeach
                                     @endif
                                 </select>
                             @if ($errors->has('parent_id'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('parent_id') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div> -->
                     @else
                     <input type="hidden" name="parent_id" value="{{app('request')->input('category_id')}}">
                     @endif
                     
                     <div class="form-group">
                         <label for="file" >{{ __('Category Icon') }} <span class="color_red">*</span></label>

                         <div >
                         <input type="file" name="image"  class="form-control" accept="image/png, image/gif, image/jpeg"  required>
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