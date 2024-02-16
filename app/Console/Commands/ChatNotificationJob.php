<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChatNotification;
use App\User;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;
class ChatNotificationJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ChatNotification:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chat Notification Remainder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //get all chating members list
        $startdate = date('Y-m-d');
        $chat_data= Message::select('*')->whereDate('created_at',date('Y-m-d'))->groupBy('to_user')->groupBy('from_user')->get();
        //find each user latest message and which one is not respond
        foreach ($chat_data as $key => $value) {
            $chat_data1=    Message::select('*')
                ->where('from_user',$value->from_user)
                ->where('to_user',$value->to_user)
                ->latest('id')->first();
            if(!empty($chat_data1))
                $val1=$chat_data1->id;
            else
                $val1=0;    

            $chat_data2=    Message::select('*')
                ->where('from_user',$value->to_user)
                ->where('to_user',$value->from_user)
                ->latest('id')->first();
            if(!empty($chat_data2))
                $val2=$chat_data2->id;
            else
                $val2=0;
            //taking latest message data
            if($val1 > $val2)
            {
                $message_time=$chat_data1->created_at;
                $not_repond=$chat_data1->to_user;
                $message_id=$chat_data1->id;
                $message_from=$chat_data1->from_user;
            }
            else{
                $message_time=$chat_data2->created_at;
                $not_repond=$chat_data2->to_user;
                $message_id=$chat_data2->id;
                $message_from=$chat_data1->from_user;
            }
            //check notification already sent
            // $sent_count=ChatNotification::where('message_id',$message_id)
            //     ->where('user1',$not_repond)->where('user2',$message_from)
            //     ->count();
            
            $sent_count=ChatNotification::where('message_id',$message_id)
                        ->where(function ($query) use ($message_from,$not_repond) {
                                $query->where('user1',$not_repond)
                                ->where('user2',$message_from);
                    })
                    ->orwhere(function ($query) use ($message_from,$not_repond) {
                                $query->where('user1',$message_from)
                                ->where('user2',$not_repond);
                    })  ->count();
            //if chat sent time exceeded 60 minutes
            if((now()->diffInMinutes($message_time)>50) && $sent_count==0)
            {
                $user = User::find($not_repond);
                $messaged_user= User::find($message_from);
                //saving notication sent table
                 ChatNotification::create([
                    "message_id" => $message_id,
                    "user1" => $not_repond,
                    "user2" => $message_from,
                ]);
                  Mail::send('emails.ChatNotification', ['messaged_user' => $messaged_user->name,'user' => $user->name], function($message) use($user){
                        $message->to($user->email);
                        $message->subject('Chat Notification - FMCG');
                });
            }       
        }

    }
}
