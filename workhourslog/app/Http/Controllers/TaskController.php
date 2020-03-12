<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Validator;
use App\User;
use App\Task;
use App\UserLeave;
use \DB;
use Auth;
use Response;
use Carbon\Carbon;

class TaskController extends Controller
{
	public function edit($date_type,Request $request)
	{
		try {
				if($request->isMethod('post'))
				{
					$header_token = $request->bearerToken();
					$data = User::where('token','=',$header_token)->first();
					if(!$data)
					{
						return $this->apiResponse(['message'=>'token is not valid'],true);
					}

					if($request->coming_tomorrow['status'] == false)
					{
						// $end_date = $request->comming_tomarrow['till_date'];
						$end_date = Carbon::parse($request->coming_tomarrow['till_date']);
						$start_date = Carbon::now()->format('Y-m-d');

						$leave_days = $end_date->diffInDays($start_date);

						UserLeave::create(['user_id'=>$data['id'],
											'leave_days'=>$leave_days,
											'start_date'=>$start_date,
											'end_date'=>$end_date
					 					]);
					}

					if($request->new_task['for_tomorrow'] == true)
					{
						$data_size = sizeof($request->new_task['data']);
						$i =0;
						
						if($data_size>=1)
						while($i<$data_size)
						{

							$task = Task::where('id','=',$request->new_task['data'][$i]['id'])->first();
							//$task->deleted = $request->new_task['data'][$i]['is_deleted'];
							$task->task_name = $request->new_task['data'][$i]['task_name'];
							$task->date = $request->new_task['completion_date'];
							$task->estimated_time = $request->new_task['data'][$i]['est_time'];
							$task->time_spent = $request->new_task['data'][$i]['time_spent'];
							$task->time_spent_value = $request->new_task['data'][$i]['time_spent_value'];
							$task->estimated_time_value = $request->new_task['data'][$i]['est_time_value'];
							$task->save();
							$i = $i + 1;
						}
						return $this->apiResponse(['message'=>'successfully task edited']);
					}
					else {
							return $this->apiResponse(['error'=>'task not edited'],true);
						}	
				}
				else if($request->isMethod('get'))
				{
						$header_token = $request->bearerToken();
						$model = User::where('token','=',$header_token)->first();
						if(!$model)
						{
							return $this->apiResponse(['message'=>'token is not valid'],true);
						}

				    $day = $date_type;
				    $day_date = $day;
				    $data;
				    // print_r($day_date);
				    // die;
				    if($day_date == 'today')
				    {
				    	$day_date = Carbon::now()->format('Y-m-d');
				    	$data = Task::where('user_id','=',$model['id'])
				    			->where('date','=',$day_date)
				    			->orderBy('date','ASC')
				    			->get();

				    }
				    else if($day_date == 'tomarrow')
				    {
				    	$day_date = Carbon::now()->addDays(1)->format('Y-m-d');
				    	
				    	//$day_date = Carbon::$day_date->addDays(5);
				    	// print_r($day_date);
				    	// die;
				    	$data = Task::where('user_id','=',$model['id'])
				    			->where('date','=',$day_date)
				    			->orderBy('date','ASC')
				    			->get();

				    }
				    else if($day_date == 'upcoming')
				    {
				    	$day_date = Carbon::now()->addDays(1)->format('Y-m-d');
				    	$data = Task::where('user_id','=',$model['id'])
				    			->where('date','>',$day_date)
				    			->orderBy('date','ASC')
				    			->get();		    	
				    }
				    else
				    {
				    	$day_date = Carbon::parse($day_date)->format('Y-m-d');
				    	$data = Task::where('user_id','=',$model['id'])
				    			->where('date','=',$day_date)
				    			->orderBy('date','ASC')
				    			->get();
				    }	
				    return $this->apiResponse(['message'=>'successfully dashboard','data'=>$data]);
				}				
		} catch(\Exception $e) {
			return $this->apiResponse(['error'=>'Invalid data'],true);
		}
				
	}

	public function getTask($date_type, Request $request)
	{
		try {

		    if($request->isMethod('post'))
            {
				$header_token = $request->bearerToken();
				
				$model = User::where('token','=',$header_token)->first();

			    if( !$model ) return $this->apiResponse(['error'=>'Invalid token'],true);
			    $day = $request->day;
			    $day_date = $day;
			    $data;
			    if($day_date == 'today')
			    {
			    	$day_date = Carbon::now()->format('Y-m-d');
			    	$data = Task::where('user_id','=',$model['id'])
			    			->where('date','=',$day_date)
			    			->orderBy('date','ASC')
			    			->get();
			    }
			    else if($day_date == 'tomarrow')
			    {
			    	$day_date = Carbon::now()->addDays(1)->format('Y-m-d');
			    	
			    	//$day_date = Carbon::$day_date->addDays(5);
			    	// print_r($day_date);
			    	// die;
			    	$data = Task::where('user_id','=',$model['id'])
			    			->where('date','=',$day_date)
			    			->orderBy('date','ASC')
			    			->get();

			    }
			    else if($day_date == 'upcoming')
			    {
			    	$day_date = Carbon::now()->addDays(1)->format('Y-m-d');
			    	$data = Task::where('user_id','=',$model['id'])
			    			->where('date','>',$day_date)
			    			->orderBy('date','ASC')
			    			->get();		    	
			    }
			    else
			    {
			    	$day_date = Carbon::parse($day_date)->format('Y-m-d');
			    	$data = Task::where('user_id','=',$model['id'])
			    			->where('date','=',$day_date)
			    			->orderBy('date','ASC')
			    			->get();
			    }	
			    return $this->apiResponse(['message'=>'successfully dashboard','data'=>$data]);
			}
		 
		    else if($request->isMethod('get'))
			    {

			    	// echo "good morning";
			    	// die;
				$header_token = $request->bearerToken();
				// print_r($header_token);
				// die;
				$model = User::where('token','=',$header_token)->first();

			    //if( !$model ) return $this->apiResponse(['error'=>'Invalid token'],true);
			    $day = $date_type;
			    $day_date = $day;
			    $data;
			    if($day_date == 'today')
			    {
			    	$day_date = Carbon::now()->format('Y-m-d');
			    	$data = Task::where('user_id','=',$model['id'])
			    			->where('date','=',$day_date)
			    			->orderBy('date','ASC')
			    			->get();
			    }
			    else if($day_date == 'tomarrow')
			    {
			    	$day_date = Carbon::now()->addDays(1)->format('Y-m-d');
			    	
			    	//$day_date = Carbon::$day_date->addDays(5);
			    	// print_r($day_date);
			    	// die;
			    	$data = Task::where('user_id','=',$model['id'])
			    			->where('date','=',$day_date)
			    			->orderBy('date','ASC')
			    			->get();

			    }
			    else if($day_date == 'upcoming')
			    {
			    	$day_date = Carbon::now()->addDays(1)->format('Y-m-d');
			    	$data = Task::where('user_id','=',$model['id'])
			    			->where('date','>',$day_date)
			    			->orderBy('date','ASC')
			    			->get();		    	
			    }
			    else
			    {
			    	$day_date = Carbon::parse($day_date)->format('Y-m-d');
			    	$data = Task::where('user_id','=',$model['id'])
			    			->where('date','=',$day_date)
			    			->orderBy('date','ASC')
			    			->get();
			    }	
			    return $this->apiResponse(['message'=>'successfully dashboard','data'=>$data]);
		     }
		    
		} catch(\Exception $e) {
			return $this->apiResponse(['error'=>'Invalid token'],true);
		}
	}


	public function add(Request $request)
	{
		try {

			// print_r($request->all());
			// die;
    //         $user1 = User::Create(['email'=>$request->email,'password' => bcrypt($request->password),'name' => $request->name, 'mobile_number' =>$request->mobile_number
				// ]);
			//$task = Task::where('id','=','1')->first();
			// print_r(json_encode($task));
			// die;
			// print_r($request->task_name);
			// die;
			// $task = Task::Create(['task_name'=>$request->task_name,'user_id'=>$request->user_id,
			// 					'date'=>$request->date,'completed'=>$request->completed,
			// 					'time_spent'=>$request->date,'estimated_time'=>$request->estimated_time]);


			//$header = $request->header('token');
			$header_token = $request->bearerToken();
			// print_r($header_token);
			// die;


			$data = User::where('token','=',$header_token)->first();
			// print_r($data);
			// die;
			if(!$data)
			{
				return $this->apiResponse(['message'=>'token is not valid'],true);
			}
			//$input = $request->all();

			if($request->coming_tomarrow['status'] == false)
			{
				// $end_date = $request->comming_tomarrow['till_date'];
				$end_date = Carbon::parse($request->coming_tomarrow['till_date']);
				$start_date = Carbon::now()->format('Y-m-d');

				$leave_days = $end_date->diffInDays($start_date);

				UserLeave::create(['user_id'=>$data['id'],
									'leave_days'=>$leave_days,
									'start_date'=>$start_date,
									'end_date'=>$end_date
			 					]);
			}

			if($request->new_task['for_tomorrow'] == true)
			{
				// echo "good morning";
				// die;
				$data_size = sizeof($request->new_task['data']);
				$i =0;
				// print_r($request->new_task['data'][0]['task_name']);
				// 	die;
				if($data_size>=1)
				while($i<$data_size)
				{
					// print_r($request->new_task['data'][$i]['est_time_value']);
					// die;
					if($request->new_task['data'][$i]['isCompleted'] == false)
					{
						$completed = 0;
					}
					else
					{
						$completed = 1;						
					}


					  Task::create(['task_name'=>$request->new_task['data'][$i]['task_name'],
								    'user_id'=>$data['id'],
									'date'=>$request->new_task['completion_date'],
									'completed'=>$completed,
									'time_spent'=>$request->new_task['data'][$i]['time_spent'],
									'time_spent_value'=>$request->new_task['data'][$i]['time_spent_value'],
									'estimated_time'=>$request->new_task['data'][$i]['est_time'],
									'estimated_time_value'=>$request->new_task['data'][$i]['est_time_value']
								   ]);
					//   echo "good morning";
					// die;
					  $i = $i + 1;
				}
				return $this->apiResponse(['message'=>'successfully task added']);
			}
			else {
					return $this->apiResponse(['message'=>'task not add']);
				}	


   //  		Task::create($input);
			// $id = \DB::getPdo()->lastInsertId();
			// $task_id = Task::findOrFail($id);
			// if($task_id)
			//return $this->apiResponse(['message'=>'fine']);

		} catch(\Exception $e) {
			return $this->apiResponse(['error'=>$e->getMessage()],true);
		}
	}
}
