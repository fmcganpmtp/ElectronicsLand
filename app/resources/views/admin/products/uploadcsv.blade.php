@extends('admin.master')
@section('title', 'Create Product')
@section('breadcrumb') Create Product @endsection
@section('content')
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Upload CSV</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-body upload-csv">
                  <ul class="nav nav-tabs" id="myTab">
                    <li class="nav-item"> <a class="nav-link {{(old('formuploadtype')=='admin'||old('formuploadtype')=='')?'active':''}}" id="admin_atag" data-toggle="tab" href="#home"><i class="fa fa-user-o" aria-hidden="true"></i>go to my account</a> </li>
                    <li class="nav-item"> <a class="nav-link {{(old('formuploadtype')=='seller')?'active':''}}" id="seller_atag" data-toggle="tab" href="#menu1"><i class="fa fa-upload" aria-hidden="true"></i>upload to seller account</a> </li>
                  </ul>
                  <div class="tab-content">
                    <div id="home" class="container tab-pane {{(old('formuploadtype')=='admin' ||old('formuploadtype')=='')?'active':'fade'}}"><br>
                      <div class="card2  tp-30 wow fadeInUp bulck-c">
                        <div class="row">
                          <div class="col-lg-6 col-12">
                            <h3>Download sample excel</h3>
                            <div class="overflow-hidden">
                              <ul>
                <li>Download the Sample CSV File.</li>
                <li>Fill the same with your product details.</li>
                <li>Provide the images as corresponding image links.</li>
                <li>Make sure the format is same as in the sample file.</li>
                <li>Upload the CSV.</li>
                <li>Make sure the products are updated in your profile with pending status.</li>
                <li>Wait for the Admin approval for displaying products in the platform.</li>
             </ul>
                            </div>
                            <a href="{{ asset('/excel/').'/'.'sample.csv' }}" class="bl-btn"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Download</a> </div>
                          <div class="col-lg-6 col-12 c22">
                            <h3>Upload your products</h3>
                            <div class="upload-c">
                              <div class="file-drop-area form-group">
                              <form method="post" action="{{ route('admin.import-product-exl') }}"  enctype="multipart/form-data" >
                                @csrf

                                <input type="hidden" value="admin" name="formuploadtype"/>
                                @if ((count($errors) > 0) && old('formuploadtype')=='admin')
                                <div class="alert alert-danger alert-dismissible">
                                  
                                    <h6><i class="icon fa fa-ban"></i> Error!</h6>
                                    @foreach($errors->all() as $error)
                                    {{ $error }} <br>
                                    @endforeach      
                                </div>
                              
                                  @elseif (count($errors) == 0 && old('formuploadtype')=='admin')
                                      
                                          <div class="alert alert-success alert-dismissible">
                                            
                                              <h6>{!! Session::get('success') !!}</h6>   
                                          </div>
                                        
                                  @endif
                                <input type="file" name="file" required class="file-input form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                <button type="submit" class="bl-btn top-marg030"> <i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload</button>
                              </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  
                    <div id="menu1" class="container tab-pane {{(old('formuploadtype')=='seller')?'active':'fade'}}"><br>
                        <form method="post" action="{{ route('admin.import-exl') }}" id="sellerupload"  enctype="multipart/form-data" >
                                @csrf
                        <div class="search-listC" >
                     
                        <!--Make sure the form has the autocomplete function switched off:-->
                        <div  class="form-group sg-list">
                        <input type="text"  name="seller" value="{{old('seller')}}" autocomplete="off" id='seller' class="form-control"/>
                        <div id="users_list">
                          <ul class="list-group ullist"  style="dispaly:block;position:relative;z-index:1">
                        </ul>
                        </div>
                        <input type="text" name="seller_id" value="{{old('seller_id')}}" id="seller_id"  required class="form-control input-hidden"/>
                        <input type="hidden" value="seller"  name="formuploadtype"/>
                        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button> 
                        </div>
                      </div>
                      <div class="card2  tp-30 wow fadeInUp bulck-c">
                        <div class="row">
                          <div class="col-lg-6 col-12">
                            <h3>Download sample excel</h3>
                            <div class="overflow-hidden">
                             <ul>
                <li>Download the Sample CSV File.</li>
                <li>Fill the same with your product details.</li>
                <li>Provide the images as corresponding image links.</li>
                <li>Make sure the format is same as in the sample file.</li>
                <li>Upload the CSV.</li>
                <li>Make sure the products are updated in your profile with pending status.</li>
                <li>Wait for the Admin approval for displaying products in the platform.</li>
             </ul>
                            </div>
                            <a href="{{ asset('/excel/').'/'.'sample.csv' }}" class="bl-btn"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Download</a> </div>
                          <div class="col-lg-6 col-12 c22">
                            <h3>Upload your products</h3>
                            <div class="upload-c">
                              <div class="file-drop-area form-group">
                             
                                @if ((count($errors) > 0) && old('formuploadtype')=='seller')
        
                                <div class="alert alert-danger alert-dismissible">
                                  
                                    <h6><i class="icon fa fa-ban"></i> Error!</h6>
                                    @foreach($errors->all() as $error)
                                    {{ $error }} <br>
                                    @endforeach      
                                </div>
                              
                                  @elseif (count($errors) == 0 && old('formuploadtype')=='seller')
                                      
                                          <div class="alert alert-success alert-dismissible">
                                            
                                              <h6>{!! Session::get('success') !!}</h6>   
                                          </div>
                                        
                                  @endif
                                  <input type="file" name="sellerfile" required class="file-input form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                               <button class="bl-btn top-marg030"> <i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload</button>
                              
                              </div>
                            </div>
                          </div>
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
    </div>
  </div>
  <link rel="stylesheet" href="{{ asset('/admin1/css/select2.min.css')}}">
<script src="{{asset('/admin1/js/select2.min.js')}}"></script>  
<script src="{{asset('/admin1/js/jqueryvalidate.js')}}"></script>
<script type="text/javascript">


$('#sellerupload').validate({ 
        rules: {
          seller_id: {required: true, }
        },
        messages: {
          seller_id: 'Please Select Seller from the List'},
          submitHandler: function(form) {
                  form.submit();
          }
    });

                              $("#users_list").hide();

                              $( "#seller" ).keyup(function() {
                                            $("#seller_id").val('');
                                            var variant = $(this).val(); 
                                            $.ajax({
                                                url:"{{route('autocomplete.sellerCompany')}}",
                                                type:"GET",
                                                data:{'name':variant},
                                                success:function(data){
                                                  $(".ullist").empty();
                                                
                                                  let dataItems = JSON.parse(data)
                                                  dataItems = dataItems.map((item) => {
                                                    var name="'"+item.name+"'";
                                                    var name1=""+item.name+"("+item.user_name+")";
                                                    $("#users_list").show();
                                                    $(".ullist").append('<li onclick="fnliclick('+item.id+','+name+')">'+name1+'</li>');
                                                  })
                                                   
                                                }
                                            });
                                          });
                                                  
                            $('#myTab a').click(function(e) {
                              e.preventDefault();
                              $(this).tab('show');
                            });

                            // store the currently selected tab in the hash value
                            $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
                              var id = $(e.target).attr("href").substr(1);
                              alert(id);
                              window.location.hash = id;
                            });

                            // on load of the page: switch to the currently selected tab
                            var hash = window.location.hash;
                            $('#myTab a[href="' + hash + '"]').tab('show');
                       
                            function fnliclick(id,name) {
                                  $("#seller_id").val(id);
                                  $("#seller").val(name);
                                  $("#users_list").hide();
                                  $("#seller").focus();
                            }
  </script>
    @endsection