<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhoneBook;
use App\Models\Error;
use App\Models\ApiCall;

class Crud extends Controller
{
    public static function create(Request $req)
    {
            //Inserting to database with Model PhoneBook
            $phonebook = new PhoneBook;
            $phonebook->first_name = $req->first_name;
            $phonebook->last_name = $req->last_name;
            $phonebook->phone_number = $req->phone_number;
            $phonebook->country_code = $req->country_code;
            $phonebook->time_zone = $req->time_zone;
            $phonebook->created_at = now();
            if($phonebook->save()){
                //Logging succeed api call
                $apicall = new ApiCall;
                $apicall->call_value = 'Create: '.json_encode($phonebook);
                $apicall->client_ip = $req->ip();
                $apicall->save();
                return '{"status":"success"}';
            }else{
                //Logging error
                $error = new Error;
                $error->error = 'Create: '.json_encode($phonebook);
                $error->client_ip = $req->ip();
                $error->save();
                return '{"status":"fail"}';
            }
    }

    public static function update(Request $req)
    {
            //Inserting to database with Model PhoneBook
            $phonebook = PhoneBook::find($req->id);
            $phonebook->first_name = $req->first_name;
            $phonebook->last_name = $req->last_name;
            $phonebook->phone_number = $req->phone_number;
            $phonebook->country_code = $req->country_code;
            $phonebook->time_zone = $req->time_zone;
            $phonebook->updated_at = now();
            if($phonebook->save()){
                //Logging succeed api call
                $apicall = new ApiCall;
                $apicall->call_value = 'Update: '.json_encode($phonebook);
                $apicall->client_ip = $req->ip();
                $apicall->save();
                return '{"status":"success"}';
            }else{
                //Logging error
                $error = new Error;
                $error->error = 'Update: '.json_encode($phonebook);
                $error->client_ip = $req->ip();
                $error->save();
                return '{"status":"fail"}';
            }
    }

    public static function delete(Request $req){
        $phonebook = PhoneBook::find($req->id);
        if($phonebook->delete()){
            //Logging succeed api call
            $apicall = new ApiCall;
            $apicall->call_value = 'Delete: '.json_encode($phonebook);
            $apicall->client_ip = $req->ip();
            $apicall->save();
            return '{"status":"success"}';
        }else{
            //Logging error
            $error = new Error;
            $error->error = 'Delete: '.json_encode($phonebook);
            $error->client_ip = $req->ip();
            $error->save();
            return '{"status":"fail"}';
        }
    }

    public static function list(Request $req){

        switch ($req->list_type) {
            case "id":
                $phonebook = PhoneBook::find($req->id);
                break;
            case "all":
                $phonebook = PhoneBook::all();
                break;
            case "pagination":
                $phonebook = PhoneBook::all()->skip($req->offset)->take($req->item_count);
                break;
            case "search":
                if(isset($req->offset) AND isset($req->item_count)){
                    $phonebook = PhoneBook::where('first_name', 'like', "%$req->name%")
                    ->orWhere('last_name', 'like', "%$req->name%")
                    ->skip($req->offset)
                    ->take($req->item_count)
                    ->get();
                }else{
                    $phonebook = PhoneBook::where('first_name', 'like', "%$req->name%")
                    ->orWhere('last_name', 'like', "%$req->name%")
                    ->get();
                }
                break;
        }
        return ['list'=>$phonebook];
    }
}
