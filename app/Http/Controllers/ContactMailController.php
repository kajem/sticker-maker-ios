<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactUsMail;
use App\ContactMail;
use App\StaticValue;
use Illuminate\Support\Facades\Redis;

class ContactMailController extends Controller
{
    public function sendMail(Request $request){
        $redis_key = 'static_value_contact_us_email';
        if(Redis::exists($redis_key)){
            $to_email = Redis::get($redis_key);
        }else{
            $contact_us_email = StaticValue::select('value')->where('name', 'contact_us_mail')->first();
            $to_email = !empty($contact_us_email->value) ? $contact_us_email->value : 'support@braincraftapps.com';
            $redis_ttl = 31536000; //Will expire after one year
            Redis::setEx($redis_key, $redis_ttl, $to_email); //Writing to Redis
        }

        $data = $request->all();

        Mail::to($to_email)->send(new ContactUsMail($data));

        $data['to_email'] = $to_email;
        $result = ContactMail::create($data);
        if($request){
            $data = [
                'status' => 200,
                'message' => 'Your message has been submitted successfully. We will contact you soon.',
            ];

            return response()->json($data);
        }else{
            $data = [
                'status' => 400,
                'message' => 'Failed! Something went wrong!'
            ];
            return response()->json($data);
        }
    }
}
