<?php $dash.='-- '; ?>
@foreach($subcategories as $subcategory)
    @if($item->id != $subcategory->id )
        <option value="{{$subcategory->id}}"  @if( in_array($subcategory->id,$selectedCategories)) selected @endif  >
        	{{$dash}}{{$subcategory->name}}
        </option>
    @endif
    @if(count($subcategory->subcategory))
        @include('admin/products/category-edit',['subcategories' => $subcategory->subcategory])
    @endif
@endforeach