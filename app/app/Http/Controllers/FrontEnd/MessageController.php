<?php

namespace App\Http\Controllers\FrontEnd;
use App\Http\Controllers\Controller;
 use App\User;
use App\Models\Message;
use App\Models\Mynetworks;
use App\Models\Chat_contact_delete;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Http\Controllers\FrontEnd\PublicMiddlewareController;

class MessageController extends Controller
{

    protected $PublicMiddlewareController;
    public function __construct(PublicMiddlewareController $PublicMiddlewareController)
    {
        $this->PublicMiddlewareController = $PublicMiddlewareController;
    }
    
    public function getMyContacts(Request $request){
        
        if (!Auth::guard("user")->check()) 
            return response()->json([]);
        $active_sellers=$this->PublicMiddlewareController->getexpireduserslist(); 
        $user_id =  Auth::guard('user')->user()->id;
        //$contacts = Mynetworks::select('users.id','users.name','users.profile_pic','mynetworks.mynetwork_id')->join('users', 'users.id', 'mynetworks.mynetwork_id')->where('mynetworks.user_id',$user_id)->get();
        $deleted_id=Chat_contact_delete::where('seller_id',$user_id)->pluck('deleted_id')->all();
        
        $a = Message::select('messages.to_user as mynetwork_id')->where('from_user', $user_id)
        ->whereNotIn('to_user', $deleted_id)
        ->whereIn('to_user',$active_sellers)
        ->groupBy('to_user')->pluck('mynetwork_id')->toArray();

        $b = Message::select('messages.from_user as mynetwork_id')->where('to_user', $user_id)
        ->whereNotIn('from_user', $deleted_id)
        ->whereIn('from_user',$active_sellers)
        ->groupBy('from_user')->pluck('mynetwork_id')->toArray();
        if(is_array($a)&&is_array($b))
        $unioncontacts = array_merge($a,$b);
        else if(is_array($a))
        $unioncontacts = $a;
        else if(is_array($b))
        $unioncontacts =  $b;
        else
        $unioncontacts =[];
        $unioncontacts = array_unique($unioncontacts);
		$return_array=array();
		
		        
		
		
		
		
		$chat_data = array();
		$contacts = User::select('users.id','users.name','users.user_status','users.profile_pic','buyer_companies.company_image','buyer_companies.company_name','users.seller_type','users.parent_id')
		->leftJoin('buyer_companies', 'users.id', '=', 'buyer_companies.user_id')->whereIn('users.id',$unioncontacts)
        ->when($request->get('chat_cnt_search_name')!='', function ($query) use ($request) {
                $query->where(DB::raw('UPPER(CONCAT(users.name,COALESCE(buyer_companies.company_name,"")))'), 'LIKE','%'.strtoupper($request->get('chat_cnt_search_name')).'%');
            })
        ->get();
        
        
        
        
        
		if (!$contacts->isEmpty()) {
			foreach($contacts as $key=> $row){ 

                    if($row->seller_type=="Co-Seller")
                         $u_id=$row->parent_id;
                    else
                         $u_id=$row->id;
                    
                    $user_company = User::find($u_id);

                    if(!empty($user_company->BuyerCompany->company_image))
                    {  
                        $image=$user_company->BuyerCompany->company_image;
                        if($image!='')          
                            $contacts[$key]['company_image']  = $user_company->BuyerCompany->company_image;
                    }
                    

				$unreadCount = Message::where('from_user', $row->id)->where('to_user',$user_id)->where('message_status', 'unread')->count();
				$latestMessage = Message::where(function ($query) use ($user_id, $row) {
								$query->where('from_user', $user_id)
                                ->where('to_user',$row->id);
                        })
                        ->orWhere(function ($query) use ($user_id, $row) {
                            $query->where('from_user', $row->id)
                                ->where('to_user', $user_id);
                        })->latest()
                        ->first();
                if(!empty($latestMessage))
                    $msgid=$latestMessage->id;
                else
                    $msgid="";  

                    
                    

                
                          
				$chat_data[$key] = array('contact'=>$row,'latestMessage'=>$latestMessage,'unreadcount'=>$unreadCount,'msgid'=>$msgid);
				
			}
		}
         $sorted_data = collect($chat_data)->sortBy('msgid')->reverse()->toArray();
         $newsortedarray=[];
         foreach ($sorted_data as $key => $value) 
             $newsortedarray[]=$value;         
		 //$return_array = array('ajax_status' => true, 'chat_data' => $newsortedarray);
        return response()->json($newsortedarray);
    }
     public function getMyMessages($id = null){
         
       if (!Auth::guard("user")->check()) 
            return response()->json([]);
       $user_id =  Auth::guard('user')->user()->id;
         Message::where('from_user', $id)->where('to_user', $user_id)->update([ 'message_status' => 'read', ]);
    	$chat_data = Message::select('id','from_user','to_user','message_type','message','file','message_status',DB::raw('DATE(created_at) as Date'),
       DB::raw('DATE_FORMAT(TIME(created_at) ,"%H:%i")as Time'))->where(function ($query) use ($user_id, $id) {
							$query->where('from_user', $user_id)
                            ->where('to_user', $id);
                    })
                    ->orWhere(function ($query) use ($user_id, $id) {
                        $query->where('from_user', $id)
                            ->where('to_user', $user_id);
                    })->orderBy('id', 'DESC')->paginate(50);;
        $userdetails = User::select('name','profile_pic','seller_type','id','parent_id',DB::raw("null as company_image"))->where('id', $id)->first();

        if($userdetails->seller_type=="Co-Seller")
                $u_id=$userdetails->parent_id;
         else
                $u_id=$userdetails->id;
                    
        $user_company = User::find($u_id);

        if(!empty($user_company->BuyerCompany->company_image))
        {  
           $image=$user_company->BuyerCompany->company_image;
           if($image!='')          
                $userdetails->company_image  = $user_company->BuyerCompany->company_image;
        }

		$return_array = array('ajax_status' => true, 'chat_data' => $chat_data,'userdetails'=>$userdetails);
        return response()->json($return_array);
    }
    public function sentMessages(Request $request){
        if (!Auth::guard("user")->check()) 
            return response()->json([]);
        $user_id =  Auth::guard('user')->user()->id;
        $user=User::find($user_id);
        $company_name ="";
        if(!empty($user->BuyerCompany->company_name))
        $company_name = $user->BuyerCompany->company_name;
        Chat_contact_delete::where('seller_id',$user_id)
                                ->where('deleted_id',$request->selected_id)->delete();
        $file = $request->file('file');
        $insert_id=  0;
        if($file){
            $file = $request->file('file');
            $fileName = time().'.'.$request->file->extension();
            $request->file->move(public_path('/assets/uploads/chat/'), $fileName);
            $insert_id= Message::create([
            'from_user' => $user_id,
            'to_user' => $request->selected_id,
            'message_type' => $request->message_type,
            'company_name' => $company_name,
            'message' => $request->message,
            'file'=>$fileName,
            'message_status' => 'unread', 
            ])->id;
        }else{
            $insert_id= Message::create([
            'from_user' => $user_id,
            'to_user' => $request->selected_id,
            'message_type' => $request->message_type,
            'message' => $request->message,
            'company_name' => $company_name,
            'message_status' => 'unread', 
        ])->id;
        } 
        $chat_data = Message::find( $insert_id);
		$return_array = array('ajax_status' => true,'chat_data'=>$chat_data);
        return response()->json($return_array);
    }
    public function checkNewMessage($senter_id =null,$last_message =null){
        
        if (!Auth::guard("user")->check()) 
            return response()->json([]);
         
        $user_id =  Auth::guard('user')->user()->id;
    	$chat_data = Message::where(function ($query) use ($user_id, $senter_id,$last_message) {
							$query->where('from_user', $user_id)
                            ->where('to_user', $senter_id)->where('id','>',$last_message);
                    })
                    ->orWhere(function ($query) use ($user_id, $senter_id,$last_message) {
                        $query->where('from_user', $senter_id)
                            ->where('to_user', $user_id)->where('id','>',$last_message);
                    })->orderBy('id', 'ASC')->get();

        $userdetails = User::select('id','name','profile_pic','seller_type','parent_id',DB::raw("null as company_image"))->where('id', $senter_id)->first();

         if($userdetails->seller_type=="Co-Seller")
                $u_id=$userdetails->parent_id;
         else
                $u_id=$userdetails->id;
                    
        $user_company = User::find($u_id);

        if(!empty($user_company->BuyerCompany->company_image))
        {
            $image=$user_company->BuyerCompany->company_image;
            if($image!='')          
                $userdetails->company_image  = $user_company->BuyerCompany->company_image;
        }


		$return_array = array('ajax_status' => true, 'chat_data' => $chat_data,'userdetails'=>$userdetails);
        return response()->json($return_array);
    } 
    public function checkNotifications(Request $request){
        
        if (!Auth::guard("user")->check()) 
            return response()->json([]);
        $user_id =  Auth::guard('user')->user()->id;
        $wishlist_count = Wishlist::where('user_id','=',$user_id)->where('wishlist','=','Yes')->count();
        
        
        $active_sellers=$this->PublicMiddlewareController->getexpireduserslist(); 
        $deleted_id=Chat_contact_delete::where('seller_id',$user_id)->pluck('deleted_id')->all();      
               
        $chat_count = Message::where('to_user',$user_id)->where('message_status', 'unread')
                            ->whereNotIn('from_user', $deleted_id)
                            ->whereIn('from_user',$active_sellers)->count();
        
        $return_array = array('ajax_status' => true,'wishlist_count'=>$wishlist_count,'chat_count'=>$chat_count);
        return response()->json($return_array);
    } 
    
    public function hide_chat_contact(Request $request){
        
        if (!Auth::guard("user")->check()) 
            return response()->json([]);

        $login_id =  Auth::guard('user')->user()->id;
        $deleted_id=$request->input('deleted_id');
        $val=Chat_contact_delete::create(['seller_id'=>$login_id,'deleted_id'=>$deleted_id]);
        return json_encode($val);
       
    } 

}