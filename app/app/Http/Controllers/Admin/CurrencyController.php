<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Product;
use App\Models\SellerProduct;
use App\Models\SellerProductTemp;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class CurrencyController extends Controller
{
    //display currency add form
     public function createCurrencies() {
        return view('admin.currency.create-currency',['Currency' => '']);		
    }
     public function currencymerge(Request $request)
    {
        $cur_ids=$request->get('cur_ids');
        $merge_id=$request->get('merge_id');
        
        if (($key = array_search($merge_id, $cur_ids)) !== false) {
                unset($cur_ids[$key]);
        }
        
        
        
       // $first_value = reset($cur_ids); //take first currency id
       // $cur_ids = array_slice($cur_ids,1); //remove first value from currency ids
       foreach($cur_ids as $cur_id)
       {
           $currency =  Currency::find($cur_id)->delete();
           $data = [
            'currency_id'=>$merge_id,
            ]; 
            Product::where('currency_id',$cur_id)->update($data);
            SellerProduct::where('currency_id',$cur_id)->update($data); 
            SellerProductTemp::where('currency_id',$cur_id)->update($data); 
           
       }
       
       
         //   SellerProductTemp::whereIn('id',$cur_ids)->delete();
        echo json_encode('Currency Merged');
    }
    //save new currencies
    public function saveCurrencies(Request $request){
        //data validation
      request()->validate([
        "name" => ['required','string', 'max:255', 'unique:currencies'],
		"shortcode" => ['required','string', 'max:3', 'unique:currencies'],
		"symbol" => ['required','string', 'max:100','unique:currencies']
      ]);
      
     $input['name'] = $request->get('name');
	 $input['shortcode'] = $request->get('shortcode');
	 $input['symbol'] = $request->get('symbol');
	 
	 $last_car = substr($request->get('symbol'), -1);
	 if($last_car == ";")
	 {
	 $second_case = rtrim($request->get('symbol'), ";");
	 }
	 else
	 {
	 $second_case = $request->get('symbol').";";
	 }
	 $input['symbol'] = str_replace(';', '', $request->get('symbol'));
	 
	 
	 
	 
	 $symbol = Currency::where('symbol', '=', $request->get('symbol'))
	 ->orWhere('symbol', '=', $second_case)
	 ->first();
    if ($symbol === null) {
   // currency doesn't exist
        //save currency
        Currency::create($input);
        return redirect()->route('list.currency')->with('message','Currency Created');
    }
	 else{
	     return redirect()->back()->with('msg', 'Currency Symbol has already added'); 
	 }
	 
    }
    //fetch all currency
    public function listCurrencies() {
        $Currencies = Currency::all();    
        return view('admin.currency.list-currency',compact('Currencies'));
    }
    //ajax call fetch all currency list
    public function getCurrenciesvalues(Request $request)
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
        //total currency count
        $totalRecords = Currency::count();
        //total filtered data count
        $totalRecordswithFilter = Currency::select('count(*) as allcount')
            ->when($searchValue != '', function ($q)  {
                return $q ->where(function ($query) {
                $query ->where('name', 'like', '%' . $searchValue . '%');
                });
            })
        ->count();

        // Get records, also we have included search filter as well
        $records = Currency::orderBy('id', 'ASC')
             ->when($searchValue != '', function ($q)  {
                    return $q ->where(function ($query) {
                         $query ->where('name', 'like', '%' . $searchValue . '%');
                     });
            })
        ->skip($start)
        ->take($rowperpage)
        
        ->get();
        $data_arr = array();
        foreach ($records as $record) {
            
            $data_arr[] = array(
                "id"=> $record->id,
                "name" => $record->name,
				"shortcode" => $record->shortcode,
				"symbol" => $record->symbol,
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
    //load edit form
    public function editCurrencies($CurrencyId) {
        $Currency = Currency::find($CurrencyId);
        if(empty($Currency )) 
            return redirect() ->route('list.currency')->with('message','Currency Not Exits');
        return view('admin.currency.create-currency' ,compact('Currency'));
    
    }
    //update edit currncy data
    public function updateCurrencies(Request $request) {
		//data validation
        request()->validate([                           
            'name' => ['required', 'string', 'max:255',Rule::unique('currencies')->ignore($request->get('currency_id'), 'id')],
			'shortcode' => ['required', 'string', 'max:3',Rule::unique('currencies')->ignore($request->get('currency_id'), 'id')],
			"symbol" => ['required','string', 'max:100']
        ]);

        $currency_id = $request->get('currency_id');
        $Currency = Currency::find($currency_id);
		$input['name'] = $request->get('name');
		$input['shortcode'] = $request->get('shortcode');
		$input['symbol'] = $request->get('symbol');
		//updata currency data 
        $Currency->update($input);
        return redirect()->route('list.currency')->with('message','Currency Updated');
    }
    //delete currency
    public function deleteCurrencies($CurrencyId)
    {
        $Currency =  Currency::find($CurrencyId);
        if(empty($Currency)){ 
            return redirect()->route('list.currency')->with('message', 'Currency not Exists');
        }
        $Currency->delete();
        return redirect()->route('list.currency')->with('message', 'Currency Deleted!');
    }
}
