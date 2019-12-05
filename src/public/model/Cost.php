<?php

namespace src\omp\model;

use src\omp\General as General;

class Cost extends General
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
        define("PRODUCTID", "product_id");
        define("LOGISTICSID", "logistics_id");
        define("LOGISTICSACCOUNTID", "logistics_by_account_id");
        define("LOGISTICSCOST", "logistics_cost");
        define("DATETIME", "datetime");
        ## String Name ##
        define("STRACCOUNTS", "accounts");
        define("STRGROUPS", "groups");
        define("STRPRODUCTS", "products");
        define("STRTOTAL", "total");
        define("STRDATETIME", "Y-m-d H:i:s");
    }

    #### CHECK EXIST IN DB ####

    public function checkExistProduct($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $product_id = $this->db_con->real_escape_string($req[PRODUCTID]);
   		$sqlCheckExist = "SELECT * FROM `product` WHERE id = '{$product_id}' AND product_group_id = '{$group_id}' AND omp_id = '{$omp_id}'";
		$resultCheckExist = $this->db_con->query($sqlCheckExist);
        $arr_result = mysqli_fetch_array($resultCheckExist,MYSQLI_ASSOC);

		if(!isset($arr_result)){
            return $response_code = '615';
		}
		return true;
    }

    public function checkExistGroup($req) {
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
   		$sqlCheckExist = "SELECT * FROM `group` WHERE id = '{$group_id}'";
		$resultCheckExist = $this->db_con->query($sqlCheckExist);
        $arr_result = mysqli_fetch_array($resultCheckExist,MYSQLI_ASSOC);

		if(!isset($arr_result)){
            return $response_code = '606';
		}
		return true;
    }

    public function checkExistLogistics($req) {
        $logistics_id = $this->db_con->real_escape_string($req[LOGISTICSID]);
   		$sqlCheckExist = "SELECT * FROM `logistics` WHERE id = '{$logistics_id}'";
		$resultCheckExist = $this->db_con->query($sqlCheckExist);
        $arr_result = mysqli_fetch_array($resultCheckExist,MYSQLI_ASSOC);

		if(!isset($arr_result)){
            return $response_code = '613';
		}
		return true;
    }

    public function checkExistUserInGroup($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $logistics_by_account_id = $this->db_con->real_escape_string($req[LOGISTICSACCOUNTID]);
        $sqlCheckExist = "SELECT `group_member`.`account_id`,`group_member`.`group_role` FROM `group_member`";
        $sqlCheckExist .= "LEFT JOIN `group`";
        $sqlCheckExist .= "ON `group_member`.`group_id` = `group`.`id`";
        $sqlCheckExist .= "WHERE `group_member`.`account_id` = '{$logistics_by_account_id}' AND `group_member`.`group_id` = '{$group_id}' AND `group`.`omp_id` = '{$omp_id}'";
		$resultCheckExist = $this->db_con->query($sqlCheckExist);
        $arr_result = mysqli_fetch_array($resultCheckExist,MYSQLI_ASSOC);

		if(!isset($arr_result)){
            return $response_code = '614';
		}
		return true;
    }

    #### COST MODEL ####

    public function AddLogisticsCost($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $product_id = $this->db_con->real_escape_string($req[PRODUCTID]);
        $logistics_id = $this->db_con->real_escape_string($req[LOGISTICSID]);
        $logistics_cost = $this->db_con->real_escape_string($req[LOGISTICSCOST]);
        $datetime = $this->db_con->real_escape_string($req[DATETIME]);
        $logistics_by_account_id = $this->db_con->real_escape_string($req[LOGISTICSACCOUNTID]);

        $sqlCreateGroup = "INSERT INTO `logistics_cost` 
            (omp_id, group_id, product_id, logistics_id, logistics_cost, datetime, logistics_by_account_id)
        VALUES (
            '{$omp_id}',
            '{$group_id}',
            '{$product_id}',
            '{$logistics_id}',
            '{$logistics_cost}',
            '{$datetime}',
            '{$logistics_by_account_id}'
        )";
        $result_add_group = $this->db_con->query($sqlCreateGroup); 
        return $this->db_con->insert_id;
    }
    
}
