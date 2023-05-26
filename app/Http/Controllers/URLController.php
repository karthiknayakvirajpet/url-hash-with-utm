<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use Illuminate\Support\Facades\Redirect;
use Log;
use Illuminate\Support\Facades\Validator;

class URLController extends Controller
{   
    /*****************************************************************
    *Generate URL Hash
    ******************************************************************/
    public function createURLHash(Request $request)
    {
        //Validate fields
        $validator = Validator::make($request->all(), [
            'url' => 'required',
            'utm' => 'required',
        ]);

        //Return code 400 if validation failed
        if($validator->fails()) 
        {
            return response()->json([
                'status' => 400, 
                'message' => 'Failed.',
                'data' => [],
                'error' => ['message' => $validator->errors()]
            ])->setStatusCode(400);
        }

        //Generate URL hash with timestamp
        $timestamp = now()->timestamp;
        $url_with_timestamp = $request->url . '?' . $timestamp;
        $hashed_url = hash('sha256', $url_with_timestamp);

        //Generating privacy-aware token
        $token = encrypt($hashed_url);

        //Save the details into Url Model
        Url::create([
            'url_hash' => $hashed_url,
            'original_url' => $request->url,
            'token' => $token,
            'utm_query' => json_encode($request->utm),
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Success.',
            'data' => ['url_hash' => $hashed_url, 'token' => $token,],
            'error' => [],
        ])->setStatusCode(200);;
    }


    /*****************************************************************
    *Count URL clicks
    ******************************************************************/
    public function URLClickCount(Request $request, $hashedURL)
    {
        //Validate fields
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        //Return code 400 if validation failed
        if($validator->fails()) 
        {
            return response()->json([
                'status' => 400, 
                'message' => 'Failed.',
                'data' => [],
                'error' => ['message' => $validator->errors()]
            ])->setStatusCode(400);
        }

        $url_data = Url::where('url_hash', $hashedURL)->where('token', $request->token)->first();

        if($url_data)
        {
            //Increment click count
            $url_data->increment('click_count');

            //Check if URL is used single time only
            if($url_data->is_used)
            {
                return response()->json([
                    'status' => 400, 
                    'message' => 'Failed.',
                    'data' => [],
                    'error' => ['message' => 'The URL has been used already.'],
                ])->setStatusCode(400);
            }

            //Update URL data as used
            $url_data->update(['is_used' => true]);
           
            return response()->json([
                'status' => 200, 
                'message' => 'Success.',
                'data' => [],
                'error' => [],
            ])->setStatusCode(200);
        }
        
        //Return code 400 if URL is invalid
        return response()->json([
            'status' => 400, 
            'message' => 'Failed.',
            'data' => [],
            'error' => ['message' => 'Invalid URL or Token.'],
        ])->setStatusCode(400);
    }
}
