<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	public $fillable = ['id','user_id','date','completed','task_name','time_spent','estimated_time','time_spent_value','estimated_time_value'];
	
	protected $hidden = [];	
	
	// public static function getCityById($id)
	// {
	// 	return $city	= City::where('id',$id)->first();	
	// }
	// public static function insertGetId($data){
	// 	$data = array_merge(['lang_id'=>\Config::get('app.locale_prefix'),'status'=>1],$data);
	// 	self::create($data);
	// 	return \DB::getPdo()->lastInsertId();
	// }
}
