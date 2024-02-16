<?php $dash.='-- ';?>
@foreach($subcategories as $subcategory)
    
    <option value="{{$uplevel.'>'.$subcategory->name}}">{{$dash}}{{$subcategory->name}}</option>
    @if(count($subcategory->subcategory))
        @include('admin/products/subcategorylist_name_option',['subcategories' => $subcategory->subcategory,'uplevel'=>$uplevel.'>'.$subcategory->name
])
    @endif
@endforeach