<?php

namespace src\omp\model;

use src\omp\General as General;

class Other extends General
{
    protected $db_con;

	public function __construct() {
        ## Database ##
		$username = getenv('DB_USER');
		$password = getenv('DB_PASS');
		$hostname = getenv('DB_HOST'); 
		$database = getenv('DB_DBNAME'); 
		$connection = new \mysqli($hostname, $username, $password, $database);
		$connection->set_charset("utf8");
        $this->db_con = $connection;
        ## Database Field ##
        define("STATUS", "status");
        define("OMPID", "omp_id");
        define("GROUPNAME", "group_name");
        define("GROUPDESCRIPT", "group_description");
        define("ID", "id");
        define("GROUPID", "group_id");
        define("ACCOUNTID", "account_id");
        define("GROUPROLE", "group_role");
        ## String Name ##
        define("STRGROUPS", "groups");
        define("STRLOGISTICS", "logistics");
        define("STRPAYMENTS", "payments");
        define("STRADS", "ads");
        define("STRPROVINCE", "province");
        define("STRDISTRICTS", "districts");
        define("STRSUBDISTRICTS", "subdistricts");
        define("STRTOTAL", "total");
    }

    #### OTHER MODEL ####

    public function searchPayment() {
   		$sqlSearchPayment = "SELECT id, payment_code, payment_name FROM `payment`";
		$resultSearchPayment = $this->db_con->query($sqlSearchPayment);
        $arr_result[STRPAYMENTS] = mysqli_fetch_all($resultSearchPayment,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRPAYMENTS]);		

        return $arr_result;
    }

    public function searchLogistic() {
        $sqlSearchLogistic = "SELECT id, logistics_name FROM `logistics`";
        $resultSearchLogistic = $this->db_con->query($sqlSearchLogistic);
        $arr_result[STRLOGISTICS] = mysqli_fetch_all($resultSearchLogistic,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRLOGISTICS]);		

        return $arr_result;
    }

    public function searchAds() {
        $sqlSearchAds = "SELECT id, ads_name FROM `ads`";
        $resultSearchAds = $this->db_con->query($sqlSearchAds);
        $arr_result[STRADS] = mysqli_fetch_all($resultSearchAds,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRADS]);		

        return $arr_result;
    }

    public function searchProvince() {
        $sqlSearchProvince = "SELECT id,name_th,name_en FROM `provinces`";
        $resultSearchProvince = $this->db_con->query($sqlSearchProvince);
        $arr_result[STRPROVINCE] = mysqli_fetch_all($resultSearchProvince,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRPROVINCE]);		

        return $arr_result;
    }

    public function searchDistricts($provinceID) {
        $sqlSearchDistricts = "SELECT id,name_th,name_en FROM `amphures` WHERE `province_id` = '{$provinceID}'";
        $resultSearchDistricts = $this->db_con->query($sqlSearchDistricts);
        $arr_result[STRDISTRICTS] = mysqli_fetch_all($resultSearchDistricts,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRDISTRICTS]);		

        return $arr_result;
    }

    public function searchSubdistricts($districtsID) {
        $sqlSearchSubdistricts = "SELECT id,name_th,name_en,zip_code FROM `districts` WHERE `amphure_id` = '{$districtsID}'";
        $resultSearchSubdistricts = $this->db_con->query($sqlSearchSubdistricts);
        $arr_result[STRSUBDISTRICTS] = mysqli_fetch_all($resultSearchSubdistricts,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRSUBDISTRICTS]);		

        return $arr_result;
    }
    
}
