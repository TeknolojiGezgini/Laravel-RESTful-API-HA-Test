<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CountryTimezoneCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $req, Closure $next)
    {
        $errors = ['errors'=>[]];
        //Setting header to json because laravel's validate function redirects to homepage instead of displaying error message
        $req->headers->set('Accept', 'application/json');
        $req->validate([
            'first_name' => 'required|max:64',
            'phone_number' => 'required|max:14',
        ]);
        //Country code validation
        $countries = 'https://api.hostaway.com/countries';
        $countries = json_decode(file_get_contents($countries),true);
        if(!array_key_exists($req->country_code, $countries['result'])){
            array_push($errors['errors'],'Country code is invalid');
        }
        //Timezone validation
        $timezones = 'https://api.hostaway.com/timezones';
        $timezones = json_decode(file_get_contents($timezones),true);
        if(!array_key_exists($req->time_zone, $timezones['result'])){
            array_push($errors['errors'],'Timezone is invalid');
        }
        if(count($errors['errors'])>0){
            echo json_encode($errors);
            exit();
        }else{
            return $next($req);
        }
    }
}
