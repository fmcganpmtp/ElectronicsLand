<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;

use App\Models\Testimonial;

class TestimonialsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    //display content apge
    public function index(Request $request)
    {
       return view('admin.testimonials.index');
    }
    //ajax call return testimonial datas
    public function gettestimoniallist(Request $request)
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
        //total count of testimonial
        $totalRecords =Testimonial::select('count(*) as allcount')->count();
        //total filtered count
        $totalRecordswithFilter =Testimonial::select('count(*) as allcount')
            ->where('id','Like','%'.$searchValue.'%')
            ->orwhere('name','Like','%'.$searchValue.'%')
            ->orwhere('company_name','Like','%'.$searchValue.'%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Testimonial::where('id','Like','%'.$searchValue.'%')
            ->orwhere('name','Like','%'.$searchValue.'%')
            ->orwhere('company_name','Like','%'.$searchValue.'%')
            ->orderBy($columnName,$columnSortOrder)         
            ->skip($start)
            ->take($rowperpage)->get();
        $data_arr = array();
       
        foreach ($records as $record) {
              
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "company_name" => $record->company_name,
                "title" => $record->title,
                "comments" => $record->comments,
                "profile_pic" =>$record->profile_pic ? asset('/assets/uploads/testimonials/').'/'.$record->profile_pic : asset('/uploads/defaultImages/no_image.jpg'),               
               );
       
         }
        //response data
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
    }

    public function table()
    {
        
        return view('admin.testimonials.table');
    }
    //display testimonial  create form
    public function create()
    {
        return view('admin.testimonials.create');
    }
//save single testimonial
    public function store(Request $request)
    {
        //data validation
        $this->validate($request, [
            'name' =>  'required',
            'company' => 'required',
            'title' => ['required','max:280'],
            'comments' =>['required','max:280'],
        ]);

        $fileName = '';
        $file = $request->file('profile_pic');
        //image validation
        if($file) {
            $this->validate($request, [
                'profile_pic' =>  'required|mimes:jpeg,jpg,webp,png,svg|max:2048',
            ]);
            //save image to serve
            $fileName = time().'.'.$request->profile_pic->extension();
            $request->profile_pic->move(public_path('/assets/uploads/testimonials/'), $fileName);
        }
        //save new testimonial
        Testimonial::create([
            'profile_pic'=>$fileName,
            'name'=>$request->name,
            'company_name'=>$request->company,
            'title'=>$request->title,
            'comments'=>$request->comments,
            'star_rating'=>$request->star_rating,
        ]);

        return redirect()->route('admin.testimonials')->with('message','Testimonial created successfully');
    }
    //display edit form
    public function edit($id)
    {
        //fetch testimonial editing item data
        $testimonials = Testimonial::find($id);
        if($testimonials){
            return view('admin.testimonials.edit',compact('testimonials'));
        } else {
            return redirect()->back()->withErrors('Testimonial not found.');
        }
    }
    //update testimonial data
    public function update(Request $request, $id)
    {
        //fetch updating testimonial data
        $Testimonials = Testimonial::find($id);
        //data validation
        if($Testimonials){
            $this->validate($request, [
                'name' =>  'required',
                'company' => 'required',
                'title' => ['required','max:280'],
                'comments' => ['required','max:280']
            ]);

            $UpdateArray['name'] = $request->name;
            $UpdateArray['company_name'] = $request->company;
            $UpdateArray['title'] = $request->title;
            $UpdateArray['comments'] = $request->comments;
            $UpdateArray['star_rating'] = $request->star_rating;

           
            //image validation
            $file = $request->file('profile_pic');
            if($file){
                $this->validate($request, [
                    'profile_pic' =>  'required|mimes:jpeg,webp,jpg,png,svg|max:2048',
                ]);
            //delete old image
            if($Testimonials->profile_pic != ''){
                $image_path = public_path('/assets/uploads/testimonials/').'/'.$Testimonials->profile_pic;
                File::delete($image_path);
            }
                //new image saving
                $file = $request->file('profile_pic');

                $fileName = time().'.'.$request->profile_pic->extension();

                $request->profile_pic->move(public_path('/assets/uploads/testimonials/'), $fileName);

                $UpdateArray['profile_pic'] = $fileName;
            }
            //updating new testimonial data
            Testimonial::find($id)->update($UpdateArray);

            return redirect()->route('admin.testimonials')->with('message','Testimonial updated successfully');
        } else {
            return redirect()->back()->withErrors('Error: Not updated. Testimonial details not found.');
        }

    }
    //deleting testimonial data
    public function destroy($id)
    {
        //feching testimonial data for delete image 
        $Testimonials = Testimonial::find($id);
        if($Testimonials){
            if($Testimonials->profile_pic != ''){
                $image_path = public_path('/assets/uploads/testimonials/').'/'.$Testimonials->profile_pic;
                File::delete($image_path);
            }
            //delete testimonial
            Testimonial::find($id)->delete();

            return redirect()->route('admin.testimonials')->with('message','Testimonial deleted successfully.');
        } else {
            return redirect()->back()->withErrors('Error: Not updated. Testimonial details not found.');
        }
    }
    
    //ajax request testimonial image
    public function remove_testimonialimage(Request $request)
    {
        if($request->id != ''){
            $Testimonial = Testimonial::find($request->id);
            if($Testimonial){
                if($Testimonial->profile_pic != ''){
                    $imagefile = public_path('/assets/uploads/testimonials/').$Testimonial->profile_pic;
                    File::delete($imagefile);
                    Testimonial::find($request->id)->update(['profile_pic'=>'']);

                    $returnArray['result'] = true;
                    $returnArray['message'] = 'Testimonial image removed successfully.';
                } else {
                    $returnArray['result'] = false;
                    $returnArray['message'] = 'Failed. Image not found.';
                }
            } else {
                $returnArray['result'] = false;
                $returnArray['message'] = 'Failed. Testimonial details not found.';
            }
        } else {
            $returnArray['result'] = false;
            $returnArray['message'] = 'Failed. Testimonial ID not found.';
        }
        return response()->json($returnArray);
    }
}
