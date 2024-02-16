@extends('layouts.template')
@section('title', 'List Packages')
@section('content')

<sectiion class="seller-page no-bg">
  <div class="pr-banner">
    <!--<img src="assets/images/pr-banner.jpg">-->
  </div>
  <div class="container">
    <div class="row"> </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom ad-pro" >
          <div class="card">
            <div class="step-icon">
              <div class="step-img"><img src="assets/images/product-add.png"></div>
              <div class="step-count">2</div>
            </div>
            <h3>Add product</h3>
            
            <!--step--->
            <div class="row">
              <div class="col-lg-12">

                <div class="search-listC" >
                  <!--Make sure the form has the autocomplete function switched off:-->
                  <form autocomplete="off" action="{{  route('fetch.id') }}" method="get">
                    @csrf
                    <div class="autocomplete form-group sg-list" >
                      <input id="variants" type="text" name="" placeholder="Search"  class="form-control">
                      <input type="hidden" name="product_id" value="" id="product_id" >
                      <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
                  </form>
                  <div id="product_list"></div>
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                    <script  type="text/javascript">
                                        $(document).ready(function(){
                                            $( "#variants" ).keyup(function() {
                                            var variant = $(this).val(); //alert(variant);
                                            $.ajax({ 
                                              
                                              url: "{{ route('getproducts') }}",
                                                type:"get",
                                                data:{'name':variant},
                                                success:function(data){ //alert (data);
                                                    $("#product_list").html(data);
                                                }
                                            });
                                            });
                                            $(document).on('click','li',function(){
                                                var value = $(this).text();
                                                var id = $(this).attr('id'); 
                                                $("#variants").val(value);
                                                $("#product_id").val(id);
                                                
                                                 $("#product_list").html("");
                                            });
                                        });
                                       
                                           
                                        
                                        </script>
                </div>
              </div>
            </div>
            
            
          
            
            
            <!---step--->
            
            <!--step-2-->
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

<script>

</script>


@endsection