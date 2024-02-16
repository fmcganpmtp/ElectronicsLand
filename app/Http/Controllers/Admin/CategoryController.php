<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\Category;
use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
{
    //loading category page
    public function listCategories(Request $request) {
        // for search
            $search_key=$request->get('search_key');
            // $categories = Category::where('parent_id', null)->latest();
            // if($search_key!=null || $search_key!='') 
            //     $categories= $categories->where('name','Like','%'.$search_key.'%');
            // else 
            //     $categories =$categories->orderby('name', 'desc'); 

            // $categories= $categories->paginate(20);

        return view('admin.categories.list-categories',compact('search_key'));
    }
    //fetch all active categories
      public function getcategorylist(Request $request)
    {  
         
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
       
                $draw = $request->get('draw');
                $start = $request->get("start");
                $rowperpage = $request->get("length"); // total number of rows per page
                $columnIndex = $columnIndex_arr[0]['column']; // Column index
                $columnName = $columnName_arr[$columnIndex]['data']; // Column name
                $columnSortOrder = $order_arr[0]['dir']; // asc or desc
               // $searchValue = $search_arr['value']; // Search value
        $searchValue=$request->get('search_key');
        //total categories count
        $totalRecords = Category::select('count(*) as allcount') ->where('parent_id', null)->count();
        //total filtered categories count
        $totalRecordswithFilter = Category::select('count(*) as allcount')
        ->where('parent_id', null)
        ->where('name','Like','%'.$searchValue.'%')
        ->count();

        // Get records, also we have included search filter as well
        $records = Category::select('*')
            ->orderBy($columnName,$columnSortOrder)
            ->where('parent_id', null)
            ->where('name','Like','%'.$searchValue.'%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
           
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "pic" => $record->category_pic ? asset('uploads/categoryImages/').'/'.$record->category_pic : asset('/uploads/defaultImages/pop-ic-4.png'),
                "parent" => $record->parent_id!=''?$record->subcategory->name :'None'
                
               );
        }
        // ajax call response data
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
}
    //loading new form for category adding
    public function createCategory(Request $request)
    {
        $categories = Category::where('parent_id', null)->orderby('name', 'asc');
           return view('admin.categories.create-category', compact('categories'));
    }
    //adding new category data
    public function saveCategory(Request $request) {
       $parent_id=$request->parent_id;
       
       $request->merge(array_map('trim', $request->all()));
       //data validation adding
        $validator = $request->validate([
            'name'      => Rule::unique('categories')->where(function ($query) use ($parent_id) {
                return $query->where('parent_id', $parent_id);
           }),
           // 'slug'      => 'required|unique:categories',
            'image' => 'required|image|mimes:jpeg,png,bmp,gif,svg',
            'parent_id' => 'nullable|numeric'
        ]);
       
        $search_key='';
        $seo_url = $this->create_slug($request->name);
        $input = [
            'name' => $request->name,
            'slug' => $seo_url,
            'parent_id' =>$request->parent_id
        ];
        //category image saving to server folder
        if(request()->hasFile('image')) {
            $extension = request('image')->extension();
            $fileName = "category_pic".time().'.'.$extension;
            $destinationPath = public_path().'/uploads/categoryImages' ;
            request('image')->move($destinationPath,$fileName);
            $input['category_pic'] = $fileName;
        } 
        //creating new category
        Category::create( $input );
        $categories = Category::where('parent_id', null)->orderby('name', 'asc')->paginate(20);
      
        if($request->parent_id!=null)
         return redirect()->route('view.category',$request->parent_id)->with('success', 'Category has been created successfully.');
        else
        return redirect()->route('category.list')->with('success', 'Category has been created successfully.');
       
       
    
    }
    //creating user friendly urlname adding
     public function create_slug($string)
    {
        $items = array("index", "create_slug", "show", "create", "store", "edit", "update", "destroy");
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        $categories_cnt = Category::where(DB::raw('lower(slug)'),strtolower($slug))->get()->count();;
        if (in_array($slug, $items) || $categories_cnt>0) 
            $slug = $slug .'-'. date('ymd').time();
        return $slug;
    }
    //loading edit form for category
        public function editCategory($categoryId){
            //select category data
            $category = Category::find($categoryId);
            $categories = Category::where('parent_id', null)->where('id', '!=', $category->id)->orderby('name', 'asc')->get();
             
            if(empty($category)) {
                return redirect()->route('category.list')->with('success','No Category found!');
            }
            return view('admin.categories.edit-category',compact('category','categories'));
        }
        //delete single category
        public function deleteCategory($categoryId){
            $category = Category::find($categoryId);
            if(empty($category)) {
                return redirect()->route('category.list')->with('success','No Category Found');
            }
            $category->delete();
            return back()->with('success','Category deleted successfully!');
        }
        //update category data
        public function updateCategory(Request $request){
            $id = $request->get('cat_id');
            $parent_id= $request->get('parent_id');
            //selecting data for update
            $category = Category::findOrFail($id);
            //data validation
            $request->merge(array_map('trim', $request->all()));
            request()->validate([
                'name' => Rule::unique('categories')->ignore($id)->where(function ($query) use ($parent_id) {
                return $query->where('parent_id', $parent_id);
                    }),
               // 'slug' => ['required', 'string',  'max:255', Rule::unique('categories')->ignore($id)],
               'image' => 'image|mimes:jpeg,png,bmp,gif,svg',
               'parent_id' => 'nullable|numeric'
            ]);
            //slug creating
            $seo_url = $this->create_slug($request->name);
            
            $input = [
                'name' => $request->name,
                'slug' => $seo_url,
                'parent_id' =>$request->parent_id==''?null:$request->parent_id
            ];
            
            //upload image into server folder
            if(request()->hasFile('image')) {
                $extension = request('image')->extension();
                $fileName = "category_pic".time().'.'.$extension;
               // request('image')->storeAs('images',$fileName);
                $destinationPath = public_path().'/uploads/categoryImages' ;
                request('image')->move($destinationPath,$fileName);
                $input['category_pic'] = $fileName;
            } else { $input['category_pic'] = $category->category_pic; }
            $category->update($input);
        return back()->with('success','Category Updated');
        }
        //loading category data viewing form
        public function viewCategory($categoryId){
            $category = Category::find($categoryId); 
              if(empty($category)) {
                    return redirect()->route('category.list')->with('success','No Category Found');
                } 
              return view('admin/categories/view-category',compact('category'));  
            }
    //ajax method for searching category
            public function searchCategory( Request $request){

                $output = "";
                         $categories = Category::where('name','Like','%'.$request->search.'%')
                        ->get();
                        //fetch all categories
                        foreach($categories as $category)
                        {
                            $output.='<tr> <td>'.$category->name.'</td>';
                            if($category->category_pic)
                                $imgPath = asset('/uploads/categoryImages/').'/'.$category->category_pic;
                            else $imgPath = asset('/uploads/defaultImages/pop-ic-4.png');
                            $output.='<td><div class="table-prof"><img style=" width:60px !important;" class="pr_img" src="'.$imgPath.' "></div></td>';
                            $output.='<td> ';
                            if(isset($category->parent_id)) echo $category->subcategory->name; 
                            else echo "None";
                            $output.='</td>
                            <td><div class="icon-bx">'.' <a href="/admin/category/edit/'.$category->id  .' ">'.'<i class="icon  cil-pencil">'.'</i></a> 
                                          '.'<a href="/admin/category/delete/'.$category->id  .' ">'.'<i class="icon cil-trash">'.'</i></a> </div></td>
                            </tr>';
                        }
                        return Response($output);
                                     
                }
        

}