<?php

namespace App\Http\Middleware;
use App\Models\OrderDetail;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'admin') {
                    return redirect(RouteServiceProvider::ADMIN);
                }
                else {
                    if(Auth::guard('user')->user()) {
                        
                        $id = Auth::guard('user')->user()->id;
                        
                        
                        if(Auth::guard('user')->user()->email_status=="No")
                        { 

                    //         $token =Str::random(60) .$id. date("Ymdss");
                            
                    //         $token=Hash::make($token);
                    //         $token=str_replace('/','a', $token);
		                  //  $token=str_replace('%','a', $token);

                    //         $token_exists = DB::table("users")->where("token_number", $token)->exists();
                    //         while ($token_exists) {
                    //             $token =Str::random(60) .$id. date("Ymdss");
                    //             $token=Hash::make($token);
                    //             $token=str_replace('/','a', $token);
                    //     		$token=str_replace('%','a', $token);

                    //             $token_exists = DB::table("users")->where("token_number", $token)->exists();
                    //             if (!$token_exists)
                    //                 break;
                    //         }
                            
                    //         DB::table("users")
                    //             ->where("id", $id)
                    //             ->update(["token_number" => $token]);
                    //       $email_id=Auth::guard("user")->user()->email;
                           // email verification
                            // Mail::send(
                            //     "emails.KYCApprovalMailTemplate",
                            //     ["token" => $token],
                            //     function ($message)  {
                            //         $email = Auth::guard("user")->user()->email;
                            //         $message->to($email);
                            //         $message->subject("Verify Email - FMCG");
                            //     }
                            // );
                           // Session::flush();
                           // Auth::guard('user')->logout(); 
                           //return redirect(route('user-login'))->with('message','Email_not_verified:'.$email_id); 
                        } 

                        if(Auth::guard('user')->user()->status=='Deleted'||Auth::guard('user')->user()->status=='Blocked'||Auth::guard('user')->user()->status=='Rejected')
                        { 
                                    Session::flush();
                                    Auth::guard('user')->logout(); 
                                    return redirect(route('home'))->with('message','You are not allowed to login'); 
                        }    

                        if(Auth::guard('user')->user()->seller_type=='Co-Seller'){
                            $parent_id=Auth::guard('user')->user()->parent_id;
                            $package_data = DB::table('subscriptions')
                                            ->leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
                                            ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
                                            ->leftJoin('package_accounts', 'package_accounts.id', '=', 'order_details.accounts_id')
                                            ->where('subscriptions.user_id', '=',$parent_id)
                                            ->where('subscriptions.status','Active')
                                            ->select('subscriptions.id','subscriptions.expairy_date','packages.subscription_type')
                                            ->orderBy('subscriptions.id','DESC')->first();
                                                     
                            if(!empty($package_data)){  
                                //if( $package_data->subscription_type=='Extended' && $package_data->expairy_date>=date('Y-m-d'))
                                if( $package_data->expairy_date>=date('Y-m-d'))
                                {
 
                                }
                                else
                                { 
                                    Session::flush();
                                    Auth::guard('user')->logout(); 
                                    return redirect(route('home'))->with('message','Access Not Allowed,Please Check Your Package Validity'); 
                                }                          
                            }

                        }       


                    $user =OrderDetail::where('user_id',$id)->first(); 
                    if(!empty($user) || Auth::guard('user')->user()->seller_type=='Co-Seller')
                            return redirect(route('home')); 
                    else
                            return redirect(route('package.listing')); 
                    }
                    
                }
                
            }
        }

        return $next($request);
    }
}
