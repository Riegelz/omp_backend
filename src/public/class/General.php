<?php 

namespace src\omp;

class General
{

	public function set_response_text($arr_response){
		
		## Success ##
		$arr_response_text['200']="Success";
		## Bad Request ##
		$arr_response_text['400']="Bad Request";
		$arr_response_text['401']="Unauthorized";
		## Error ##
		$arr_response_text['500']="Internal Server Error";
		## Api Response ##
		$arr_response_text['600']="Duplicate Account";
		$arr_response_text['601']="Can Not Delete Account";
		$arr_response_text['602']="Duplicate Group";
		$arr_response_text['603']="Can Not Delete Group";
		$arr_response_text['604']="Duplicate Group Member";
		$arr_response_text['605']="Account Have Not Register";
		$arr_response_text['606']="Group Have Not Register";
		$arr_response_text['607']="Can Not Delete Group Member";
		$arr_response_text['608']="Duplicate Product";
		$arr_response_text['609']="Can Not Delete Product";
		$arr_response_text['610']="Product Have Not Register";
		$arr_response_text['611']="Duplicate Promotion";
		$arr_response_text['612']="Can Not Delete Promotion";
		$arr_response_text['613']="Logistics Have Not Register";
		$arr_response_text['614']="User Have Not In This Group";
		$arr_response_text['615']="Product Have Not Registered In Group";

		return $arr_response_text[$arr_response['response_code']];
		
	}

	public static function responseFormat($code = 200, $datas = '')
	{
		$responses = [
			'status' => $code, 
			'description' => self::set_response_text(['response_code' => $code]), 
		];
		if($code === 200 && !empty($datas) || $code === 400 && !empty($datas)) { 
			$responses['data'] = $datas;
		}

		return $responses;
	}
}