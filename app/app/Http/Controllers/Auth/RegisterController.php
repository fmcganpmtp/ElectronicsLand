<?php

namespace App\Http\Controllers\Auth;
use App\User;
use App\Admin;
use DB;
use App\Models\BuyerCompany;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderDetail;
use App\Models\Generalsetting;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:admin');
        $this->middleware('guest:user');
    }
    public function showAdminRegisterForm()
    {
        return view('auth.register', ['url' => 'admin']);
    }
    function isMobile() {
    if(isset($_SERVER['HTTP_USER_AGENT'])) {
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/(tablet|ipad|amazon|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($useragent))) {
        return true ;
    } ;

    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
            return true ;
        }
    }
    return 0 ;
}
    public function showUserRegisterForm()
    {
        if($this->isMobile()) { 
            return view('auth.register_mobile', ['url' => 'user']);
           
       } else {
         
           return view('auth.register', ['url' => 'user']);
        }
	
        
        
    }
    protected function createAdmin(Request $request)
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i', 'unique:admins'],
            'username' => ['required', 'string',  'max:255', 'unique:admins'],
            'phone' => ['required', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'image' =>  'mimes:jpeg,jpg,png,gif,webp',
        ]);
        $admin = Admin::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'username' => $request['username'],
            'phone' => $request['phone'],
            'about' => $request['about'],
            'profile_pic' => $request['profile_pic'],
            'password' => Hash::make($request['password']),
        ]);
        return redirect()->intended('login/admin');
    }
    protected function createUser(Request $request)
    {   
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i',  Rule::unique('users')->where(function ($query)  {return $query->where('status','<>','Deleted');})],
            'surname' => ['required', 'string',  'max:255'],
            'phone' => ['required','regex:/^(^([+]+)(\d+)?$)$/',Rule::unique('users')->where(function ($query)  {return $query->where('status','<>','Deleted');})],
			'company_name' => ['required', 'string',  'max:255'],
            //'full'      => 'required|regex:/^(^([+]+)(\d+)?$)$/ ',
            //'position' => ['required'],
            // 'agree' => ['required'],
            // 'agree_privacy_policy' => ['required'],
            //'password' => ['required', 'string', 'min:8', 'confirmed']
            'password' => [
                'required',
                'string',
                'min:8', 'confirmed',         // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ]);

     
        $input = [
            'name' => $request->get('name'),
            'surname' => $request->get('surname'),
            'position' => $request->get('position'),
            'email' => $request->get('email'),
            //'username' => $request->get('username'),
            'phone' => $request->get('phone'),
            'password' => Hash::make($request->get('password')),
        ];  
        if(request()->hasFile('image')) {
            $extension = request('image')->extension();
            $fileName = "user_pic".time().'.'.$extension;
            $destinationPath = public_path().'/uploads/userImages' ;
            request('image')->move($destinationPath,$fileName);
            $input['profile_pic'] = $fileName;
        } 
        $password = Hash::make($request->get('password'));
        $email = $request->get('email');
        $user_id = User::create( $input )->id; 
		$company['company_name'] = $request->get('company_name');
		$company['user_id'] = $user_id;
		BuyerCompany::create( $company ); 
        //mail to admin
            $name = $request->get('name');
            $surname = $request->get('surname');
            $email = $request->get('email');
            $phone = $request->get('phone');
			$company_name = $request->get('company_name');    
		Mail::send('emails.RegisterAdminMail', ['name' => $name,'email' => $email,'phone' => $phone,'surname' => $surname,'company_name' => $company_name], function($message) use($request){
			$company_email = Generalsetting::where('item','company_email')->first()->value;	   
			if(!empty($company_email))
				$message->to($company_email);
			else
				$message->to('info@fmcg.com');
			$message->subject('New User Registration - FMCG');
		}); 
        
        //Mail to newly registered user
        	Mail::send('emails.RegisterUserMail', ['name' => $name,'email' => $email], function($message) use($request){
			$message->to($request->get('email'));
			$message->subject('Thank you for registering with us - FMCG');
		}); 
		//random no creation for verification purpose
		$token =Str::random(60) . $user_id . date("Ymdss");
		
		$token=Hash::make($token);
		$token=str_replace('/','a', $token);
		$token=str_replace('%','a', $token);

        $token_exists = DB::table("users")->where("token_number", $token)->exists();
        while ($token_exists) {
            $token =Str::random(60) .$user_id. date("Ymdss");
            $token=Hash::make($token);
            $token=str_replace('/','a', $token);
        	$token=str_replace('%','a', $token);

            $token_exists = DB::table("users")->where("token_number", $token)->exists();
            if (!$token_exists)
                break;
        }
        DB::table("users")
            ->where("id", $user_id)
            ->update(["token_number" => $token]);
        //email verification
        Mail::send(
            "emails.KYCApprovalMailTemplate",
            ["token" => $token, "user_id" => $user_id],
            function ($message) use ($request) {
                $message->to($request->get('email'));
                $message->subject("Verify Email - FMCG");
            }
        );
        Auth::guard('user')->loginUsingId($user_id);

        return redirect(route('user-login'))->with('message','Email_not_verified:'.$email); 
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i',  'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {  request()->validate([
        'name' => ['required', 'string', 'max:255'],
        'surname' => ['required', 'string', 'max:255'],
        'position' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'regex:/(.+)@(.+)\.(.+)/i', 'unique:admins'],
        'phone' => ['required', 'max:13', 'unique:admins'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);
        $input = [
            'name' => $request->get('name'),
            'surname' => $request->get('surname'),
            'position' => $request->get('position'),
            'email' => $request->get('email'),
            'username' => $request->get('username'),
            'phone' => $request->get('phone'),
            'about' => $request->get('about'),
            'adminrole' => $request->get('adminrole'),
            'password' => Hash::make($request->get('password')),
        ];
        if(request()->hasFile('image')) {
            $extension = request('image')->extension();
            $fileName = "user_pic".time().'.'.$extension;
            $destinationPath = public_path().'/uploads/userImages' ;
            request('image')->move($destinationPath,$fileName);
            $input['profile_pic'] = $fileName;
        } 
        $password = Hash::make($request->get('password'));
        $email = $request->get('email');
        $user_id = User::create( $input )->id;
        $post = array('password' => $password, 'email' => $email);
        Auth::guard('user')->loginUsingId($user_id);
        return redirect()->route('home'); 
    }
}
