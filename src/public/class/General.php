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