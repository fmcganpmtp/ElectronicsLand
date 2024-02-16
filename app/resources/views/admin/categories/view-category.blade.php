@extends('admin.master')
@section('title', 'List Categories')
@section('breadcrumb') Category View @endsection
@section('content')
   



 
    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        @if(Session::has('success'))<p class="alert alert-success">{{Session::get('success')}}</p>@endif
        <h2>Category View</h2>
        <h3><img style="width:150px;" src ="{{ $category->category_pic ? asset('uploads/categoryImages/').'/'.$category->category_pic : asset('/uploads/defaultImages/pop-ic-4.png')}}">{{ $category->name}}</h3>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                	<div class="search-area wth-btn">
                    
                     <!-- <div class="form-group">
                      <input type="email" class="form-control" value="Search..">
                      <button type="submit"><i class="icon cil-search"></i></button>
                    </div> -->
                    
                    </div>
                    
                    <a href="{{ route('createCategory',['category_id' => $category->id ])}}" class="bl-btn flt-right">Create Sub category</a>
                    <a href="{{ route('category.list')}}" class="bl-btn flt-right" style="margin-right:20px;">Back to List</a>
                    
                </div>
                <div class="card-body">
                  <div class="tableC">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                         <th>Sl no</th>
                          <th>Category</th>
                           <th>Icon</th>
                          <th> Parent Category</th>
                        
                          <th> Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                      @if(isset($category))
                            <?php $_SESSION['i'] = 0; ?>
                            <?php $dash =''; ?>
                      
                      
                        @if(count($category->subcategory))
                                     @include('admin/categories/sub-category-list',['subcategories' => $category->subcategory])
                         @endif
                                
                      
                      
                      <?php unset($_SESSION['i']); ?>
                        @endif 
              
                      </tbody>
                    </table>
                  </div>
                  
                  
                  
                 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  


  <script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
  <script>
    function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('delete.category',':id')}}".replace(':id', '')+data;

  swal({
    title:"Do you really want to delete? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      window.location=deleteurl;
    } 
    else {
          
        }
    });  

}
</script>


@endsection