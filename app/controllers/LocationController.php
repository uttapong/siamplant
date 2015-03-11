<?php

class LocationController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	
	
	public function index()
	{
		if(Input::has('lat')){
			$lat=Input::get('lat');
			$lng=Input::get('lng');

			$sql="select distinct gyms.name as gym_name,teams.name,(((acos(sin(({$lat}*pi()/180)) * 
            sin((gyms.lat*pi()/180))+cos(({$lat}*pi()/180)) * 
            cos((gyms.lat*pi()/180)) * cos((({$lng}- gyms.long)* 
            pi()/180))))*180/pi())*60*1.1515
	        ) as distance,gyms.lat,gyms.long,teams.id
	        FROM teams
	        left join opendays on opendays.team=teams.id
			left join gyms on opendays.gym=gyms.id 
			ORDER BY distance ASC limit 20;";

			$distance = DB::select($sql, array());
			//dd($distance);
			return json_encode($distance);
		}
		else return null;
	}

	public function getlocationbyday()
	{
		if(Input::has('lat')){
			$lat=Input::get('lat');
			$lng=Input::get('lng');
			$day=Input::get('day');
			if($day=='all')$day='%';

			$sql="select distinct gyms.name as gym_name,teams.name,(((acos(sin(({$lat}*pi()/180)) * 
            sin((gyms.lat*pi()/180))+cos(({$lat}*pi()/180)) * 
            cos((gyms.lat*pi()/180)) * cos((({$lng}- gyms.long)* 
            pi()/180))))*180/pi())*60*1.1515
	        ) as distance,gyms.lat,gyms.long,teams.id 
	        FROM teams
	        left join opendays on opendays.team=teams.id
			left join gyms on opendays.gym=gyms.id 
			where opendays.day like '{$day}'
			ORDER BY distance ASC limit 20;";

			//return $sql;

			$distance = DB::select($sql, array());
			//dd($distance);
			return json_encode($distance);
		}
		else return null;
	}

	public function getgym(){
		if(Input::has('lat')){
			$lat=Input::get('lat');
			$lng=Input::get('lng');

			$sql="select distinct gyms.id as gym_id, gyms.name as gym_name,(((acos(sin(({$lat}*pi()/180)) * 
            sin((gyms.lat*pi()/180))+cos(({$lat}*pi()/180)) * 
            cos((gyms.lat*pi()/180)) * cos((({$lng}- gyms.long)* 
            pi()/180))))*180/pi())*60*1.1515
	        ) as distance,gyms.lat,gyms.long 
	        FROM gyms 
			ORDER BY distance ASC limit 20;";

			$distance = DB::select($sql, array());
			//dd($distance);
			return json_encode($distance);
		}
		else return null;
	}
	
	


	
}
?>