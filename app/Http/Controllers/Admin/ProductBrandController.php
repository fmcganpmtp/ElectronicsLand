<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;

use App\Models\Productbrand;
use App\Models\Product;

class ProductBrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    //display brands listing page
    public function index(Request $request)
    {
        
        return view('admin.brands.index');
    }
    //ajax request method returns json result of brands
    public function getproductBrandlist(Request $request)
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
        $searchValue=$request->get('search_key');
        //total brands count
        $totalRecords =Productbrand::select('count(*) as allcount')->count();
        //total filtered data counts
        $totalRecordswithFilter =Productbrand::select('count(*) as allcount')
            ->where('name','Like','%'.$searchValue.'%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Productbrand::where('name','Like','%'.$searchValue.'%')
            ->orderBy($columnName,$columnSortOrder)         
            ->skip($start)
            ->take($rowperpage)->get();
        $data_arr = array();
       
        foreach ($records as $record) {
              
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "image" =>$record->image ? asset('/assets/uploads/brands/').'/'.$record->image : asset('/uploads/defaultImages/no_image.jpg'),       
                );
         }
        //json result
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
    }
    //display brand add form
    public function create()
    {
        return view('admin.brands.create');
    }
    //save single brand data 
    public function store(Request $request)
    {
        //data validation
        $request->validate([
            'name' =>  'required|unique:productbrands,name',
        ],[
            'name.required'=>'The Brand has already been taken.',
            'name.unique'=>'The Brand has already been taken.'
        ]);

        $image = $request->file('image');
        $fileName = '';
        //save image into server folder
        if($image){
            $request->validate([
                'image' =>  'mimes:jpeg,jpg,png,gif,webp|max:1000||dimensions:max_width=150,max_height=100',
            ]);

            $fileName = time().'.'.$request->image->extension();
            $request->image->move(public_path('/assets/uploads/brands/'), $fileName);
        }
        //create new brand
        Productbrand::create([
            'name'             =>   $request->name,
            'image'            =>   $fileName
        ]);

        return redirect()->route('admin.brands')->with('message', 'Brand Added successfully.');
    }
    //display edit form
    public function edit($id = null)
    {
        if($id){
            //fetch brand data
            $data = Productbrand::find($id);
            if($data){
                return view('admin.brands.edit', compact('data'));
            } else {
                return redirect()->back()->withErrors('Sorry.. Brand details not found.');
            }
        } else {
            return redirect()->route('admin.brands')->withErrors('Sorry... Something went wrong.');
        }
    }
    //update brand data
    public function update(Request $request, $id = null)
    {
        if($id){
            //fetch brand data
            $Branddetails = Productbrand::find($id);

            if($Branddetails){
                $image = $request->file('image');
                $fileName = '';
                //image validation
                if($image) {
                    $request->validate([
                        'name'    =>  'required|unique:productbrands,name,'.$id,
                        'image'   =>  'mimes:jpeg,jpg,png,gif,webp|max:1000||dimensions:max_width=150,max_height=100'
                    ],[
                        'name.required'=>'The Brand has already been taken.',
                        'name.unique'=>'The Brand has already been taken.'
                    ]);
                    //delete old image
                    if($Branddetails->image != ''){
                        $image_path = public_path('/assets/uploads/brands/').'/'.$Branddetails->image;
                        File::delete($image_path);
                    }
                    //save new image
                    $fileName = time().'.'.$request->image->extension();
                    $request->image->move(public_path('/assets/uploads/brands/'), $fileName);
                    //update brand data
                    Productbrand::find($id)->update([
                        'name'  =>  $request->name,
                        'image' =>  $fileName
                    ]);
                } else {
                    $request->validate([
                        'name'  =>  'required|unique:productbrands,name,'.$id,
                    ]);
                    Productbrand::find($id)->update([
                        'name'  =>  $request->name,
                    ]);
                }

                return redirect()->route('admin.brands')->with('message', 'Brand details updated successfully');
            } else {
                return redirect()->back()->withErrors('Sorry... Updation failed. Brand details not found.');
            }
        } else {
            return redirect()->back()->withErrors('Sorry... Something went wrong.');
        }

    }
    //delete brand data
    public function destroy($id = null)
    {
        if($id){
            $Branddetails = Productbrand::find($id);
            //checking brand exist in db
            if($Branddetails){
                $Brandexist = Product::where('brands', $Branddetails->id)->where('status','active')->exists();
                if(!$Brandexist){
                    //delete image
                    if(!empty($Branddetails) && $Branddetails->image != '') {
                        $file_path = public_path('/assets/uploads/brands/').$Branddetails->image;
                        File::delete($file_path);
                    }
                    Productbrand::find($id)->delete();
                    return redirect()->route('admin.brands')->with('message', 'Brand deleted successfully');
                } else {
                    return redirect()->back()->withErrors('Sorry... Delete failed. Brand exists some products.');
                }
            } else {
                return redirect()->back()->withErrors('Sorry... Delete failed. Brand details not found.');
            }
        } else {
            return redirect()->route('admin.brands')->withErrors('Sorry... Something went wrong.');
        }
    }
    
    //ajax call remove image
    public function remove_brandimage(Request $request)
    {
        if($request->id != ''){
            //fetching brand data
            $Brand = Productbrand::find($request->id);
            if($Brand){
                //delete brand image
                if($Brand->image != ''){
                    $imagefile = public_path('/assets/uploads/brands/').$Brand->image;
                    File::delete($imagefile);
                    Productbrand::find($request->id)->update(['image'=>'']);

                    $returnArray['result'] = true;
                    $returnArray['message'] = 'Brand image removed successfully.';
                } else {
                    $returnArray['result'] = false;
                    $returnArray['message'] = 'Failed. Image not found.';
                }
            } else {
                $returnArray['result'] = false;
                $returnArray['message'] = 'Failed. Brand details not found.';
            }
        } else {
            $returnArray['result'] = false;
            $returnArray['message'] = 'Failed. Brand ID not found.';
        }
        return response()->json($returnArray);
    }
}
