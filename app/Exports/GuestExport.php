<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Helpers\XMLWriter;

class GuestExport implements FromCollection,ShouldAutoSize,WithHeadings
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

       $records=$records ->where('usertype','guest')->where('seller_type','Master')->where('users.status','<>','Deleted')      
        ->orderBy('users.name','asc') 
        ->select(DB::raw("1 AS no"),'users.name','buyer_companies.company_name','users.email','users.phone',DB::raw("DATE_FORMAT(users.created_at, '%d-%m-%Y') as formatted_dob"),'users.address',DB::raw("countries.name as country_name"))       
        ->get()->each(function ($row, $inr) {
                        $row->no = ++$inr;
                    });


        return $records;  
       
    }

     public function headings() :array
    {
        return [" SL No"," SELLER NAME ", " COMPANY NAME"," EMAIL"," PHONE"," CREATED AT"," ADDRESS"," COUNTRY NAME"];
    }

     public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:H1')->applyFromArray([
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