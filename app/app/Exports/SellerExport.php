<?php

namespace App\Exports;

use App\User;
//use App\Models\SellerMessage;
use App\Models\Category;
use App\Models\SellerOfflineCategory;
use App\Models\SellerProduct;
use App\Models\CompanyType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Helpers\XMLWriter;

class SellerExport implements FromCollection,ShouldAutoSize,WithHeadings
{

     function __construct($search_key,$status) {
        $this->search_key = $search_key;
        $this->status = $status;
    } 
    public function collection()
    {
         

        $inr=0;
         $records = User::leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
        ->leftJoin('countries', 'countries.id', '=', 'users.country_id');
        if($this->status != '' || $this->status != null)
            $records=$records->where('status',$this->status);
        if($this->search_key != '' || $this->search_key != null) 
            $records=$records->where('users.name','Like','%'.$this->search_key.'%')
                ->orwhere('email','Like','%'.$this->search_key.'%')
                ->orwhere('phone','Like','%'.$this->search_key.'%')
                ->orwhere('buyer_companies.company_name','Like','%'.$this->search_key.'%')
                ->orwhere('countries.name','Like','%'.$this->search_key.'%');

       $records=$records ->where('usertype','seller') ->where('seller_type','Master')->where('users.status','<>','Deleted')     
        ->orderBy('users.name','asc') 
        ->select('buyer_companies.company_name',DB::raw("'' AS cmpny_type"),'users.name','users.surname','users.position','users.email','users.phone',DB::raw("countries.name as country_name"),
        DB::raw("CONCAT(buyer_companies.company_street, ',', buyer_companies.company_location, ',', buyer_companies.company_zip) as address"),DB::raw("'' AS ctgry")
        ,DB::raw("(SELECT date FROM subscriptions WHERE subscriptions.user_id = users.id order BY subscriptions.id DESC limit 1) as subscription_date"),DB::raw("(SELECT expairy_date FROM subscriptions WHERE subscriptions.user_id = users.id order BY subscriptions.id DESC limit 1) as expairy_date"),'users.id','buyer_companies.company_type')       
        ->get()->each(function ($row, $inr) {
                       // $row->no = ++$inr; 
                        $userId = $row->id;
                        $cmpny_type = $row->cmpny_type;
                        $user = User::find($userId);
                        $sellerProducts = $user->SellerProduct;
                        
                        ///////////////
        $parent_cat_id=[];
        $values = [];
        foreach ($sellerProducts as $sproduct) {
            $values[] = trim($sproduct->category_id);
            if($sproduct->status=="active" && $sproduct->product_visibility=="Yes"){
                $parent=Category::find($sproduct->category_id);
    
                if(!empty( $parent)) 
                 {  
                    $parent_id=$parent->id;
                    while(!empty($parent)) 
                    {   
                        $parent = $parent->parent;
                        if(!empty( $parent)) 
                            $parent_id=$parent->id;
                    }
                    $parent_cat_id[]= $parent_id; 
                }
            }
        }
        $parent_cat_id = array_unique($parent_cat_id);
       
        $values = array_unique($values);

        $parent_categorylists = Category::whereIn("id", $parent_cat_id)->orderBy('name',"ASC")->get();

        $category_product_count = [];
        foreach ($values as $row1) {
            $prdt_count = SellerProduct::where("status", "active")
                ->where("user_id", $userId)
                ->WhereRaw('find_in_set("' . $row1 . '",category_id)')
                ->count();
            $category_name = Category::find($row1, ["name", "category_pic"]);
            $category_product_count[] = [
                "product_count" => $prdt_count,
                "category" => $category_name,
            ];
        }
        arsort($category_product_count);
        $category_product_count = array_splice($category_product_count, 0, 3);

        $seller_Ofln_Cats = SellerOfflineCategory::select('category_id')->where('user_id', $userId)->first();
        if ($seller_Ofln_Cats) 
            $seller_offine_categorylists = explode(",", $seller_Ofln_Cats->category_id);
        else 
            $seller_offine_categorylists = []; 
        
        $categorylists = Category::whereIn("id", $parent_cat_id)
            ->orwhereIn("id", $seller_offine_categorylists)
            ->where('parent_id',null)
            ->whereNotNull('name')
            ->where('name','<>','')
            ->distinct() 
            ->orderBy('name','asc')
            ->pluck("name")
            ->all();
              $cats = implode( ', ', $categorylists );  
              $row->ctgry = $cats;
                        
                        
           $ctype_values = [];
            foreach (explode(",", $row->company_type) as $ctype_val) {
                $ctype_values[] = trim($ctype_val);
            }
			$ctype_values = array_unique($ctype_values);
			$ctypes = CompanyType::whereIn("id", $ctype_values)
            ->pluck("company_type")
            ->all();          
             $ctype = implode( ', ', $ctypes );  
             $row->cmpny_type = $ctype;           
                     
             $row->id = '';
             $row->company_type = '';
                        
                        
                    });


        return $records;  
       
    }

     public function headings() :array
    {
        
		return [" Company Name", " Company Type(s)"," Name"," Last name"," Job Title"," Mail address"," Phone#"," Country"," Location (City)"," Active product categories"," Subscription start","Subscription end"];
		//return [" SL No"," SELLER NAME ", " COMPANY NAME"," EMAIL"," PHONE"," CREATED AT"," ADDRESS"," COUNTRY NAME"," SUBSCRIPTION END"," Category"," Company Type"];
    }

     public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size'=>13,
                    ]
                ]);
            },
        ];
    }
 }   

?>