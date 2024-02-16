@if(Auth::guard('user')->check())
 
<div id="frame" style="display:none; width:auto;">
    <div class="chat-close" id="chat_close"><i class="fa fa-times" aria-hidden="true"></i>
</div>
	<div id="sidepanel">
		<div id="profile">
			<div class="wrap">
				@php	
				    if(!empty(Auth::guard('user')->user()->profile_pic)) 
				        $img_path = asset('/uploads/userImages/').'/'.Auth::guard('user')->user()->profile_pic;
                    else  
                        $img_path = asset('uploads/defaultImages/default_avatar.png');
                @endphp
                        <img class="avatar-img" src="{{$img_path}} " alt="{{ Auth::guard('user')->user()->name ?? '' }}">
				<p>{{ Auth::guard('user')->user()->name ?? '' }}</p>
			
				<div id="status-options">
					<ul>
						<li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>
						<li id="status-away"><span class="status-circle"></span> <p>Away</p></li>
						<li id="status-busy"><span class="status-circle"></span> <p>Busy</p></li>
						<li id="status-offline"><span class="status-circle"></span> <p>Offline</p></li>
					</ul>
				</div>
				<div id="expanded">
					<label for="twitter"><i class="fa fa-facebook fa-fw" aria-hidden="true"></i></label>
					<input name="twitter" type="text" value="mikeross" />
					<label for="twitter"><i class="fa fa-twitter fa-fw" aria-hidden="true"></i></label>
					<input name="twitter" type="text" value="ross81" />
					<label for="twitter"><i class="fa fa-instagram fa-fw" aria-hidden="true"></i></label>
					<input name="twitter" type="text" value="mike.ross" />
				</div>
			</div>
		</div>
		 <div id="search">
			<label for="" onclick="fnsearchcontact()" ><i class="fa fa-search" aria-hidden="true"></i></label>
			<input type="text" id="chat_search_contact" placeholder="Search contacts..."  />
			<div class="searchcancel" onclick="fnclearsearch()"><i class="fa fa-times" aria-hidden="true"></i></div>
		</div>
		<div id="contacts_chat">
			 
		</div>
		<!-- <div id="bottom-bar">
			<button id="addcontact"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i> <span>Add contact</span></button>
			<button id="settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> <span>Settings</span></button>
		</div> -->
	</div>
	<div class="content" style="display:none;" id="divmaincontainer" >
		<div class="contact-profile" id="chat_profile" style="cursor:pointer">
			
			 </div>
		<div class="messages" id="messageDiv">
		    <div class="ajax-loading" style="display:none;"><img src="{{ asset('images/ajax-loder.gif')}}" style="width:60px;"/></div>
			<ul class="chatMessages">
				 
			</ul>
			
		</div>
		<div class="message-input"  style="display:none;"> 
			<div class="upload-type" style="display:none;">
				<img id="imgprevchat" src="" class="upload-image-ic">
				<span id="prevchatfilename"></span>
		    </div>		
			<div class="wrap">
			 <div data-emojiarea data-type="unicode"  data-global-picker="true"  data-anchor-alignment="right">
                	<div class="emoji-button" onclick="fnemoji()">&#x1f604;</div>    
					<!--<input type="text" placeholder="Write your message..." id="txt_message" />-->
					<textarea placeholder="Write your message..." rows="3" id="txt_message"></textarea>
			</div>
			<input type="hidden"  id="selected_id" value="144" />
			<a href="javascript:void(0)" id="showattachment"><i class="fa fa-paperclip attachment" aria-hidden="true"></i></a>
			<div id="attachment_cntr" style="display:none">
			    <a href="javascript:void(0)" id="imgAttachment"> <i class="fa fa-picture-o" aria-hidden="true"></i> Image</a>
			    <a href="javascript:void(0)" id="documentAttachment"><i class="fa fa-file-text-o" aria-hidden="true"></i> Document</a>
			    <input type="file" id="chat_attachment"  style="visibility:hidden"   />
			</div>
			 
			<button class="submit" id="btn_sent_message"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
			 
		</div>
	</div>
</div>
</div>
@endif

 