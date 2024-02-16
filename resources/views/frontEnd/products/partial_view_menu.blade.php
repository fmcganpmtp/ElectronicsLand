

              	 @foreach($menu_items as $items)
              	 @php
                    $Clist=App\Models\Category::where('parent_id',$items->id)->where('have_product','Yes')->get();
            	 @endphp

				 @if(count($Clist)>0)
            	 @php
            	 $category = App\Models\Category::where("id",$items->id)->first();
                 $cnt=$category->getParentsNames()->count()+1;
                 

                 @endphp	
            	 		<li><a href="{{route('Product.Listing',['search_key'=>$items->slug])}}">{{$items->name}} <i class="fa fa-angle-right" aria-hidden="true"></i></a>
            	 		 <ul class="level-{{$cnt}}">
            	 		 	 <li><h6>{{$items->name}}</h6></li>
            	 		
              			@include('frontEnd/products/partial_view_menu',['menu_items' => $Clist])
              		</ul>
              		</li>
				@else
				
              	 <li><a href="{{route('Product.Listing',['search_key'=>$items->slug])}}">{{$items->name}}</a></li>
              	@endif
              	@endforeach
                	
              	

  
