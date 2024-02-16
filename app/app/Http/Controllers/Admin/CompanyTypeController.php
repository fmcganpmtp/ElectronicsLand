<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CompanyType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyTypeController extends Controller
{
    //loading company type adding form
    public function createCompanyType() {
        return view('admin.company-type.create-company-type',['CompanyType' => '']);
    }
    //save new comapny type
    public function saveCompanyType(Request $request){
        //validation adding
      request()->validate([
        "company_type" => ['required','string', 'max:255', 'unique:company_types']
      ]);
      $input = $request->all(); 
      //save new company type
      CompanyType::create($input);
      return redirect()->route('list.company.type')->with('message','Company Type Created');
    }
    //list page  company type
    public function listCompanyTypes() {
        $CompanyTypes = CompanyType::paginate(20);
        return view('admin.company-type.list-company-type',compact('CompanyTypes'));
    }
    // company type data table loading  ajax calling method
    public function getcompanytypevalues(Request $request)
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
        $searchValue = $search_arr['value']; // Search value  
        //total company type count
        $totalRecords = CompanyType::count();
        //total filtered company type count
        $totalRecordswithFilter = CompanyType::select('count(*) as allcount')
            ->where('company_type', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = CompanyType::orderBy($columnName,$columnSortOrder)
            ->where('company_type', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $data_arr[] = array(
                "id"=> $record->id,
                "company_type" => $record->company_type,
                "created_at" => date('d-m-Y h:m:s a', strtotime($record->created_at))
                );
        }
        //ajax response data
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
}
    //loading edit form for company type
    public function editCompanyType($CompanyTypeId) {
        $CompanyType = CompanyType::find($CompanyTypeId);
        if(empty($CompanyType )) 
            return redirect() ->route('list.company.type')->with('message','CompanyType Not Exits');
        return view('admin.company-type.create-company-type' ,compact('CompanyType'));
    
    }
    //update company type
    public function updateCompanyType(Request $request) {
        request()->validate([
            'company_type' => ['required', 'string', 'max:255',Rule::unique('company_types')->ignore($request->get('id'))]
        ]);

        $CompanyType_id = $request->get('type_id');
        //fetching data for updation
        $CompanyType = CompanyType::find($CompanyType_id);    
        $input = $request->all();
        //update company type
        $CompanyType->update($input);
        return redirect()->route('list.company.type')->with('message','Company Type Updated');
    }
    //delete single company type
    public function deleteCompanyType($CompanyTypeId)
    {
        $CompanyType =  CompanyType::find($CompanyTypeId);
        if(empty($CompanyType)) 
            return redirect()->route('list.company.type')->with('message', 'Company Type not Exists');     
        $CompanyType->delete();
        return redirect()->route('list.company.type')->with('message', 'Company Type Deleted!');
    }
}
