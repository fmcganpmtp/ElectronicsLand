<?php $dash.='-- '; ?>
@foreach($subcategories as $subcategory)
    <?php $_SESSION['i']=$_SESSION['i']+1; ?>
    <tr>
        <td>{{$_SESSION['i']}}</td>
        <td>{{$dash}}{{$subcategory->name}}</td>
        <td><div class="table-prof"><img src ="{{ $subcategory->category_pic ? asset('uploads/categoryImages/').'/'.$subcategory->category_pic : asset('/uploads/defaultImages/pop-ic-4.png')}}" ></div></td>

        <td>{{$subcategory->parent->name}}</td>
        <td><div class="icon-bx"> <a href="{{  route('view.category',$subcategory->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a> <a href="{{ route('edit.category',$subcategory->id) }}"><i class="icon  cil-pencil"></i></a> 
                          <a onclick="deleteconfirm({{$subcategory->id}})" href="JavaScript:void(0);"><i class="icon cil-trash"></i></a> </div></td>
    </tr>
    @if(count($subcategory->subcategory))
        @include('admin/categories/sub-category-list',['subcategories' => $subcategory->subcategory])
    @endif



@endforeach