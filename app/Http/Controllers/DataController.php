<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DataController extends Controller
{
	/**
	 * @param Request $request
	 *
	 */
	function saveData(Request $request){
		$allData=$request->all();
		$allData['id']=time();
		$allData['added_date']=time();

	    $data=json_encode($allData);

		Storage::disk('local')->prepend('local.db', $data.",");
		return response()->json(['success'=>true]);
    }

	/**
	 *
	 */
	function getData(){
		$data=substr(Storage::disk('local')->get('local.db'), 0, -1);
		echo "[".$data."]";
	}


}
