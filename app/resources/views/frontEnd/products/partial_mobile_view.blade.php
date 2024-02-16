

            <ul>
				@foreach($menu_items as $items)
				@php
            	 $Clist=App\Models\Category::where('parent_id',$items->id)->where('have_product','Yes')->get();

            	 @endphp

				@if(count($Clist)>0)
            	 
              			<li><a href="javascript:void(0)">{{$items->name}}</a>
              			@include('frontEnd/products/partial_mobile_view',['menu_items' => $Clist])
              	</li>
              	
              	@else
              	
              	<li> <a href="{{route('Product.Listing',['search_key'=>$items->slug])}}">{{$items->name}}</a> </li>
              	@endif

              	@endforeach
            </ul>
  
