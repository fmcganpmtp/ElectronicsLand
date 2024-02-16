@extends('admin.master')
@section('title', 'Vendor Products List')
@section('breadcrumb') Products @endsection
@section('content')

<div class="body flex-grow-1 px-3">
  
    <div class="loaderajax loaderajaxcntr" style="display: none;"><span id="loadcntr" style="color:red;font-size:20px"></span><img  src="{{asset('img/Loading_icon.gif')}}"/></div>
    <div class="container-lg">
      <div class="card-out mb-4 inner-form cr-req">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
     
        <h2> product create request</h2>
        <div class="card-body ">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
              
              
              
              <div class="card-header">
                 
                    
                  
                  <div class="row">
                     <div class="col-lg-3 col-12">
                      <label>Key word:</label>
                      <input type="text" id="search_key" class="form-control">
                    </div>
                    
                    <div class="col-lg-3 col-12">
                      <div class="form-group">
                        <label>Seller :</label>
                         <select type="text" id="user_id"  class="form-control" >
                                        <option value="">None</option>
                                       @foreach($User as $item)
                                                <?php $dash=''; ?>
                                                <option value="{{$item->id}}" >@if(!empty($item->company_name)){{$item->company_name}}&nbsp;(@endif  {{$item->name}}@if(!empty($item->company_name)) ) @endif</option>
                                       @endforeach
                          </select>
                      </div>
                    </div>
                    <div class="col-lg-3 col-12">
                     <div class="form-group">
                      <label>Category:</label>
                      <select type="text" id="category_id"  class="form-control" >
                                        <option value="">None</option>
                                        @if($categories)
                                            @foreach($categories as $item)
                                                <?php $dash='';
                                                $uplevel=$item->name;?>
                                                <option value="{{$item->name}}" >{{$item->name}}</option>
                                                @if(count($item->subcategory))
                                                @include('admin/products/subCategory_name',['subcategories' => $item->subcategory,'uplevel'=>$uplevel])
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                    </div>
                     </div>
                    
                   
                    <div class="col-lg-2 col-12">
                      <button type="button" id="btnsubmit" class="bl-btn flt-right top-mrg"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                    </div>
                    </div>
                    <div class="row">
                     <div class="col-lg-12 col-12">
                      <div class="form-group">
                         <button type="button" onclick="fnChangStatus('active')" class="btn btn-outline-success">Approve selected</button>
                         <button type="button" onclick="fnChangStatus('rejected')" class="btn btn-outline-danger">Reject selected</button>
                          <button type="button" onclick="fnbulkdelete()" class="btn btn-outline-info">Delete selected</button>
                          <button type="button" onclick="fnChangStatus('addcategory')" class="btn btn-outline-primary">Add Category</button>
                      </div>
                    </div>

                  </div>
                  
                  
                </div>
              
              
              
                <div class="card-body">
                  <div class="tableC pro-cr-req">
        <table id="datatable" class="table  table-bordered" data-page-length='50' cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th><div class="custom-control custom-checkbox">
                           <input type="checkbox" class="custom-control-input" id="customCheck1">
                            <label class="custom-control-label" for="customCheck1"> </label>
                            </div>Sl no</th>
                          <th>Product Description</th>
						  <th>Category</th>
                          <th>Image</th>
                          <th>Company Name</th>
                          <th>Seller</th>
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

  <div class="modal fade" id="Mymodal" tabindex="-1" aria-labelledby="MymodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MymodalLabel">Assign Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="fnclosepopup1()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
     <div class="form-group">
          <input type="hidden" id="hdncategory_id" value="0">
          <label>Parent Category</label>
          <br>
          <select class="form-control" id="parent_category" required>
            <option value="">Select</option>
            @if($categories)
                      @foreach($categories as $item)
                          <option value="{{$item->id}}">{{$item->name}}</option>
                      @endforeach
            @endif
          </select>
          <br>
           <div id="divsubcategory">
           <label>Sub Category: </label>
           <select class="form-control" id="ddsubcategory" >
            <option></option>
           </select>
          </div> 
          <br>
          <div id="divsubcategory1">
           <label>Next Sub Category: </label>
           <select class="form-control" id="dropsubcategory1" onchange="fnlastdropdown()">
            <option></option>
           </select>
          </div> 
     </div>
     <br>
          <button  type="submit" onclick="fninitupdate()" class="bl-btn mar-10">SUBMIT</button>
        
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>

  <script src="{{asset('/admin1/js/datatable.js')}}"></script>
  <script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script>
  var button_status="";
  var seller_products=[];
  var Active="'active'";
  var Pending="'rejected'";
  var counter=0;
  var $ = jQuery;
  (function($) {
  $(document).ready( function () {

    
    $(document).on("keydown", disableButtonsDown);
    $("#customCheck1").click(function(){
      
       if($("#customCheck1").is(':checked'))
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
      
    })


    var id = '12';
    var editurl="{{route('vendor_product.edit', ':id')}}";
    var viewurl="{{route('vendor_product.view', ':id')}}";

    $('#datatable').on('page.dt', function() {
       seller_products=[];
       $("#customCheck1").prop('checked',false);
    });                   
    
    $('#btnsubmit').click(function(){
    dataTable.draw();
    });


     $('#parent_category').on('change',function(e){
            
            var cat_id = e.target.value; 
            $("#hdncategory_id").val(cat_id);
            var bse_url = "{{URL::to('/')}}";
            $('#divsubcategory1').hide();
            $('#divsubcategory').hide();
            $.get(bse_url+'/ajax-subcat?cat_id='+ cat_id,function(data){
                var subcat =  $('#ddsubcategory').empty();
        subcat.append('<option value ="">Select</option>');
        if(data.length>0){
                $.each(data,function(create,subcatObj){
          $('#divsubcategory').show();
                    var option = $('<option/>', {id:create, value:subcatObj});
                    subcat.append('<option value ="'+subcatObj.id+'">'+subcatObj.name+'</option>');
                });
        }
        else{
          $('#divsubcategory').hide();
        }
        
            });
        });


        $('#ddsubcategory').on('change',function(e){
           
            var cat_id = e.target.value; 
            if(cat_id=='')
            { 
              var newdata=$('#parent_category option:selected').val();
              $("#hdncategory_id").val(newdata);
            }
            else
              $("#hdncategory_id").val(cat_id);
            var bse_url = "{{URL::to('/')}}";
            $.get(bse_url+'/ajax-subcat?cat_id='+ cat_id,function(data){
                var subcat =  $('#dropsubcategory1').empty();
                subcat.append('<option value ="">Select</option>');
                if(data.length>0){
                  
                $.each(data,function(create,subcatObj){
                  $('#divsubcategory1').show();
                   var option = $('<option/>', {id:create, value:subcatObj});
                    subcat.append('<option value ="'+subcatObj.id+'">'+subcatObj.name+'</option>');
                });
                }
                else
                  $('#divsubcategory1').hide();
                
                
            });
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
          'url':"{{ url('getvendorproductlist') }}",
          'data': function(data){
          _token="{{csrf_token()}}";
           data.search_key = $("#search_key").val();
           data.category_id =$("#category_id").val();  
           data.country_id = $("#country_id").val(); 
           data.user_id = $("#user_id").val(); 
        },
      
    },   
   
    "columnDefs":[
    {
       "targets":0, 
       "orderable": true,
       "visible":false
      },
       {
       "targets":[4], 
       "orderable": false,
      },
	  
      {
       "targets":1, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
         
         return '<div class="custom-control custom-checkbox"><input id="'+full.id+'" type="checkbox" class="custom-control-input clsallcheck"  onclick="fnPushId('+full.id+')"></div>' +(meta.row + meta.settings._iDisplayStart + 1);
      }
    },
     
      {
       "targets":7, 
       "orderable": false,
       "data":"id",
       "render": function(data,type,full,meta)
      {
        editurl = editurl.replace(':id', '');
        viewurl = viewurl.replace(':id', '');
       

        return '<div class="icon-bx"> <a href="'+viewurl+full.id+'"><i class="fa fa-eye" aria-hidden="true"></i></a><a href="'+editurl+full.id+'"><i class="icon  cil-pencil"></i></a> <a href="JavaScript:void(0);" onclick="deleteconfirm('+full.id+')"><i class="icon cil-trash"></i></a> <button title="Approve This Product" onclick="fnstatusupdator('+full.id+','+Active+')"><i class="fa fa-check-circle-o" aria-hidden="true"></i></button>  <button title="Reject This Product" onclick="fnstatusupdator('+full.id+','+Pending+')"><i class="fa fa-window-close-o" aria-hidden="true"></i></button> </div>';     
      }
    }
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'name' },
		  { data: 'categories' } ,
          { data: 'profile_pic' } ,  
          { data: 'store_name' },
          { data: 'sellername' }
         ]
      });     
  });


})(jQuery);

function fninitupdate(){

 if($("#hdncategory_id").val()!='')
    updatestatus();
}
function disableButtonsDown(e) { 
    if(e.key=='F5'){
       $("input:checkbox").prop('checked',"");
      }
};
function fnlastdropdown(){
  var cat_id = $("#dropsubcategory1 option:selected").val(); 
  if(cat_id=='')
            { 
              var newdata=$('#ddsubcategory option:selected').val();
              $("#hdncategory_id").val(newdata);
            }
            else
              $("#hdncategory_id").val(cat_id);
}

function fnfindnewcategory(){
        $("#parent_category").val('');
        $("#divsubcategory").hide();
        $('#Mymodal').modal('show');
}

function fnclosepopup1(){
    $("#Mymodal").modal('hide');
}

function fnPushId(id){


  var exists = seller_products.includes(id)

  if (exists) 
    seller_products= seller_products.filter((c) => { return c !== id })
   else
    seller_products.push(id)
  

  //console.log(seller_products);
}

function fnChangStatus(status){
  $("#hdncategory_id").val('');
  var msg="";
  if(status=='rejected')
    msg="Do you  want to Reject Product?";
  else if(status=='active')
    msg="Do you  want to Approve Product?";
  else
     msg="Do you  want to Add category?";

   button_status=status;
  
  if(seller_products.length>0)
    {
  swal({
    title:msg,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      if(status== 'active')
      {
        swal({
                 title:"Do you want to Change Category Names? " ,
                 text: "",
                 icon: "warning",
                 buttons: true,
                 dangerMode: true,
               })
               .then((willDelete) => {
                 if (willDelete) {
                       $('#divsubcategory1').hide();
                       $('#divsubcategory').hide();
                       $('#ddsubcategory').empty();
                       $('#dropsubcategory1').empty();
                       fnfindnewcategory();
                 }
                 else 
                   updatestatus();
                 });
        }
        else if(status== 'addcategory'){
                       $('#divsubcategory1').hide();
                       $('#divsubcategory').hide();
                       $('#ddsubcategory').empty();
                       $('#dropsubcategory1').empty();
                       fnfindnewcategory();
        }
        else  
          updatestatus();
      }
          
          }); 
        } 
            else
            swal("Please Select At Least One Item",'','error'); 
         
}

function updatestatus(){
 $(".loaderajaxcntr").show();
 $("#loadcntr").empty();
   var singledata=[];
   singledata.push(seller_products[counter]);
   counter++;
   var msg=counter+'/'+seller_products.length;
   $("#loadcntr").empty().append(msg);
   
   $.ajax({
               url: "{{ url('vendorproductapproval') }}",
                  type: "post",
                  data:{ 
                      _token:'{{ csrf_token() }}',
                        seller_products:singledata ,
                        status:button_status,
                        category_id:$("#hdncategory_id").val()
                  },
                  async:true,
                  cache: false,
                  dataType: 'json',
                  success: function(data){
                    
                        if(counter < seller_products.length)
                            updatestatus();
                        else{
                            counter=0;
                            $(".loaderajaxcntr").hide();
                            swal(data,'','success');
                            $("input:checkbox").prop('checked',"");
                            seller_products=[]; 
                            location.reload();
                        }
                        
                } ,
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                     $(".loaderajaxcntr").hide();
                     counter=0;
                     swal("Some Products Not Approved.Please check Product Details",'','error' ); 
                     location.reload();
                }  
      
              })  ;

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
         $(".loaderajaxcntr").hide();
 
           $.ajax({
               url: "{{ url('vendorproductdelete') }}",
                  type: "post",
                  data:{ 
                      _token:'{{ csrf_token() }}',
                        seller_products: seller_products,
                        action_type:'tempproducts'
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
                  swal("Error: "+errorThrown,'','error');  
                }  
      
              })  ;
      }
          
    }); 
  } 
  else
    swal("Please Select At Least One Item",'','error'); 
   


}
function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('delete.vendorproduct', ':id')}}".replace(':id', '')+data;

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

function fnstatusupdator(id,type){
  
       seller_products=[];
       seller_products.push(id);
       fnChangStatus(type);
  
}
</script>


 @endsection

