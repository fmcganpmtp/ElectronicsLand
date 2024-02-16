@extends('admin.master')
@section('title', 'Edit Category')
@section('breadcrumb') Edit Category @endsection
@section('content')




<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        @if(Session::has('success')) <div class="alert alert-success">{{Session::get('success') }}</div> @endif
        <h2>Edit Category</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
              <div class="card-body">
                  
                    <form method="POST" action="{{ route('update.category') }}" aria-label="{{ __('Register') }}" enctype="multipart/form-data">
                    
                        @csrf
                     <input type="hidden" name="user_id" value="{{ $category->id }}">
                        <div class="form-group">
                            <label for="name" >{{ __('Name') }}  <span class="color_red">*</span></label>

                            <div >
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $category->name }}" required >
                                
                                <input  type="hidden"  name="cat_id" value="{{ $category->id }}" >
                                
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    
                        
                        <!-- <div class="form-group">
                            <label for="slug" >{{ __('Slug') }}  <span class="color_red">*</span></label>

                            <div >
                                <input  id="slug" type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug" value="{{  $category->slug }}" required >

                                @if ($errors->has('slug'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('slug') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->
                        
                        
                       
                        <div class="form-group">
                            <label for="phone" >{{ __('Parent Category') }} <span class="color_red">*</span></label>
 
                            <div >
							
							
							<select type="text" name="parent_id" class="form-control">
                                        <option value="">None</option>
                                        @if($categories)
                                            @foreach($categories as $item)
                                                <?php $dash=''; ?>
                                                <option value="{{$item->id}}" @if($category->parent_id == $item->id ) selected @endif>{{$item->name}}</option>
                                                @if(count($item->subcategory))
                                                @include('admin/categories/sub-category-list-option-for-update',['subcategories' => $item->subcategory])
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
                        </div>


                        
                        <div class="form-group">
                        <img style=" width:100px !important;" class="pr_img" src="{{$category->category_pic ?URL::asset('/uploads/categoryImages/').'/'.$category->category_pic: asset('/uploads/defaultImages/pop-ic-4.png')}}
                        ">
                        <br><br>
                            <label for="file" >{{ __('Category Icon') }} <span class="color_red">*</span></label>

                            <div >
							<input type="file" name="image" accept="image/png, image/gif, image/jpeg"   class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}">
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
                                    {{ __('Update') }}
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
