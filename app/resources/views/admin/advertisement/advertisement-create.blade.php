@extends('admin.master')
@section('title', 'Advertisement List')
@section('breadcrumb') Advertisement List @endsection
@section('content')

@if($Advertisement!="")  
                  <?php 
                    $title=$Advertisement->title;
                    $media_type=$Advertisement->media_type;
                    $media_file=$Advertisement->media_file;
                    $start_date=date('Y-m-d', strtotime($Advertisement->start_date));
                    $end_date=date('Y-m-d', strtotime($Advertisement->end_date));
                    $link_url=$Advertisement->link_url;
                    $position=$Advertisement->position;
                    $adv_id=$Advertisement->adv_id;
                    $page=$Advertisement->page_id;
                    $duty = 'Edit' ;
                  ?>
             @else
                <?php 
                  $title="";
                  $media_type="";
                  $media_file="";
                  $start_date="";
                  $end_date="";
                  $link_url="";
                  $position="";
                  $adv_id='';
                  $page='';
                  $duty = 'Creation' ;
                ?>

        @endif

        
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Advertisement {{ $duty}}</h2>
        <div class="card-body">

        @if($Advertisement!="") 
                <form method="post" action="{{ route('advertisement.update',$adv_id) }}"  enctype="multipart/form-data">
                   
         @else
                <form method="post" action="{{ route('advertisement.store') }}"  enctype="multipart/form-data">
         @endif   
         @csrf
        
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
              
                <div class="card-body">
                <input type="hidden" value="{{$adv_id}}" name="adv_id"/>

                 <div class="form-group">
                      <label>Page :</label>
                      <select name="page_id" class="form-control" id="page" onchange="fngetposition()">
                        <option value="">Select Page  </option>
                        @foreach($page_list as $data)
                        <option value="{{$data->id}}" {{ old('page',$page) == $data->page ? 'selected' : ''}}>{{$data->page}}</option>
                        @endforeach
                       </select>
                    </div>
                     <div class="form-group">
                      <label>Position:</label>
                      <select name="position" class="form-control" id="position" required>
                        <option></option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Title:</label>
                      <input type="text" name="title" class="form-control"   value="{{ old('title',$title) }}" required>
                    </div>
                    <div class="form-group">
                      <label>Media type:</label>
                      <select name="media_type" id="media_type" class="form-control"  >
                          <option value="Image" {{ old('media_type',$media_type) == 'Image' ? 'selected' : ''}}>Image</option>
                          <option value="Video" {{ old('media_type',$media_type) == "Video" ? 'selected' : ''}}>Video</option>
                          <option value="YouTube" {{ old('media_type',$media_type) == "YouTube" ? 'selected' : ''}}>YouTube</option>
                         
                        </select>
                      
                    </div>
                    <div class="form-group">
                      <label>Media file:</label>
                      <input type="file" name="media_file" class="form-control{{ $errors->has('media_file') ? ' is-invalid' : '' }}" class="file-input form-control" accept=".jfif,.jpg,.jpeg,.png,.gif,video,.mp4" multiple <?php  if($duty=='Creation') echo 'required';?>>
                      @if ($errors->has('media_file'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('media_file') }}</strong>
                                    </span>
                                @endif
                      <?php if(($media_type=="Image" || $media_type=="YouTube") &&  $duty == 'Edit'){?>
                      <img src="{{asset('uploads/advertiseImages/'.$media_file)}}" class="img-responsive" style="max-height: 100px; max-width: 100px;" alt="" srcset="">
                      <?php  }else if( $duty == 'Edit'){?><video width="100" height="100" controls>
                          <source src="{{asset('uploads/advertisementVideo/'.$media_file) }}" type="video/mp4">
                          </video>
                          <?php }?>
                          <span id="filesizeval" style="color:red"></span>
                    </div>
                    <div class="row">
                      <div class="col-lg-6 col-12">
                        <div class="file-drop-area form-group">
                          <label >Start date:</label>
                          <input type="date" class="form-control" id="datepicker1" name="start_date"  value="{{old('start_date',$start_date)}}" min="<?=($duty=='Edit')?date('Y-m-d'):date('Y-m-d') ?>" required>
                        </div>
                      </div>
                      <div class="col-lg-6 col-12">
                        <div class="file-drop-area form-group">
                          <label > End date:</label>
                          <input type="date" class="form-control" id="datepicker2" min="{{$start_date}}" name="end_date"  value="{{old('end_date',$end_date)}}" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group" id="link_url">
                      <label>Link url:</label>
                      <input type="text" class="form-control {{ $errors->has('link_url') ? ' is-invalid' : '' }}" name="link_url"  value="{{old('link_url',$link_url)}}">
                       @if ($errors->has('link_url'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('link_url') }}</strong>
                                 </span>
                             @endif
                    </div>
                   
                    <button type="submit" class="bl-btn">Submit</button>
                 
                </div>
              </div>
            </div>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <script>

  var position_id='<?php echo  old('position',$position);?>';
 
  $(function() {
   var old_pageid='<?php echo  old('page_id',$page);?>';
   
    document.getElementById("datepicker1").onchange = function () {
        var input = document.getElementById("datepicker2");
        input.setAttribute("min", this.value);
    }
   if(old_pageid!=''){
    $("#page").val(old_pageid);
    fngetposition();
   }
   $("#position").change(function(){

     var position=$("#position").val();
     var page=$("#page").val();
     if((position=="Middle" && page=="11"))
      $("#filesizeval").text("Minimum Image Width:1000px");
     else
      $("#filesizeval").text("");
     
   })

  
   
    var media_type=$("#media_type").val();
      $("#media_type").change(function(){
        media_type=$("#media_type").val();
        link_url(media_type)
      })
      link_url(media_type);

    $("#position").val();  
   
  } );
  function fngetposition(){
 $("#position").empty().append('<option> </option>');
       $.ajax({
         url: "{{ url('pagepositionavailable') }}",
            type: "get",
            data:{ 
               id: $("#page").val()
                },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
             var position=data.split(",");
               
             position.forEach(function myFunction(element) {  
                $("#position").append("<option value='"+element+"'>"+element+"</option>");
              });  

               if(position_id!='')
                 $("#position").val(position_id);
            
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            
          }  

        })  ;
  
  }

  

  function link_url(media_type){
    if(media_type=='YouTube')   
      $("#link_url").css('display','block');
    else  
      $("#link_url").css('display','none');
  }
  </script>
@endsection