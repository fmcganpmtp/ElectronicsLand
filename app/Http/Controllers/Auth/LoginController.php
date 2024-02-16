<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon; 
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use App\Mail\UserCreatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Session;
use DB;
use Hash;
use App\User;
use App\Models\LoginActivity;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:user')->except('logout');
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
    public function showAdminLoginForm()
    {
        //return view('auth.login', ['url' => 'admin']);
        return view('admin.login', ['url' => 'admin']);
    }
   
    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => ['required','email', 'regex:/(.+)@(.+)\.(.+)/i'],
            'password' => 'required'
        ]);

        $remember_me = $request->has('remember') ? true : false;

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {

            return redirect()->intended('/admin');
        }
        else
        {
                $user = \App\Admin::where('email', $request->email)->first();
           
                if (!empty($user) && !\Hash::check($request->password, $user->password)) 
                    return back()->withErrors(['password' => 'Wrong Password!'])->withInput(); 
                else
                    return back()->withErrors(['email' => 'Wrong User Name!'])->withInput();
        }
        
    }
    public function showUserLoginForm()
    { 
       if($this->isMobile()) { 
		    return view('auth.login_mobile', ['url' => 'user']);
           
       } else {
         
            return view('auth.login', ['url' => 'user']);
        }
	
       
        
    }

    public function userLogin(Request $request)
    {
        
        
        $this->validate($request, [
            'email'   => ['required','email', 'regex:/(.+)@(.+)\.(.+)/i'],
            'password' => 'required'
        ]);

        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password,'status'=> function ($query) {$query->where('status', '!=', 'Deleted');}], $request->get('remember'))) {                                                
                LoginActivity::create(['user_id'=>Auth::guard('user')->id()]);
    
        }
        $user = \App\User::where('email', $request->email)->latest('id')->first();
        if (!empty($user) && !\Hash::check($request->password, $user->password)) 
            return back()->withErrors(['password' => 'Invalid Password!']); 
        else
            return back()->withErrors(['email' => 'This E-Mail Address is not Registered!']);

  }

    public function showForgetPasswordForm() {
        
        if($this->isMobile()) { 
		   // return view('auth.login_mobile', ['url' => 'user']);
		    return view('auth.passwords.forgot-password_mobile');
           
       } else {
         
            return view('auth.passwords.forgot-password');
        }
        
    }

    public function submitForgetPasswordForm(Request $request)
      {
          $token = Str::random(64); 
          $request->validate([
              'email' => ['required','email','exists:users', 'regex:/(.+)@(.+)\.(.+)/i'],
          ]);
  
         
          
          $query =  DB::table('password_resets')->insert([
              'email' => $request->email, 
              'token' => $token, 
              'created_at' => Carbon::now()
            ]);
           
  
          Mail::send('emails.forgetPassword', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset Password - FMCG');
          });

          //Mail::to($request->email)->send(new UserCreatedMail($request));
  
          return back()->with('message', 'We have e-mailed your password reset link!');
      }
      public function showResetPasswordForm($token) {
          if($this->isMobile()) { 
		    return view('auth.passwords.forgetPasswordLink_mobile', ['token' => $token]);
           
       } else {
         
            return view('auth.passwords.forgetPasswordLink', ['token' => $token]);
        }
        
        
     }
     public function submitResetPasswordForm(Request $request)
      {
          $request->validate([
              'email' =>[ 'required','email', 'regex:/(.+)@(.+)\.(.+)/i','exists:users'],
              'password' => [
                'required','string','min:8', 'confirmed', 'regex:/[a-z]/',  'regex:/[A-Z]/', 'regex:/[0-9]/','regex:/[@$!%*#?&]/'],
              'password_confirmation' => 'required'
          ]);
  
          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }
  
          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);
 
          DB::table('password_resets')->where(['email'=> $request->email])->delete();
  
          return redirect()->route('user-login')->with('message', 'Your password has been changed!');
      }
    //   public function logout() {
    //     Session()->flush();
    //      return redirect()->route('admin.login')->with('message','Login is Invalid');
    //    }
       
       public function logout( Request $request )
{
    if(Auth::guard('admin')->check()) // this means that the admin was logged in.
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login.form');
    }

    $this->guard()->logout();
    $request->session()->invalidate();

    return $this->loggedOut($request) ?: redirect('/');
}
}
