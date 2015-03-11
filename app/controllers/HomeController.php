<?php

class HomeController extends BaseController {

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
	protected $layout = 'layouts.main';
	
	public function index()
	{
			//die(Hash::make('serenoss'));
			$data=array();
		/*$facebook = new Facebook(array(
		  'appId'  => '612396988819496',
		  'secret' => 'e2ab814a26b255a01ee2bc59fe8f3995',
		));


				// Get User ID
		$user = $facebook->getUser();
		
		  //$data['statusUrl'] = $facebook->getLoginStatusUrl();
		$data['loginUrl'] = $facebook->getLoginUrl();
		   //dd($loginUrl);


		//stat
		$dayofweek=date('N')+1; 
		if($dayofweek==8)$dayofweek=1;*/
		$data['items']=Item::orderBy('date_added', 'DESC')->whereIn('status',['new','relist'])->paginate(20);

		return View::make('home',$data);
	}

	
	



	
}
?>