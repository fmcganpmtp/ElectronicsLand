<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\CompanyType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Helpers\XMLWriter;

class BuyerExport implements FromCollection,ShouldAutoSize,WithHeadings
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

       $records=$records ->where('usertype','buyer')->where('seller_type','Master')->where('users.status','<>','Deleted')      
        ->orderBy('users.name','asc') 
        ->select(DB::raw("1 AS no"),'users.name','buyer_companies.company_name','users.email','users.phone',DB::raw("DATE_FORMAT(users.created_at, '%d-%m-%Y') as formatted_dob")
        ,'users.address',DB::raw("countries.name as country_name")
        ,DB::raw("(SELECT expairy_date FROM subscriptions WHERE subscriptions.user_id = users.id order BY subscriptions.id DESC limit 1) as expairy_date")
        ,DB::raw("'' AS cmpny_type"),
        'users.id','buyer_companies.company_type'
        )       
        ->get()->each(function ($row, $inr) {
                        $row->no = ++$inr;
                        $userId = $row->id;
                        $cmpny_type = $row->cmpny_type;
                        $user = User::find($userId);
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
        return [" SL No"," SELLER NAME ", " COMPANY NAME"," EMAIL"," PHONE"," CREATED AT"," ADDRESS"," COUNTRY NAME"," SUBSCRIPTION END"," Company Type"];
    }

     public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:J1')->applyFromArray([
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