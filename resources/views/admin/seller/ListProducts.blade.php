@extends('layouts.template')
@section('title', 'List Seller Products')
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{asset('/js/datatable.js')}}"></script>
<style>
  table.dataTable thead .sorting_asc::after, table.dataTable thead .sorting::after  { position: relative !important;     top: 0px; }
</style>
<sectiion class="seller-page-new no-bg">
  <div class="pr-banner">
    <!--<img src="assets/images/pr-banner.jpg">-->
  </div>
  <div class="container">
    <div class="row"> </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom">
          <div class="card wow fadeInUp">
            <div class="slr-details">
              <div class="pr-logo active-border">
                <?php
                if(!empty(Auth::guard('user')->user()->profile_pic)) $img_path = asset('/uploads/userImages/').'/'.Auth::guard('user')->user()->profile_pic;
                else  $img_path = asset('uploads/defaultImages/default_avatar.png'); ?>
              <img src="{{ $img_path }}">
                <!--not-active-border-->
                <!--                <div class="vrfd"><i class="fa fa-check" aria-hidden="true"></i> </div>
-->
              </div>
              <h2>{{ Auth::guard('user')->user()->store_name ?? ''}} </h2>
			  
			  <div class="row ">
              <div class="col-lg-12 col-12">
                <h3>Bulk upload
</h3></div>
              
              </div>
			  
			  
              <div class="row dwd-exel">
            	<div class="col-lg-6 col-12">
                
                	<div class="brd">
               <h4 class="h4-title">Download sample excel</h4>
                
                <ul>
                <li>Lorem ipsum dolor sit amet</li>
                <li> consectetur adipiscing</li>
                <li>  elit, sed do eiusmod </li>
                <li>  tempor incididunt ut labore et </li>
                <li>  dolore magna aliqua. Ut enim ad minim veniam,</li>
                <li>   quis nostrud exercitation ullamco laboris nisi</li>
                 <li>   ut aliquip ex ea commodo consequat. </li>
                  <li>  Duis aute irure dolor in reprehen</li>
             </ul>
             
             <a href="{{ asset('/excel/').'/'.'sample.csv' }}" class="default-btn"> <i class="fa fa-file-excel-o" aria-hidden="true"></i>
Download</a>
                </div>
                </div>
                <div class="col-lg-6 col-12 c22">
                
                <div class="fupd">
                <h4 class="h4-title">Upload your products</h4>
                
                
                <form method="post" action="{{ route('import-exl') }}"  enctype="multipart/form-data" >
                  @csrf
                  @if (count($errors) > 0)
                
                      <div class="alert alert-danger alert-dismissible">
                         
                          <h6><i class="icon fa fa-ban"></i> Error!</h6>
                          @foreach($errors->all() as $error)
                          {{ $error }} <br>
                          @endforeach      
                      </div>
                    
                @endif
  
                @if (Session::has('success'))
                    
                        <div class="alert alert-success alert-dismissible">
                           
                            <h6>{!! Session::get('success') !!}</h6>   
                        </div>
                      
                @endif
                <div class="upload-c">
                <div class="file-drop-area form-group">
                      <input type="file"  name="file" class="file-input form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required >
                      
                              <button class="default-btn"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>

Upload</button>
                    </div>
                
                
                </div>
				</form>
				</div>
             
                </div>
            </div>
            </div>
          </div>
          
          <div class="card  tp-30 wow fadeInUp">
<div class="row">
              <div class="col-lg-12 col-12">
                <h3>Product list
</h3></div>
            <div class="row srch-top">
              <div class="col-lg-4 col-12">
                <input type="search" id="search_key"  placeholder="Search...." class="form-control" >
              </div>
              <div class="col-lg-2 col-12">
                <div class="form-group">
                  <select type="text" id="category_id"  class="form-control" >
                                        <option value="">None</option>
                                        @if($categories)
                                            @foreach($categories as $item)
                                                <?php $dash=''; ?>
                                                <option value="{{$item->id}}" >{{$item->name}}</option>
                                                @if(count($item->subcategory))
                                                @include('admin/products/subCategory',['subcategories' => $item->subcategory])
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                </div>
              </div>
              <div class="col-lg-2 col-12">
                <div class="form-group">
                  <select id="stock" class="form-control">
                      <option value="">None</option>
                      <option value="instock" >Instock</option>
                      <option value="outstock" >Out of stock</option>
                      </select>
                </div>
              </div>
              
              <div class="col-lg-4 col-12">
              <button type="button"  id="btnsearch" class="btn btn-success btn-mrg"><i class="fa fa-search" aria-hidden="true"></i>Search</button>

              <div class="btn-right">
              
         
              <a href="{{ route('add.seller.product')}}" class="btn btn-outline-primary">Add new product</a>
              
       <!--  <a href="seller-Bulk-upload.php" class="btn btn-outline-info">Bulk upload</a> -->


</div>
              </div>
              
              <div class="col-lg-3 col-12">
               
              </div>
            </div>
            
          </div>
			
			
			
			
            <div class="tableC">
                    <table  id="datatable" class="table table-striped table-bordered datatable" data-page-length='20' cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th></th>
                    <th>Sl no</th>
                    <th>Category</th>
                    <th>Sub category</th>
                    <th>Product name</th>
                    <th>Image</th>
                    <th>Time and date</th>
                    <th> Actions</th>
                  </tr>
                </thead>
                
              </table>
            </div>
            
            
          
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</sectiion>


      

@endsection
@section('footer_script')


<script type="text/javascript">
 
 var $ = jQuery;
  (function($) {


    var id = '12';
    var editurl="{{route('edit.sellerProduct', ':id')}}";
    var deleteurl="{{route('delete.Sproduct', ':id')}}";
    var viewurl="{{route('view.Sproduct', ':id')}}";

    $('#btnsearch').click(function(){
    dataTable.draw();
    });
    $("#search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
     }
    });
   var dataTable = $('#datatable').DataTable({
         "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
         'searching': false,
         "lengthChange": false,
         "order": [ 0,'desc'],
         'ajax': {
          'url':"{{ url('getSellerproductlist') }}",
          'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $("#search_key").val();
          data.category_id =$("#category_id").val();  
          data.stock = $("#stock").val(); 
        },
      
    }, 
   
    "columnDefs":[
      {
       "targets":0, 
       "orderable": true,
       "visible":false
      },
      {
       "targets":1, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
         return meta.row + meta.settings._iDisplayStart + 1;
      }
    },
    {
       "targets":[2,3,5,7], 
       "orderable": false,
      
    },
     {
       "targets":7, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        var str="'Do you really want to delete?'";
        editurl = editurl.replace(':id', '');
        deleteurl = deleteurl.replace(':id', '');
        viewurl = viewurl.replace(':id', '');
        return '<div class="icon-bx"> <a href="'+viewurl+full.id+'" class="view-ic"><i class="fa fa-eye" aria-hidden="true"></i></a> <a href="'+editurl+full.id+'" class="edit-ic"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a href="'+deleteurl+full.id+'" onclick="return confirm('+str+')" class="dlt-ic"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>';     
      }
    }
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'strcat_parent' },
          { data: 'strcat' } ,  
          { data: 'name' },
          { data: 'strimg' },
          { data: 'created_at' },
          { data: '' },
           
         ]
      });     
 })(jQuery);
</script>


@endsection