@yield('footer_script')
<script type="text/javascript">

	   $fmcg(document).ready( function () { 
	    $fmcg('body').click(function(e) {
	           if (!$fmcg(e.target).is('#txt_message, .wrap *, #showattachment, #attachment_cntr *')) {
	                 message_sending_type="";
	                 $fmcg("#txt_message").val('');
	                 $fmcg("#attachment_cntr").hide();
	           }
	    });
	   $fmcg("#search").autocomplete({ 
	       source: "{{ url('TypeaheadSearch') }}",
	           focus: function( event, ui ) {
	          // $fmcg( "#search" ).val( ui.item.title ); // uncomment this line if you want to select value to search box  
	           return false;
	       },
	       select: function( event, ui ) {
	           window.location.href = ui.item.url;
	       }
	   }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
	       var inner_html = '<a href="' + item.url + '" ><div class="list_item_container"><div class="image"><img src="' + item.image + '" ></div><div class="label_search"><b>' + item.title + '</b></div></div></a>';
	       return $fmcg( "<li></li>" )
	               .data( "item.autocomplete", item )
	               .append(inner_html)
	               .appendTo( ul );
	   };
	   
	   
	   $fmcg("#chat_close").click(function(e) {
	             $fmcg("#divmaincontainer").hide();

	   });
	  
	});
	
</script> 
<script src="{{ asset('assets/js/jquery.emojiarea.min.js')}}"></script>

<script>
	$fmcg(document).ready(function(){
		var route = "{{ url('autocomplete-search') }}";
	$fmcg('#top_bar_search').typeahead({ 
	  limit:20,
	  source: function (query, process) { // query is search parameter
	    return $fmcg.get(route, {
	      query: query
	    }, function (data) { 
	      return process(data);
	    });
	  } ,
	  updater: function(item) {
	    var name = item.name; 
	    var item_id=item.id.split(":"); 
	   if(item_id[0]=='P')
	    var url_path="{{route('view.Sproduct',":id")}}".replace(':id', item_id[1]);
	     else
	    var url_path = '{{ route("Product.Listing", ":name") }}';
	    url_path = url_path.replace(':name', item.name );
	    window.location=url_path;
	  }
	});
	  $fmcg("#categoryButton2").click(function(){
	    $fmcg(".new-cat-menu").slideToggle();
	  });
	  $fmcg("#categoryButton2").click(function(){
	    $fmcg(".menu-backdrop").slideToggle();
	  });
	   $fmcg(".menu-backdrop").click(function(){
	    $fmcg(".new-cat-menu").slideUp();
	  });
	  $fmcg(".menu-backdrop").click(function(){
	    $fmcg(".menu-backdrop").hide();
	  });
	  $fmcg("#chat_search_contact").keydown(function (event) { 
	     if (event.which == 13) { 
	        search_contact_name=$fmcg("#chat_search_contact").val();
	         loadContacts();
	     }
	    });
	});
	function fnsearchcontact(){
	   search_contact_name=$fmcg("#chat_search_contact").val();
	   loadContacts();
	}
	function fnclearsearch(){
	  $fmcg("#chat_search_contact").val('');
	  search_contact_name='';
	  loadContacts();
	}
</script>
<script>
	var senter = '';
	var last_message = 0;
	var search_contact_name = '';
	var message_sending_type='text';
	var message_type = 'text';
	var img_path = '{{ asset('/uploads/userImages/').'/' }}';
	var img_path_company = '{{ asset('/uploads/BuyerCompany/').'/' }}';
	var chat_img_path = '{{ asset('assets/uploads/chat/').'/' }}';
	var default_img_path = '{{ asset('uploads/defaultImages/default_avatar.png')}}';
	@if(Auth::guard('user')->check())
	
	  @if(!empty(Auth::guard('user')->user()->profile_pic)) 
	      var profileimg_path = '{{asset('/uploads/userImages/').'/'.Auth::guard('user')->user()->profile_pic}}';
	    @else  
	        var profileimg_path = '{{asset('uploads/defaultImages/default_avatar.png')}}';
	    @endif
	@endif
	  var sender_image_path="{{asset('/uploads/userImages/')}}"; 
	function loadContacts(){
	    $fmcg.ajax({
	    url:"{{route('chat.getMyContacts')}}",
	    type: 'POST',
	    data:{ 
	            _token:"{{csrf_token()}}",
	            chat_cnt_search_name:search_contact_name,
	    },  
	    success: function( data ) { 
	
	        var contentHtml ='<ul>';
	       $fmcg.each(data, function( index, value ) { 
	            if(index==0&&senter==''){
	               senter =value.contact.id
	               $fmcg("#selected_id").val(senter);
	            }
	            contentHtml +='<li class="contact" data-uid="'+value.contact.id+'">';
	    contentHtml +='<div class="wrap" >';
	    if(value.contact.user_status==null || value.contact.user_status==='Offline' )
	    contentHtml +='<span class="contact-status offline"></span>';
	      else if( value.contact.user_status==='Away')
	    contentHtml +='<span class="contact-status away"></span>';
	    else if(value.contact.user_status==='Online')
	    contentHtml +='<span class="contact-status online"></span>';
	    else if(value.contact.user_status==='Busy')
	    contentHtml +='<span class="contact-status busy"></span>';
	 if(value.contact.company_image!=null) 
	    contentHtml +='<img src="'+img_path_company+value.contact.company_image+'" alt="" />';
	    else if(value.contact.profile_pic!=null) 
	    contentHtml +='<img src="'+img_path+value.contact.profile_pic+'" alt="" />';
	    else
	    contentHtml +='<img src="'+default_img_path+'" alt="" />';
	    contentHtml +='<div class="meta">';
	    contentHtml +='<p class="name" onclick="fnmovetoprofile('+value.contact.id+')">'+value.contact.name+'</p>';
	    if(value.contact.company_name!=null)
	    contentHtml +='<p class="company">'+value.contact.company_name+'</p>';
	    else
	    contentHtml +='<p class="company"></p>';
	        if(value.latestMessage!=null)
	            {
	                if(value.latestMessage.message_type=='image')
	                        contentHtml +='<p class="chatimageicon"><i class="fa fa-picture-o" aria-hidden="true"></i> Photo</p>';
	                if(value.latestMessage.message_type=='document')
	                        contentHtml +='<a target="_blank" class="chatimageicon" href='+chat_img_path+value.latestMessage.file+' /><i class="fa fa-file-text-o" aria-hidden="true"></i> '+value.latestMessage.file+'</a>';
	                if(value.latestMessage.message!=null)
	                { 
	                    if(value.latestMessage.message.length >70)
	                        contentHtml +='<p>'+value.latestMessage.message.substring(0, 70)+'...</p>'; 
	                    else
	                        contentHtml +='<p>'+value.latestMessage.message+'</p>'; 
	                    
	                }
	                
	            }
	            else
	              contentHtml +='<p></p>'; 
	    
	    if(value.unreadcount>0)
	    contentHtml +='<span class="unreadcount" id="spancnt'+value.contact.id+'">'+value.unreadcount+'</span>';
	    contentHtml +='</div>';
	    contentHtml +='</div>';
	    contentHtml +='<div class="hide_button" style="width:60px;"><a href="javascript:void(0)" onclick="removefromchat('+value.contact.id+',this)" class="rmv "><i class="fa fa-eye-slash" aria-hidden="true"></i>  Hide</a></div>';
	   
	            contentHtml +='</li>';
	         });
	       contentHtml +='</ul>';
	       $fmcg("#contacts_chat").html(contentHtml);
	        
	    },
	    error: function() {
	        //alert('Upload Failed');
	    }
	});   
	}
	function fnemoji(){
	message_sending_type = 'text';
	document.getElementById("chat_attachment").value = null;
	 $fmcg("#attachment_cntr").hide();
	}    
	 
	function fnmovetoprofile(userid)
	{
	var url_path="{{route('ViewSeller.profile', ':id')}}".replace(':id', '')+userid;
	window.open(url_path, '_blank');
	}      
	 
	function removefromchat(userid,event)
	{
	
	$fmcg.ajax({
	        url: "{{ url('updatedeletedcontact') }}",
	           type: "post",
	           data:{ 
	                _token:"{{csrf_token()}}",
	               deleted_id: userid,
	           },
	           async:true,
	           cache: false,
	           dataType: 'json',
	           success: function(data){
	                $fmcg(event).parents('li').remove();
	                $fmcg(".content").css("display", "none");
	                $fmcg("#frame").css("width", "360px");
	           } ,
	         error: function(XMLHttpRequest, textStatus, errorThrown) { 
	           swal(errorThrown, "", "failure");
	         }  
	
	       })  ;
	}
	 
	 
	 
	 
	 function handleBackToMainContactClick(event) {
      event.stopPropagation();
      $fmcg("#divmaincontainer").hide();
     }
	 
	 function loadMessage(){
	      $fmcg(".chatMessages").html('');
	       $fmcg(".upload-type").hide();
	       $fmcg("#attachment_cntr").hide();
	       $fmcg("#spancnt"+senter).text('');
	       $fmcg("#spancnt"+senter).removeClass("unreadcount");
	     senter = $fmcg("#selected_id").val();
	      $fmcg.ajax({
	    url:"{{url('/getMyMessages')}}/"+senter,
	    type: 'GET',  
	     async:false,
	    success: function( data ) { 
	        var chat_profile = '';
	        chat_profile+='<div id="backtomaincontact" class="backtomaincontact" style="width:20px" onclick="handleBackToMainContactClick(event)"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></div>';
	         if(data.userdetails.company_image!=null)  
	      chat_profile +='<img src="'+img_path_company+data.userdetails.company_image+'" alt="" />';
	        else if(data.userdetails.profile_pic!=null)  
	      chat_profile +='<img src="'+img_path+data.userdetails.profile_pic+'" alt="" />';
	  else
	      chat_profile +='<img src="'+default_img_path+'" alt="" />';
	      chat_profile+='<p>'+data.userdetails.name+'</p>';
	        
	         $fmcg("#chat_profile").html(chat_profile);
	        $fmcg('#chat_profile').removeAttr('onclick');
	         $fmcg('#chat_profile').attr('onClick', 'fnmovetoprofile('+senter+')');
	         var prev_date = '';
	       $fmcg.each(data.chat_data.data, function( index, value ) { 
	          
	           var contentHtml ='';
	             if(index==data.chat_data.data.length-1)
	                contentHtml +='<li  class="datetime"><span>'+value.Date+'</span></li>';
	           if(index==0){
	               last_message = value.id;
	            }
	           if(value.from_user==senter){
	                contentHtml +='<li  class="sent">'; 
	        if(data.userdetails.company_image!=null)  
	        contentHtml +='<img src="'+img_path_company+'/'+data.userdetails.company_image+'" alt="" />';        
	        else if(data.userdetails.profile_pic!=null)  
	        contentHtml +='<img src="'+sender_image_path+'/'+data.userdetails.profile_pic+'" alt="" />';
	        else
	        contentHtml +='<img src="'+default_img_path+'" alt="" />';
	        contentHtml +='<p>';
	        if(value.message_type=='image')
	        contentHtml +='<div class="inc_chat_class"><div class="jchat_class"><a target="_blank" href='+chat_img_path+value.file+'><img class="w-100" src='+chat_img_path+value.file+' /></a></div> <span>'+value.Time+'</span></div>';
	        if(value.message_type=='document')
	        contentHtml +='<a target="_blank" href='+chat_img_path+value.file+' />'+value.file+'</a><span>'+value.Time+'</span>';
	        if(value.message!=null)
	        contentHtml +=value.message+'<span>'+value.Time+'</span>';
	        contentHtml +='</p>'; 
	        contentHtml +='</li>';
	           }else{
	                contentHtml +='<li  class="replies">'; 
	        contentHtml +='<img src="'+profileimg_path+'" alt="" />';
	      contentHtml +='<p>';
	        if(value.message_type=='image')
	        contentHtml +='<div class="inc_chat_class"><div class="jchat_class"><a target="_blank" href='+chat_img_path+value.file+'><img class="w-100" src='+chat_img_path+value.file+' /></a></div><span>'+value.Time+'</span></div>';
	        if(value.message_type=='document')
	        contentHtml +='<a target="_blank" href='+chat_img_path+value.file+' />'+value.file+'</a><span>'+value.Time+'</span>';
	        if(value.message!=null)
	        contentHtml +=value.message+'<span>'+value.Time+'</span>';
	        contentHtml +='</p>'; 
	        contentHtml +='</li>';
	           }
	           if(prev_date == value.Date){
	               ;
	           }else{
	              
	               if(index!=0){
	                   contentHtml +='<li  class="datetime"><span>'+prev_date+'</span></li>';
	               } 
	              
	                prev_date = value.Date;
	           }
	           $fmcg(".chatMessages").prepend(contentHtml);
	           //var element = document.getElementById("messageDiv");
	          // element.scrollTop = element.scrollHeight;
	           //$fmcg('.chatMessages li').scrollTop($fmcg('.chatMessages li')[0].scrollHeight);
	       }); 
	       // $fmcg('#messageDiv').scrollTop($fmcg('#messageDiv')[0].scrollHeight); 
	      // $fmcg("#contacts").html(contentHtml);
	      
	    },
	    error: function() {
	       // alert('Upload Failed');
	    }
	});
	 }
	function checkNewMessage(last_messageid,senter){
	
	    
	    //$fmcg(".chatMessages").html('');
	    $fmcg.ajax({
	        url:"{{url('/checkNewMessage')}}/"+senter+'/'+last_messageid,
	        type: 'GET',  
	        success: function( data ) { 
	        
	        $fmcg.each(data.chat_data, function( index, value ) { 
	           var contentHtml ='';
	            
	               last_message = value.id;
	            
	           if(value.from_user==senter){
	                contentHtml +='<li  class="sent">'; 
	        if(data.userdetails.company_image!=null)  
	        contentHtml +='<img src="'+img_path_company+'/'+data.userdetails.company_image+'" alt="" />';        
	        else if(data.userdetails.profile_pic!=null)  
	        contentHtml +='<img src="'+sender_image_path+'/'+data.userdetails.profile_pic+'" alt="" />';
	        else
	        contentHtml +='<img src="'+default_img_path+'" alt="" />';
	                contentHtml +='<p>';
	                if(value.message_type=='image')
	                contentHtml +='<div class="inc_chat_class"><div class="jchat_class"><a target="_blank" href='+chat_img_path+value.file+'><img class="w-100" src='+chat_img_path+value.file+' /></a></div>';
	                if(value.message_type=='document')
	                contentHtml +='<a target="_blank" href='+chat_img_path+value.file+' />'+value.file+'</a>';
	                if(value.message!=null)
	                contentHtml +=value.message;
	                contentHtml +='</p>';  
	        contentHtml +='</li>';
	           }else{
	                contentHtml +='<li  class="replies">'; 
	        contentHtml +='<img src="'+profileimg_path+'" alt="" />';
	      contentHtml +='<p>';
	                if(value.message_type=='image')
	                contentHtml +='<div class="inc_chat_class"><div class="jchat_class"><a target="_blank" href='+chat_img_path+value.file+'><img class="w-100" src='+chat_img_path+value.file+' /></a></div>';
	                if(value.message_type=='document')
	                contentHtml +='<a target="_blank" href='+chat_img_path+value.file+' />'+value.file+'</a>';
	                if(value.message!=null)
	                contentHtml +=value.message;
	                contentHtml +='</p>';  
	                 
	        contentHtml +='</li>';
	           }
	           $fmcg(".chatMessages").append(contentHtml);
	           //$fmcg('.chatMessages li').scrollTop($fmcg('.chatMessages li')[0].scrollHeight);
	       }); 
	      
	      // $fmcg('#messageDiv').scrollTop($fmcg('#messageDiv')[0].scrollHeight); 
	    },
	    error: function() {
	       // alert('Upload Failed');
	    }
	});
	 }
	$fmcg(document).on("click", "#btn_sent_message", function(e) { 
	
	   $fmcg(".upload-type").hide();
	   const str = $fmcg("#txt_message").val();
	   const rex = /[\u{1f300}-\u{1f5ff}\u{1f900}-\u{1f9ff}\u{1f600}-\u{1f64f}\u{1f680}-\u{1f6ff}\u{2600}-\u{26ff}\u{2700}-\u{27bf}\u{1f1e6}-\u{1f1ff}\u{1f191}-\u{1f251}\u{1f004}\u{1f0cf}\u{1f170}-\u{1f171}\u{1f17e}-\u{1f17f}\u{1f18e}\u{3030}\u{2b50}\u{2b55}\u{2934}-\u{2935}\u{2b05}-\u{2b07}\u{2b1b}-\u{2b1c}\u{3297}\u{3299}\u{303d}\u{00a9}\u{00ae}\u{2122}\u{23f3}\u{24c2}\u{23e9}-\u{23ef}\u{25b6}\u{23f8}-\u{23fa}]/ug;
	   const message = str.replace(rex, match => `&#${match.codePointAt(0).toString()};`);
	  
	 if(message_sending_type=="text")
	  {
	      
	         var selected_id = $fmcg("#selected_id").val();
	         if(message!=''){
	         $fmcg.ajax({
	             url:"{{url('/sentMessages')}}",
	             data: {
	                 selected_id: selected_id,
	                 message: message,
	                 message_type:'text',
	                 "_token": "{{ csrf_token() }}",
	             },
	             type: 'POST', 
	              async:false,
	             success: function( data ) { 
	                 var contentHtml ='';
	                 if(data.chat_data){
	                 last_message=data.chat_data.id;
	                 contentHtml +='<li  class="replies">'; 
	         contentHtml +='<img src="'+profileimg_path+'" alt="" />';
	         contentHtml +='<p>';
	         if(data.chat_data.message_type=='image')
	         contentHtml +='<div class="inc_chat_class"><div class="jchat_class"><a target="_blank" href='+chat_img_path+value.file+'><img class="w-100" src='+chat_img_path+data.chat_data.file+' /></a></div>';
	         if(data.chat_data.message_type=='document')
	         contentHtml +='<a target="_blank" href='+chat_img_path+data.chat_data.file+' />'+data.chat_data.file+'</a>';
	         if(data.chat_data.message!=null)
	         contentHtml +=data.chat_data.message;
	         contentHtml +='</p>'; 
	         contentHtml +='</li>';
	                 }
	                 $fmcg(".chatMessages").append(contentHtml);
	                 $fmcg("#txt_message").val('');
	                 $fmcg('#messageDiv').scrollTop($fmcg('#messageDiv')[0].scrollHeight); 
	                 last_message=data.chat_data.id;
	               // $fmcg("#contacts").html(contentHtml);
	             },
	             error: function() {
	                // alert('Upload Failed');
	             }
	         });
	         }
	       }
	       else
	       {
	
	          if( document.getElementById("chat_attachment").files.length != 0 ){
	
	                formData = new FormData();
	                //var message = $fmcg("#txt_message").val();
	                var selected_id = $fmcg("#selected_id").val();
	                fileupload = document.getElementById("chat_attachment");
	                formData.append("file", fileupload.files[0]);
	                formData.append("_token", "{{ csrf_token() }}");
	                formData.append("message_type", message_type);
	                formData.append("message", message);
	                formData.append("selected_id", selected_id);
	                
	                 $fmcg.ajax({
	                    url:"{{url('/sentMessages')}}",
	                    type: 'POST',
	                    data: formData,
	                    contentType: false,
	                    processData: false,
	                     async:false,
	                    success: function( data ) { 
	                        var contentHtml ='';
	                       if(data.chat_data){
	                        last_message=data.chat_data.id;
	                        contentHtml +='<li  class="replies">'; 
	                contentHtml +='<img src="'+profileimg_path+'" alt="" />';
	                contentHtml +='<p>';
	                if(data.chat_data.message_type=='image')
	                contentHtml +='<div class="inc_chat_class"><div class="jchat_class"><a target="_blank" href='+chat_img_path+data.chat_data.file+'><img class="w-100" src='+chat_img_path+data.chat_data.file+' /></a></div>';
	                if(data.chat_data.message_type=='document')
	                contentHtml +='<a target="_blank" href='+chat_img_path+data.chat_data.file+' />'+data.chat_data.file+'</a>';
	                if(data.chat_data.message!=null)
	                contentHtml +=data.chat_data.message;
	                contentHtml +='</p>'; 
	                contentHtml +='</li>';
	                        }
	                        $fmcg(".chatMessages").append(contentHtml);
	                        $fmcg("#txt_message").val('');
	                        last_message=data.chat_data.id;
	                       $fmcg('#messageDiv').scrollTop($fmcg('#messageDiv')[0].scrollHeight); 
	                       document.getElementById("chat_attachment").value = null;
	                    },
	                    error: function() {
	                       // alert('Upload Failed');
	                    }
	                });
	
	            }     
	       }
	
	
	
	
	
	});
	
	 $fmcg(document).on("click", ".contact", function(e) {
	      senter = $fmcg(this).attr('data-uid');
	      $fmcg("#selected_id").val(senter);
	      $fmcg(".chatMessages").html('');
	      $fmcg(".message-input").css("display", "block");
	      $fmcg("#frame").css("display", "block");
	      $fmcg(".content").css("display", "block");
	      $fmcg("#frame").css("width", "95%");
	      loadMessage();
	      $fmcg('#messageDiv').scrollTop($fmcg('#messageDiv')[0].scrollHeight); 
	 });
	  $fmcg(document).on("click", "#loadChatWindow", function(e) {   
	      
	       $fmcg("#frame").show();
	       
	 });
	
	  $fmcg(document).on("click", "#txt_message", function(event) {  
	       $fmcg("#attachment_cntr").hide();
	       document.getElementById("chat_attachment").value = null;
	       $fmcg(".upload-type").hide();
	       message_sending_type='text';
	  });
	 $fmcg(document).on("keydown", "#txt_message", function(event) {  
	   $fmcg("#attachment_cntr").hide();
	   $fmcg(".upload-type").hide();
	   message_sending_type='text';
	    var id = event.key || event.which || event.keyCode || 0;     
	    if (id == 13 || id == 'Enter') {
	        $fmcg('#btn_sent_message').trigger('click');
	    }
	 });
	 
	 
	 $fmcg(document).on("click", "#chat_close", function(e) {
	       $fmcg("#frame").hide();
	 });
	
	 
	 
	 
	$fmcg(document).on('keyup', function(e) {
	    if (e.key == "Escape") 
	          $fmcg("#frame").hide();
	});
	  $fmcg(document).on("click", "#showattachment", function(e) {
	      message_sending_type="doc";
	      $fmcg("#txt_message").val('');
	       $fmcg("#attachment_cntr").toggle();
	 });
	 
	
	 $fmcg(document).on("click", "#imgAttachment", function(e) {
	     message_sending_type="doc";
	        message_type = 'image' ;
	        $fmcg("#chat_attachment").click();
	 });
	 $fmcg(document).on("click", "#documentAttachment", function(e) {
	     message_sending_type="doc";
	        message_type = 'document' ;
	         $fmcg("#chat_attachment").click();
	 });
	 $fmcg(document).on("click", "#chat_attachment", function(e) {
	         
	           $fmcg(".upload-type").hide();
	
	  });
	
	 $fmcg(document).on("change", "#chat_attachment", function(e) {
	
	         if( document.getElementById("chat_attachment").files.length != 0 ){
	
	
	          $fmcg(".upload-type").show();
	          $fmcg("#prevchatfilename").text('');
	          var file = $fmcg('#chat_attachment')[0].files[0].name;
	          $fmcg("#prevchatfilename").text(file);
	          if($fmcg('#chat_attachment')[0].files[0].type.split("/")[0] === "image")
	          {  
	             message_type = 'image' ;
	             $fmcg("#imgprevchat").show();
	             var image = document.getElementById('imgprevchat');
	             image.src = URL.createObjectURL(e.target.files[0]);
	          }
	          else
	           { 
	            $fmcg("#imgprevchat").hide();
	             message_type = 'document' ;
	          }
	        }
	        else
	            $fmcg(".upload-type").hide();
	
	 });
	 
	   var interval = setInterval(function () {
	       @if(Auth::guard('user')->check())
	             @if(Auth::guard('user')->user()->usertype!="guest") 
	                loadContacts();
	             @endif
	       @endif 
	 }, 5000);
	  var interval = setInterval(function () {
	      @if(Auth::guard('user')->check())
	             @if(Auth::guard('user')->user()->usertype!="guest")
	                checkNotifications();
	                if(senter!='')   
	                    checkNewMessage(last_message,senter);
	             @endif        
	      @endif
	   }, 5000);
	 function checkNotifications(){
	     $fmcg.ajax({
	    url:"{{route('checkNotifications')}}",
	    type: 'GET',  
	    success: function( data ) { 
	        if(data.wishlist_count>0){
	            $fmcg(".wishlist_count").html(data.wishlist_count);
	            $fmcg(".wishlist_count").show();
	        }else{
	             $fmcg(".wishlist_count").hide();
	        }
	        if(data.chat_count>0){
	            $fmcg(".chat-count").html(data.chat_count);
	            $fmcg(".chat-count").show();
	        }else{
	            $fmcg(".chat-count").hide();
	        }
	    },
	    error: function() {
	        //alert('Upload Failed');
	    }
	});  
	 }
</script>