<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Validator;
use App\User;
use \DB;
use Auth;
use Response;
use Mail;

class LoginController extends Controller
{
	// public function login2()
	// {
	// 	echo "good morning";
	// 	return;
	// }

	public function resetPassword(Request $request)
	{
		try {

			//$header = $request->header('token');
			$header_token = $request->bearerToken();
			$model = User::where('token','=',$header_token)->first();

		    if( !$model ) return $this->apiResponse(['error'=>'Invalid token'],true);
		    //try logging in the user
		    $validator = Validator::make(
            	$request->all(),[
                	'password' => 'required'
            		]
        		);
		    if($validator->fails()) {
		            return $this->apiResponse(['error' => $validator->messages()->first()], true);
		        }
		    $model->password = bcrypt($request->password);
		    $model->token = md5(uniqid($model->email, true));
		    $model->save();   
		    return $this->apiResponse(['message'=>'successfull password resset']);
		} catch(\Exception $e) {
			return $this->apiResponse(['error'=>'Invalid login credentials'],true);
		}
	}

	public function forgotPasswordFunction(Request $request){
		try{
			$inputs = $request->all();
			  $validator = Validator::make(
            	$request->all(),[
                	'email' => 'required|email'
            		]
        		);
		       	if($validator->fails()) {
		            return $this->apiResponse(['error' => $validator->messages()->first()], true);
		        }
			if(!isset($inputs['email'])  || empty($inputs['email']))
			{
				return $this->apiResponse(['message' => 'email  not be null'], true);
			}
			else
			{
				$data = User::where('email','=',$inputs['email'])->first();
				if( !$data )
			 	{
			 		return $this->apiResponse(['message' => 'Invalid user_id  credentials'], true);
			 	}
			 	else
			 	{
				   // $data1 = $data->toArray();
				   // $data2 =[];
				   // $data2['user'] = $data1;
				    // Mail::send('emails.hello', $data2, function($message) use ($data2) {
				    //     $message->to('vijay8101995@gmail.com');
				    //     $message->subject('E-Mail Example');
				    // });

				   // $user['title'] = "This is Test Mail Tuts Make";
				   // $user['email'] = $inputs['email'];
				   // $user['name'] = 'vijay';
				   // $user['token'] = $data['token'];

				   $data1 = $data->toArray();
				   $data2 =[];
				   $data2['user'] = $data1;
 
			        // Mail::send('emails.hello', $value, function($message) {
			 
			        //     $message->to('maneeshchauhan79@gmail.com', 'Receiver Name')
			 
			        //             ->subject('Tuts Make Mail');
			        // });
			        Mail::send('emails.password', $data2, function($message) use ($data2) {
			         $message->to($data2['user']['email']);
			         $message->subject('E-Mail Example');
			     
			      });
			 	//return $this->apiResponse(['success' => 'true' ,'message' => 'successfull send the link on this email']);
			        if (Mail::failures()) {
			           return $this->apiResponse(['error'=>'mail not send'],true);
			         }else{
			           return $this->apiResponse(['success' => 'true' ,'message' => 'successfull send the link on this email']);
			         }




				    // Mail::send('emails.password', $data2, function($message) use ($data2) {
				    //     $message->to($data2['user']['email']);
				    //     $message->subject('E-Mail Example');
				    // });
	   				//return $this->apiResponse(['success' => 'true' ,'message' => 'successfull send the link on this email']);
			 	}
			}
		}
		catch(\Exception $e) {
			return $this->apiResponse(['error' => $e->getMessage()], true);
		}
	}

	public function login(Request $request)
	{
		try {
		    $validator = Validator::make(
		        $request->all(),[
		            'email' => 'email',
		            'password' => 'required'
		        ]
		    );
			
		    if($validator->fails()) return $this->apiResponse(['error'=>'validation is not fullfill'],true);
		    $model = User::where('email', $request->email)->first();

		    if( !$model ) return $this->apiResponse(['error'=>'Invalid login credentials'],true);
		    //try logging in the user
		    if(Auth::attempt($request->only(['email', 'password']), $request->input('remember'))) {
		      	$user = Auth::user();
		      	return $this->apiResponse(['message' => 'Successfully login', 'data' => $user]);
		    }
		    return $this->apiResponse(['error'=>'Invalid login credentials'],true);
		} catch(\Exception $e) {
			return $this->apiResponse(['error'=>'Invalid login credentials'],true);
		}
	}

	public function register(Request $request) 
	{
		try
		{
			$validator = Validator::make(
		        $request->all(),[
		            'email' => 'required',
		            'password' => 'required',
		            'name' => 'required',
		            'mobile_number'=>'required'
		        ]
		    );
			if($validator->fails()) $this->apiResponse(['error'=>'validation is not fullfill'],true);
            
            $user1 = User::create(['email'=>$request->email,'password' => bcrypt($request->password),'name' => $request->name, 'mobile_number' =>$request->mobile_number
        					]);

            $id = \DB::getPdo()->lastInsertId();
			$user = User::findOrFail($id);
            if($user)
            {
            	$token = md5(uniqid($request->email, true));
            	$user->token = $token;
            	$user->save();
        		return $this->apiResponse(['message' => 'Successfully user is Register', 'data' => $user]);
            }
        	else
        	{
        		return $this->apiResponse(['error'=>'data not Register'],true);
        	}
        } catch(\Exception $e) {
            return $this->apiResponse(['error'=>'Invalid login credentials'],true);
        }
	}


}
