<?php

namespace App\Exports;

use App\User;
use App\Models\NewsletterSubscription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class NewsletterExport implements FromCollection,ShouldAutoSize,WithHeadings
{

     function __construct($user_types) {
        $this->user_types = $user_types;
    } 
    public function collection()
    {
         

        $inr=0;
        if($this->user_types=="")
        {
        
        $p1 = DB::table('newsletter_subscriptions')
            ->select(DB::raw("1 AS no"),'email',DB::raw("NULL as surname"),DB::raw("NULL as name"),DB::raw("NULL as company_name"));

        $p2 = DB::table('users')
                ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
                ->where('newsletter_status', 'Yes')
                ->where('users.status','<>','Deleted')
                ->select(DB::raw("1 AS no"),'name','surname','company_name','email');

        $records= $p1->unionAll($p2);
        
        
        }
        
               
        elseif($this->user_types=="guests") 
             $records=NewsletterSubscription::where('newsletter_status','Yes')
             ->select(DB::raw("1 AS no"),DB::raw("NULL as name"),DB::raw("NULL as surname"),DB::raw("NULL as company_name"),'email');
        else
              $records=User::leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
                  ->where('newsletter_status','Yes')->where('users.status','<>','Deleted')
                  ->select(DB::raw("1 AS no"),'name','surname','company_name','email') ;
          

       $records=$records  ->orderBy('email','asc') 
        ->get()->each(function ($row, $inr) { $row->no = ++$inr; });


        return $records;  
       
    }

     public function headings() :array
    {
        return [" SL No","NAME","SURNAME","COMPANY NAME","EMAIL"];
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