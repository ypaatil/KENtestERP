<?php

namespace App\Http\Middleware;
use Config;
use Closure;
use Illuminate\Http\Request;
use DB;
use Session;
use DotenvEditor; 
use App\Models\Login;

class SetDatabaseForAllRouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */


    public function handle(Request $request, Closure $next)
    {

                $oldDbName = env("DB_DATABASE");


               if(Session::get('year_id') !="")
               {

                $tenant = DB::table('ken_year_databases')->where('year_id',Session::get('year_id'))->first();


                $db_host_name=$tenant->db_host_name;
                $db_name=$tenant->database_name;
                $db_user_name=$tenant->db_user_name;
                $db_password=$tenant->db_password;
                

               DB::disconnect(env("DB_CONNECTION"));

                } else{

               DB::disconnect(env("DB_CONNECTION"));
               
                 $request->session()->flash('error','Access Denied');
                 return redirect('login');

                }

        //DB::reconnect('mysql');
         

        Config::set('database.connections.' . env("DB_CONNECTION"), array(
                'driver'    => 'mysql', //or $request['driver'],
                'host'      => $db_host_name,
                'database'  => $db_name,
                'username'  => $db_user_name,
                'password'  => $db_password,
                'charset'   => 'utf8',
                'collation' => 'utf8_general_ci',
                'prefix'    => '',
        ));
     
      //  return redirect('UnderMaintance');
        //DB::enableQueryLog();
        $userInfo = DB::table('usermaster')->select('username')->where('username','=', Session::get('username'))->where('password','=', Session::get('password'))->first();  
      // dd(DB::getQueryLog());
      

        if($userInfo == "")
        {
            $request->session()->flash('error','Please enter valid login details');
            return redirect('login'); 
        }
        else
        {
            return $next($request);
        }
 
    }
}
