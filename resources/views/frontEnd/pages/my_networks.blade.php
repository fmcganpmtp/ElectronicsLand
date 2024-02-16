@extends('layouts.template')
@section('title', 'My Network')
@section('content')



<sectiion class="seller-page no-bg nw-page-new">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom">


     <div class="card srch-seller serch-seller-sec3 networks-search ">
           <h3>Company Database </h3>
           <div class="row">
         <div class="col-lg-3 col-12">
                
        <div class="form-group">
                    <select id="search_by_category" class="list-menu" onchange="fnbtnsearchseller()">
                                        <option value="0">Select Category</option>
                                        @if($allcategorylists)
                                            @foreach($allcategorylists as $item)
                                             <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        @endif
                    </select>
                  </div>
        
        </div>
        <div class="col-lg-3 col-12">
        
        <div class="form-group">
        <select class="js-example-data-array form-control select2-hidden-accessible" multiple tabindex="-1" aria-hidden="true" placeholder="Select Company Type" name="company_type[]" id='company_type' >
        @if($CompanyType)
                                            @foreach($CompanyType as $item)
                                             <option value="{{$item->id}}">{{$item->company_type}}</option>
                                            @endforeach
                                        @endif
        </select>  
            
           <input type="hidden" id="selected_company_type">
            </div>
        </div>
         <div class="col-lg-3 col-12">
         <div class="form-group">
              <select type="text" placeholder="Available Countries" name="available_countries[]" id='available_countries' class="form-control" multiple="multiple">
              @if($countries)
                                            @foreach($countries as $item)
                                             <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        @endif
              </select>
              <input type="hidden" id="selected_country_id">
                  </div>
        
        </div>
        
        <div class="col-lg-3 col-12">
                <div class="form-group srch-frm7">
                  <input type="text" class="form-control" id="search_by_name" placeholder="Search for Keyword, Name or Company"> 
                    <input type="hidden" id="start_from" value="0"> 
                   <button type="button" onclick="fnbtnsearchseller()"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
                
              </div>
              </div>
        </div>
            <div class="row seller-result">
              <div class="col-lg-12 col-12">
                <div class="row" id="sellers_list"  >
				@include('frontEnd.pages.sellers_list')
				
				</div>
                
              </div>
            </div>
          </div>      




     <div class="card m-cts1">
           <h3>My Contacts</h3>
           <div class="row">
          <div class="col-lg-3 col-12">
                
        <div class="form-group">
                    <select id="search_by_category1" name="search_by_category1" class="list-menu" onchange="fnbtnsearchnetwork()">
                                        <option value="0">Select Category</option>
                                        @if($allcategorylists)
                                            @foreach($allcategorylists as $item)
                                             <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        @endif
                    </select>
                  </div>
        
        </div>   
        
        <div class="col-lg-3 col-12">
                
        <div class="form-group">
                    <select type="text" multiple placeholder="Select Company Type" name="company_type1[]" id='company_type1' class="form-control" multiple="multiple">
                    @if($CompanyType)
                                            @foreach($CompanyType as $item)
                                             <option value="{{$item->id}}">{{$item->company_type}}</option>
                                            @endforeach
                                        @endif
           </select>    
           <input type="hidden" id="selected_company_type1">
            </div>
                  </div>
        

         <div class="col-lg-3 col-12">
                
        <div class="form-group">
              <select type="text" placeholder="Available Countries" name="available_countries2[]" id='available_countries2' class="form-control" multiple="multiple">
              @if($countries)
                                            @foreach($countries as $item)
                                             <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        @endif
              </select>
              <input type="hidden" id="selected_country_id2">
                  </div>
        
        </div>
        
        <div class="col-lg-3 col-12">
                <div class="form-group srch-frm7">
                  <input type="text" class="form-control" id="search_by_name1" placeholder="Search for Keyword, Name or Company"> 
                    <input type="hidden" id="start_from1" value="0"> 
                   <button type="button" onclick="fnbtnsearchnetwork()"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
                
              </div>
        </div>
        
           <div class="row" id="network_list">
		      @include('frontEnd.pages.network_contacts')
		   </div>
            
        </div>


    
    
        </div>
      </div>
    </div>
  </div>
  
  

</sectiion>
<link rel="stylesheet" href="{{ asset('/css/select2.min.css')}}">

<script type="text/javascript">
function my_net_contact(){
    
     //$fmcg('li').removeClass('active');
        $fmcg(this).parent('li').addClass('active');
        var page= $fmcg('#pg_number1').val(); 
        getnetworkList(page);
}

function my_sellers_search(){
    
     //$fmcg('li').removeClass('active');
        $fmcg(this).parent('li').addClass('active');
        var page= $fmcg('#pg_number').val(); 
        fnloadsellers(page);
}
$fmcg(document).ready(function(){
    /*$fmcg("input#pg_number1").keydown(function (event) { 
     if (event.which == 13) { 
        $fmcg('li').removeClass('active');
        $fmcg(this).parent('li').addClass('active');
        var page= $fmcg('#pg_number1').val(); 
        getnetworkList(page);
     }
    });
    
 $fmcg("input#pg_number").keydown(function (event) { 
     if (event.which == 13) { 
        event.preventDefault();
        $fmcg('li').removeClass('active');
        $fmcg(this).parent('li').addClass('active');
        var page= $fmcg('#pg_number').val(); 
        fnloadsellers(page);
     }
    });*/
    
    
    
});     
// var route1 = "{{route('company.types')}}";
//    $fmcg('#company_type').select2({
//      placeholder: 'Select Company Type',
     
//     data: typeDatas,

//     escapeMarkup: function(markup) { 
//           return markup;
//     },
//     templateResult: function(data) {
       
//       return data.html;
//     },
//     templateSelection: function(data) {      

//       if (data && !data.selected) 
//       return data.text;
//     },

    
    
//     // ajax: {
//     //   url: route1,
//     //   dataType: 'json',
//     //   delay: 250,
//     //   processResults: function(data) {
//     //     return {
//     //       results: $fmcg.map(data, function(item) {
//     //         return {
//     //           html:"<span>"+item.company_type+"</span>",
//     //           text: item.company_type,
//     //           id: item.id
//     //         }
//     //       })
          
//     //     };
//     //   },
//     //   cache: true,

//     // }
//   }).on("change", function (e) {
//      $fmcg("#selected_company_type").val($fmcg(this).val());
//      fnbtnsearchseller();
//   });

  $fmcg(".js-example-data-array").select2({
      placeholder: 'Select Company Type',}
      ).on("change", function (e) {
        fnbtnsearchseller();
    });

    $fmcg('#available_countries').select2({
     placeholder: 'Select Available Countries',}
     ).on("change", function (e) {
     fnbtnsearchseller();
  });

    $fmcg('#available_countries2').select2({
      placeholder: 'Select Available Countries',
      }).on("change", function (e) {
      fnbtnsearchnetwork();
    });

    $fmcg('#company_type1').select2({
     placeholder: 'Select Company Type',
    }).on("change", function (e) {
     fnbtnsearchnetwork();
    });

    
  
  //    $fmcg('#company_type1').select2({
  //    placeholder: 'Select Company Type',

  //   escapeMarkup: function(markup) { 
  //         return markup;
  //   },
  //   templateResult: function(data) {
       
  //     return data.html;
  //   },
  //   templateSelection: function(data) {
      

  //     if (data && !data.selected) 
  //     return data.text;
  //   },

    
  //   ajax: {
  //     url: route1,
  //     dataType: 'json',
  //     delay: 250,
  //     processResults: function(data) {
  //       return {
  //         results: $fmcg.map(data, function(item) {
  //           return {
  //             html:"<span>"+item.company_type+"</span>",
  //             text: item.company_type,
  //             id: item.id
  //           }
  //         })
  //       };
  //     },
  //     cache: true,

  //   }
  // }).on("change", function (e) {
  //    $fmcg("#selected_company_type1").val($fmcg(this).val());
  //    fnbtnsearchnetwork();
  // });       

// var route = "{{route('available.countries')}}";

//   $fmcg('#available_countries').select2({
//      placeholder: 'Select Available Countries',

//     escapeMarkup: function(markup) { 
//           return markup;
//     },
//     templateResult: function(data) {
       
//       return data.html;
//     },
//     templateSelection: function(data) {
      

//       if (data && !data.selected) 
//       return data.text;
//     },
//     ajax: {
//       url: route,
//       dataType: 'json',
//       delay: 250,
//       processResults: function(data) {
//         return {
//           results: $fmcg.map(data, function(item) {
//             return {
//               html:"<span>"+item.name+"</span>",
//               text: item.name,
//               id: item.id
//             }
//           })
//         };
//       },
//       cache: true,

//     }
//   }).on("change", function (e) {
//      $fmcg("#selected_country_id").val($fmcg(this).val());
//      fnbtnsearchseller();
//   });
  
  
// $fmcg('#available_countries2').select2({
//      placeholder: 'Select Available Countries',

//     escapeMarkup: function(markup) { 
//           return markup;
//     },
//     templateResult: function(data) {
       
//       return data.html;
//     },
//     templateSelection: function(data) {
      

//       if (data && !data.selected) 
//       return data.text;
//     },
//     ajax: {
//       url: route,
//       dataType: 'json',
//       delay: 250,
//       processResults: function(data) {
//         return {
//           results: $fmcg.map(data, function(item) {
//             return {
//               html:"<span>"+item.name+"</span>",
//               text: item.name,
//               id: item.id
//             }
//           })
//         };
//       },
//       cache: true,

//     }
//   }).on("change", function (e) {
//      $fmcg("#selected_country_id2").val($fmcg(this).val());
//      fnbtnsearchnetwork();
//   });



getnetworkList(1);
 $fmcg('#search_by_category,#search_by_category1').select2();
/*function loadmore(){
    //$fmcg(".loaderajax").show();
     fnloadsellers();
}*/
/*function loadmore1(){
     getnetworkList();
}*/

 $fmcg("#search_by_name").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         fnbtnsearchseller();
     }
    });
 $fmcg("#search_by_name1").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         fnbtnsearchnetwork();
     }
    });   
    
 function fnmovetoprofile(userid)
{
  window.location="{{route('ViewSeller.profile', ':id')}}".replace(':id', '')+userid;
}   

 function addtonetwork(userid)
   {
      // $fmcg(".loaderajax").show();
    $fmcg.ajax({
            url: "{{ url('profile_to_network') }}",
               type: "get",
               data:{ 
                   user_id: userid,
               },
               async:false,
               cache: false,
               dataType: 'json',
               success: function(data){ 
                   //$fmcg(".loaderajax").hide();
                 swal(data, "", "success");
                 $fmcg(".clsa"+userid).remove();
                 $fmcg(".clschat"+userid).show();
                 $fmcg('#start_from1').val(0);
                 $fmcg("#network_list").empty();
                 getnetworkList();
             } ,
             error: function(XMLHttpRequest, textStatus, errorThrown) { 
                // $fmcg(".loaderajax").hide();
               swal(errorThrown, "", "error");
             }  
   
           })  ;
  }

function fnbtnsearchseller(){
    
     $fmcg('#start_from').val(0);
     $fmcg("#sellers_list").empty();
     fnloadsellers();
     @if($view_composer_profile_menu_visible_criteria['network_expand'] != 1)
     $fmcg('.err-shw').css('display','block');
     @else
     $fmcg('.err-shw').css('display','none');
     @endif
}

function fnbtnsearchnetwork(){
     $fmcg('#start_from1').val(0);
     $fmcg("#network_list").empty();
     getnetworkList();
}


function fnloadsellers(page){

   // $fmcg(".loaderajax").show();
    $fmcg.ajax({
                          type:'POST',
                          
                          async:false,
                            cache: false,
                          data:{
                                '_token':'{{csrf_token()}}',
                                'seller_name':$fmcg('#search_by_name').val(),
                                'category_id':$fmcg('#search_by_category').val(),
                                'company_type':$fmcg('#company_type').val(),
                                'selected_country_id':$fmcg('#available_countries').val(),
                                'start_from':$fmcg('#start_from').val(),
                                'per_page':9
                          },
                           url: "{{ route('sellers.get-more-sellers') }}" + "?page=" + page,
                          success:function(data) {
                          $fmcg('#sellers_list').empty().html(data);
                          },
                          error: function(XMLHttpRequest, textStatus, errorThrown) { 
                 //$fmcg(".loaderajax").hide();
               swal(errorThrown, "", "error");
             } 
                      });
  
}

function EnableChatRequest(seller_id){
          $fmcg(".message-input").css("display", "block");
          $fmcg("#frame").css("display", "block");
          $fmcg(".content").css("display", "block");
          $fmcg("#frame").css("width", "95%");
        
         
          $fmcg("#selected_id").val(seller_id);
          loadMessage();
          $fmcg("#frame").show();
          senter = seller_id;
          $fmcg("#txt_message").focus();      
           
        
}


function getnetworkList(page){ 
   // $fmcg(".loaderajax").show();
  $fmcg.ajax({
            
              type: "Post",
               async:false,
               cache: false,
               data:{
                                '_token':'{{csrf_token()}}',
                                'seller_name':$fmcg('#search_by_name1').val(),
                                'category_id':$fmcg('#search_by_category1').val(),
                                'company_type':$fmcg('#company_type1').val(),
                                'selected_country_id':$fmcg('#available_countries2').val(),
                                //'company_type':$fmcg('#selected_company_type1').val(),
                                'start_from':$fmcg('#start_from1').val(),
                               // 'selected_country_id':$fmcg('#selected_country_id2').val(),
                                
                          },
               url: "{{ route('sellers.network-contacts') }}" + "?page=" + page,
                          success:function(data) {
                          $fmcg('#network_list').empty().html(data);
                          },

             error: function(XMLHttpRequest, textStatus, errorThrown) { 
                // $fmcg(".loaderajax").hide();
               swal(errorThrown, "", "error");
             }  
   
           })  ;
}

  function removefromnetwork(userid)
   {
      // $fmcg(".loaderajax").show();
    $fmcg.ajax({
            url: "{{ url('revokeFrom_network') }}",
               type: "get",
               data:{ 
                   user_id: userid,
               },
               async:false,
               cache: false,
               dataType: 'json',
               success: function(data){
                   //$fmcg(".loaderajax").hide();
                 swal(data, "", "success");
                 $fmcg("#div_mynetwork"+userid).remove(); 
                 $fmcg("#expandnetwork"+userid).remove(); 
                 $fmcg("#home"+userid).remove();
             } ,
             error: function(XMLHttpRequest, textStatus, errorThrown) { 
                // $fmcg(".loaderajax").hide();
               swal(errorThrown, "", "error");
             }  
   
           })  ;
  }
  


</script>

<script>
$fmcg(document).ready(function()
{
$fmcg(document).on('click', '.seller-pagi .pagination a',function(event)
{ 
event.preventDefault();
$fmcg('li').removeClass('active');
$fmcg(this).parent('li').addClass('active');

var page=$fmcg(this).attr('href').split('page=')[1]; 
fnloadsellers(page);
});
});

$fmcg(document).ready(function(){

   
    
$fmcg(document).on('click', '.pagination_outer .pagination a',function(event)
{ 
event.preventDefault();
$fmcg('li').removeClass('active');
$fmcg(this).parent('li').addClass('active');

var page=$fmcg(this).attr('href').split('page=')[1]; 
getnetworkList(page);
});
});

</script>
  <style>
 
.add-nt{margin-right:4px;}
    .srch-seller .slr-details{ margin-top:0px;}
    .add-nt{ float:right;}
          
         .serch-seller-sec3 .nw-proC{ border-bottom:2px solid #edeaea;}
         .serch-seller-sec3 .nw-prof-img {
                overflow: hidden;
                border-radius: 50%;
                border: 2px solid #fff;
            }
          .serch-seller-sec3 .add-ntw{  padding:5px 10px; text-transform:uppercase; float:left; color:#fff; border-radius:4px; background:#0d6efd; border-bottom:2px solid #0561e9;}
          .add-ntw:hover{ background:#333; color:#FFFFFF;border-bottom:2px solid #333;}
          .chat-btn9{ color:#0d6efd; margin-left:10px; font-size:25px; margin-top:-4px; float:left;}
          .net-wrk-btn{ margin-top:17px; float:right;}
          .sr-listing8{ margin-bottom:10px;}
          
          .chat-btn9:hover{ color:#333;}
          
          .srch-frm7{ position:relative;}
          .srch-frm7 button{ background:none!important; padding:10px; position:absolute; right:10px; color:#000000; top:0; margin:0px 0px;}
          .seller-result{ margin-top:20px;}
          
          </style>


@endsection
