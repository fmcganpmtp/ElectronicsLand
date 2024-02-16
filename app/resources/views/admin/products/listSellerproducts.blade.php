@extends('admin.master')
@section('title', 'Seller Products List')
@section('breadcrumb') Seller Products @endsection
@section('content')

<div class="body flex-grow-1 px-3">

    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
     
	 <div class="row"><div class="col-lg-8 col-12">
        <h2>Seller Products</h2> </div>
                
				</div>
        @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <div class="card-body">
                    
                    </div>
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                
                
                <div class="row">

                     <div class="col-lg-3 col-12">
                      <label> Keyword search:</label>
                      <input type="search" id="search_key"  placeholder="Search...." class="form-control" value={{$search_key}}>
                     
                     </div>
                    
                    <div class="col-lg-3 col-12">
                     <div class="form-group">
                      <label>Category:</label>
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
                      <label>Stock</label>
                      <select id="stock" class="form-control">
                      <option value="">None</option>
                      <option value="instock" <?php if($stock=='instock') echo "selected";?>>Instock</option>
                      <option value="outstock" <?php if($stock=='outstock') echo "selected";?>>Out of stock</option>
                      </select>
                    </div>
                    </div>
                   <div class="col-lg-4 col-12">
                      <div class="float-btn top-mrg">
                        <button type="button" id="btnsubmit" value="search" class="bl-btn flt-right"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                         <button type="button" onclick="fnbulkdelete('products',seller_products)" class="btn btn-outline-danger">Delete selected</button>
                       </div>
                    </div>
  
					</div>
					

                <div class="card-body">
                  <div class="tableC">
                  
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th> <div class="custom-control custom-checkbox">
                           <input type="checkbox" class="custom-control-input" id="customCheck">
                            <label class="custom-control-label" for="customCheck"> Sl No</label>
                            </div></th>
                          <th>Product Description</th>
                          <th>Company Name</th>
                          <th>Stock Location</th>
                          <th>Price</th>
                          <th>Category</th>
                          <th>Image</th>
                          <th>Featured Product</th>
                          <th>Special Season</th>
                          <th>Visibility</th>
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
  </div>

  <script src="{{asset('/admin1/js/datatable.js')}}"></script>
  <script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script>
var seller_products=[];
var $ = jQuery;
  (function($) {
  $(document).ready( function () {

    $("#customCheck").click(function(){
      
       if($("#customCheck").is(':checked'))
        {  
         seller_products=[]; 
         $(".clsallcheck").prop('checked',true);
         $(".clsallcheck").each(function() {
            seller_products.push(parseInt($(this).attr('id')));
          });
       }
      else
        {
          $(".clsallcheck").prop('checked',false);
           seller_products=[];
        }
      
    });
    var id = '12';
    var editurl="{{route('edit.product', ':id')}}";
    var deleteurl="";
    var viewurl="{{route('view.Sellerproduct', ':id')}}";

    $('#btnsubmit').click(function(){
    dataTable.draw();
    });
    $("#search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
     }
    });
   var checked=unchecked=""
   var dataTable = $('#datatable').DataTable({
        "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
         'searching': false,
         "lengthChange": false,
         "order": [ 0,'desc'],
         'ajax': {
          'url':"{{ url('getSellerProductlist') }}",
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
           return '<div class="custom-control custom-checkbox"><input id="'+full.id+'" type="checkbox" class="custom-control-input clsallcheck"  onclick="fnPushId('+full.id+')">'+(meta.row + meta.settings._iDisplayStart + 1)+'</div>';
     
      }
    },
    
    {
       "targets":7, 
       "orderable": false,
      
    },
    {
       "targets":8, 
       "render": function(data,type,full,meta)
      {

        if(full.featured_product=="Yes")
          checked="checked";
        else
          checked="";

        if(full.featured_product=="No")
          unchecked="checked";
        else
          unchecked="";
         return  '<div class="switch-field"><input type="radio" id="radio-'+full.id+'" name="switch-'+full.id+'" value="Yes" '+checked+' onchange="activateslider('+full.id+')" /><label for="radio-'+full.id+'">On</label><input type="radio" id="radio-1'+full.id+'" name="switch-'+full.id+'"  value="No" '+unchecked+' onchange="activateslider('+full.id+')"/> <label for="radio-1'+full.id+'">Off</label></div>';
      }
    }
    ,
     {
       "targets":9, 
       "render": function(data,type,full,meta)
      {

        if(full.special_season=="Yes")
          checked="checked";
        else
          checked="";

        if(full.special_season=="No")
          unchecked="checked";
        else
          unchecked="";
         return  '<div class="switch-field"><input type="radio" id="radio1-'+full.id+'" name="switch1-'+full.id+'" value="Yes" '+checked+' onchange="activateSpecialSeason('+full.id+')" /><label for="radio1-'+full.id+'">On</label><input type="radio" id="radio1-1'+full.id+'" name="switch1-'+full.id+'"  value="No" '+unchecked+' onchange="activateSpecialSeason('+full.id+')"/> <label for="radio1-1'+full.id+'">Off</label></div>';
      }
    }
    ,
     {
       "targets":10, 
       "render": function(data,type,full,meta)
      {

        if(full.product_visibility=="Yes")
          checked="checked";
        else
          checked="";

        if(full.product_visibility=="No")
          unchecked="checked";
        else
          unchecked="";
         return  '<div class="switch-field"><input type="radio" id="radio_visi-'+full.id+'" name="switch_visi-'+full.id+'" value="Yes" '+checked+' onchange="fnproduct_visbility('+full.id+')" /><label for="radio_visi-'+full.id+'">On</label><input type="radio" id="radio_visi-1'+full.id+'" name="switch_visi-'+full.id+'"  value="No" '+unchecked+' onchange="fnproduct_visbility('+full.id+')"/> <label for="radio_visi-1'+full.id+'">Off</label></div>';
      }
    },
      {
       "targets":11, 
       "orderable": false,
       "data":"id",
       "render": function(data,type,full,meta)
      {
        editurl = editurl.replace(':id', '');
        viewurl = viewurl.replace(':id', '');
        return '<div class="icon-bx"> <a href="'+viewurl+data+'"><i class="fa fa-eye" aria-hidden="true"></i></a>  <a href="JavaScript:void(0);" onclick="deleteconfirm('+data+')"><i class="icon cil-trash"></i></a> </div>';     
      }
    }
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'name' },
          { data: 'company_name' } ,  
          { data: 'location' },
          { data: 'product_price' },
          { data: 'cat_name' },
          { data: 'strimg' },
          { data: 'featured_product' }, 
          { data: 'special_season' }, 
          { data: 'product_visibility' }, 
         ]
      });     
  });
})(jQuery);


	 function activateSpecialSeason(id){ 

    var status=$("input[name='switch1-"+id+"']:checked").val();
    swal({
    title:"Do you want to continue ? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
       $(".loaderajax").show();
      $.ajax({
         url: "{{url('updatesellerseasonproduct')}}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  id: id,
                  status: status,
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
                 $(".loaderajax").hide();
              if(data)
                  swal("Featured Product Status Changed", "", "success");
              else
                  swal("Error On Submission", "", "error");
                
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
               $(".loaderajax").hide();
            swal(errorThrown, "", "error");
          }  

        })
    } 
    else {
        
           var cur_status=$("input[name='switch1-"+id+"']:checked").val();
           if(cur_status=='Yes')
              {
                $("#radio1-"+id).prop('checked',false);
                $("#radio1-1"+id).prop('checked',true);
              }
           else
              {
                $("#radio1-"+id).prop('checked',true);
                $("#radio1-1"+id).prop('checked',false);
              }
          
        }
    });  

  }

function fnbulkdelete(){

   
  if(seller_products.length>0)
    {
  swal({
    title:"Do you  want to Delete Product?",
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
         $(".loaderajax").show();
          $.ajax({
              url: "{{ url('vendorproductdelete') }}",
                  type: "post",
                  data:{ 
                      _token:'{{ csrf_token() }}',
                        seller_products: seller_products,
                        action_type:'products'
                      },
                  async:true,
                  cache: false,
                  dataType: 'json',
                  success: function(data){
                        $(".loaderajax").hide();
                        swal(data,'','success');
                        $("input:checkbox").prop('checked',"");
                        seller_products=[]; 
                        location.reload();
                } ,
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                     $(".loaderajax").hide();
                  swal("Error: "+errorThrown ,'','error'); 
                }  
      
              })  ;
      }
          
    }); 
  } 
  else
    swal("Please Select At Least One Item",'','error'); 
   


}

function fnPushId(id){
  var exists = seller_products.includes(id)
  if (exists) 
    seller_products= seller_products.filter((c) => { return c !== id })
   else
    seller_products.push(id)
}


  function fnproduct_visbility(id){

    var status=$("input[name='switch_visi-"+id+"']:checked").val();
    swal({
    title:"Do you want to continue ? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
       $(".loaderajax").show();
      $.ajax({
         url: "{{url('updateselerproductvisibility')}}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  id: id,
                  status: status,
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
                 $(".loaderajax").hide();
              if(data)
                  swal("Product Visibility Changed", "", "success");
              else
                  swal("Error On Submission", "", "error");
                
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
               $(".loaderajax").hide();
            swal(errorThrown, "", "error");
          }  

        })
    } 
    else {
        
           var cur_status=$("input[name='switch_visi-"+id+"']:checked").val();
           if(cur_status=='Yes')
              {
                $("#radio_visiradio_visi-"+id).prop('checked',false);
                $("#radio_visi-1"+id).prop('checked',true);
              }
           else
              {
                $("#radio_visi-"+id).prop('checked',true);
                $("#radio_visi-1"+id).prop('checked',false);
              }
          
        }
    });  

  }

  function activateslider(id){

    var status=$("input[name='switch-"+id+"']:checked").val();
    swal({
    title:"Do you want to continue ? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
       $(".loaderajax").show();
      $.ajax({
         url: "{{url('updatesellerfeaturedproduct')}}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  id: id,
                  status: status,
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
                 $(".loaderajax").hide();
              if(data)
                  swal("Featured Product Status Changed", "", "success");
              else
                  swal("Error On Submission", "", "error");
                
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
               $(".loaderajax").hide();
            swal(errorThrown, "", "error");
          }  

        })
    } 
    else {
        
           var cur_status=$("input[name='switch-"+id+"']:checked").val();
           if(cur_status=='Yes')
              {
                $("#radio-"+id).prop('checked',false);
                $("#radio-1"+id).prop('checked',true);
              }
           else
              {
                $("#radio-"+id).prop('checked',true);
                $("#radio-1"+id).prop('checked',false);
              }
          
        }
    });  

  }

function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('delete.Sellerproduct', ':id')}}".replace(':id', '')+data;

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

<style type="text/css">
  

.switch-field {
  display: flex;
  overflow: hidden;
}

.switch-field input {
  position: absolute !important;
  clip: rect(0, 0, 0, 0);
  height: 1px;
  width: 1px;
  border: 0;
  overflow: hidden;
}

.switch-field label {
  background-color: #e4e4e4;
  color: rgba(0, 0, 0, 0.6);
  font-size: 14px;
  line-height: 1;
  text-align: center;
  padding: 8px 16px;
  margin-right: -1px;
  border: 1px solid rgba(0, 0, 0, 0.2);
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
  transition: all 0.1s ease-in-out;
}

.switch-field label:hover {
  cursor: pointer;
}

.switch-field input:checked + label {
  background-color: #0b6eb1; color:#fff;
  box-shadow: none;
}
.switch-field label{box-shadow:none;background-color: #fff;}
.switch-field label:first-of-type {
  border-radius: 4px 0 0 4px;
}

.switch-field label:last-of-type {
  border-radius: 0 4px 4px 0;
}

</style>
 @endsection

