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

class PromoNewsletterExport implements FromCollection,ShouldAutoSize,WithHeadings
{

     function __construct($search_key) {
        $this->search_key = $search_key;
    } 
    public function collection()
    {
         

        $inr=0;
        $search_key= $this->search_key;
        $records=User::where('hide_promo_email','No')->where('users.status','<>','Deleted')
                 ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
                ->when($search_key!='', function ($query) use ($search_key) {
                          $query->where('email', 'like', '%'.$search_key.'%');
                      })
                //->select(DB::raw("1 AS no"),'email') ;
                ->select(DB::raw("1 AS no"), 'name','surname','email','company_name');
          

       $records=$records->orderBy('email','asc') 
        ->get()->each(function ($row, $inr) { $row->no = ++$inr; });


        return $records;  
       
    }

     public function headings() :array
    {
        return [" SL No","NAME","SURNAME","EMAIL","COMPANY NAME"];
    }

     public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:E1')->applyFromArray([
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