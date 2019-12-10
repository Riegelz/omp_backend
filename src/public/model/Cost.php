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
        define("ADSID", "ads_id");
        define("ADSACCOUNTID", "ads_by_account_id");
        define("LOGISTICSACCOUNTID", "logistics_by_account_id");
        define("LOGISTICSCOST", "logistics_cost");
        define("ADSCOST", "ads_cost");
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
        $logistics_by_account_id = (!empty($req[LOGISTICSACCOUNTID])) ? $this->db_con->real_escape_string($req[LOGISTICSACCOUNTID]) : $this->db_con->real_escape_string($req[ADSACCOUNTID]);
        if ($logistics_by_account_id !== "1") {
            $where = " AND `group_member`.`account_id` = '{$logistics_by_account_id}'";
        }
        $sqlCheckExist = "SELECT `group_member`.`account_id`,`group_member`.`group_role` FROM `group_member` LEFT JOIN `group`";
        $sqlCheckExist .= "ON `group_member`.`group_id` = `group`.`id`";
        $sqlCheckExist .= "WHERE `group_member`.`group_id` = '{$group_id}' AND `group`.`omp_id` = '{$omp_id}' $where";
		$resultCheckExist = $this->db_con->query($sqlCheckExist);
        $arr_result = mysqli_fetch_array($resultCheckExist,MYSQLI_ASSOC);

		if(!isset($arr_result)){
            return $response_code = '614';
		}
		return true;
    }

    public function checkExistAds($req) {
        $ads_id = $this->db_con->real_escape_string($req[ADSID]);
   		$sqlCheckExist = "SELECT * FROM `ads` WHERE id = '{$ads_id}'";
		$resultCheckExist = $this->db_con->query($sqlCheckExist);
        $arr_result = mysqli_fetch_array($resultCheckExist,MYSQLI_ASSOC);

		if(!isset($arr_result)){
            return $response_code = '618';
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
        $last_update = date(STRDATETIME);

        $sqlCreateGroup = "INSERT INTO `logistics_cost` 
            (omp_id, group_id, product_id, logistics_id, logistics_cost, datetime, logistics_by_account_id,last_update)
        VALUES (
            '{$omp_id}',
            '{$group_id}',
            '{$product_id}',
            '{$logistics_id}',
            '{$logistics_cost}',
            '{$datetime}',
            '{$logistics_by_account_id}',
            '{$last_update}'
        )";
        $result_add_group = $this->db_con->query($sqlCreateGroup); 
        return $this->db_con->insert_id;
    }

    public function EditLogisticsCost($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $id = $this->db_con->real_escape_string($req[ID]);
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $product_id = $this->db_con->real_escape_string($req[PRODUCTID]);
        $logistics_id = $this->db_con->real_escape_string($req[LOGISTICSID]);
        $logistics_cost = $this->db_con->real_escape_string($req[LOGISTICSCOST]);
        $datetime = $this->db_con->real_escape_string($req[DATETIME]);
        $logistics_by_account_id = $this->db_con->real_escape_string($req[LOGISTICSACCOUNTID]);
        $last_update = date(STRDATETIME);

   		$sqlEditLogisticsCost = "UPDATE `logistics_cost` 
   		SET omp_id = '{$omp_id}',
            group_id = '{$group_id}',
            product_id = '{$product_id}',
            logistics_id = '{$logistics_id}',
            logistics_cost = '{$logistics_cost}',
            datetime = '{$datetime}',
            logistics_by_account_id = '{$logistics_by_account_id}',
            last_update = '{$last_update}'
		WHERE id = '{$id}' AND omp_id = '{$omp_id}'";
		return $this->db_con->query($sqlEditLogisticsCost);
    }

    public function getRoleInGroup($accountID,$groupID) {
        $group_id = $this->db_con->real_escape_string($groupID);
        $account_id = $this->db_con->real_escape_string($accountID);
        $sqlCheckRole = "SELECT group_role FROM `group_member` WHERE `group_id` = '{$group_id}' AND `account_id` = '{$account_id}'";
		$resultCheckRole = $this->db_con->query($sqlCheckRole);
        $arr_result = mysqli_fetch_array($resultCheckRole,MYSQLI_ASSOC);

		if(!isset($arr_result)){
            return $response_code = '614';
		}
		return $arr_result;
    }

    public function deleteLogisticsCostID($ompID,$logisticsCostID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $logisticsCostID = $this->db_con->real_escape_string($logisticsCostID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
    	$sql = "DELETE FROM `logistics_cost` WHERE id = '{$logisticsCostID}' $where";
        $resultSearchAcc = $this->db_con->query($sql);
        if (!mysqli_affected_rows($this->db_con)) {
            $status = 617;
        }else{
            $status = 200;
        }
        return $status;
    }

    public function searchLogisticCostList($ompID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        ($ompID === "1") ? $where = "" : $where = " WHERE `logistics_cost`.`omp_id` = '{$ompID}'";
        $sqlSearchLogisticCost = "SELECT `logistics_cost`.`id`,`logistics_cost`.`omp_id`,`logistics_cost`.`group_id`,`group`.`group_name`,`logistics_cost`.`product_id`,`product`.`product_name`,`logistics_cost`.`logistics_id`,`logistics`.`logistics_name`,`logistics_cost`.`logistics_cost`,`logistics_cost`.`datetime`,`logistics_cost`.`logistics_by_account_id`,`account`.`account_name`";
        $sqlSearchLogisticCost .= "FROM `logistics_cost` LEFT JOIN `group`";
        $sqlSearchLogisticCost .= "ON `logistics_cost`.`group_id` = `group`.`id` LEFT JOIN `product`";
        $sqlSearchLogisticCost .= "ON `logistics_cost`.`product_id` = `product`.`id`";
        $sqlSearchLogisticCost .= "LEFT JOIN `logistics`";
        $sqlSearchLogisticCost .= "ON `logistics_cost`.`logistics_id` = `logistics`.`id` LEFT JOIN `account`";
        $sqlSearchLogisticCost .= "ON `logistics_cost`.`logistics_by_account_id` = `account`.`id` $where";
		$resultSearchLogisticCost = $this->db_con->query($sqlSearchLogisticCost);
        $arr_result[STRPRODUCTS] = mysqli_fetch_all($resultSearchLogisticCost,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRPRODUCTS]);		

        return $arr_result;
    }

    public function searchLogisticCostListByID($ompID,$logisticcostID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $logisticcostID = $this->db_con->real_escape_string($logisticcostID);
        ($ompID === "1") ? $where = "" : $where = " WHERE `logistics_cost`.`omp_id` = '{$ompID}' AND `logistics_cost`.`id` = '{$logisticcostID}'";
        $sqlSearchLogisticCost = "SELECT `logistics_cost`.`id`,`logistics_cost`.`omp_id`,`logistics_cost`.`group_id`,`group`.`group_name`,`logistics_cost`.`product_id`,`product`.`product_name`,`logistics_cost`.`logistics_id`,`logistics`.`logistics_name`,`logistics_cost`.`logistics_cost`,`logistics_cost`.`datetime`,`logistics_cost`.`logistics_by_account_id`,`account`.`account_name`";
        $sqlSearchLogisticCost .= "FROM `logistics_cost` LEFT JOIN `group`";
        $sqlSearchLogisticCost .= "ON `logistics_cost`.`group_id` = `group`.`id` LEFT JOIN `product`";
        $sqlSearchLogisticCost .= "ON `logistics_cost`.`product_id` = `product`.`id`";
        $sqlSearchLogisticCost .= "LEFT JOIN `logistics`";
        $sqlSearchLogisticCost .= "ON `logistics_cost`.`logistics_id` = `logistics`.`id` LEFT JOIN `account`";
        $sqlSearchLogisticCost .= "ON `logistics_cost`.`logistics_by_account_id` = `account`.`id` $where";
		$resultSearchLogisticCost = $this->db_con->query($sqlSearchLogisticCost);
        $arr_result[STRPRODUCTS] = mysqli_fetch_all($resultSearchLogisticCost,MYSQLI_ASSOC);

        return $arr_result;
    }

    public function AddAdsCost($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $product_id = $this->db_con->real_escape_string($req[PRODUCTID]);
        $ads_id = $this->db_con->real_escape_string($req[ADSID]);
        $ads_cost = $this->db_con->real_escape_string($req[ADSCOST]);
        $datetime = $this->db_con->real_escape_string($req[DATETIME]);
        $ads_by_account_id = $this->db_con->real_escape_string($req[ADSACCOUNTID]);
        $last_update = date(STRDATETIME);

        $sqlAddAdsCost = "INSERT INTO `ads_cost` 
            (omp_id, group_id, product_id, ads_id, ads_cost, datetime, ads_by_account_id,last_update)
        VALUES (
            '{$omp_id}',
            '{$group_id}',
            '{$product_id}',
            '{$ads_id}',
            '{$ads_cost}',
            '{$datetime}',
            '{$ads_by_account_id}',
            '{$last_update}'
        )";
        $result = $this->db_con->query($sqlAddAdsCost); 
        return $this->db_con->insert_id;
    }

    public function EditAdsCost($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $id = $this->db_con->real_escape_string($req[ID]);
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $product_id = $this->db_con->real_escape_string($req[PRODUCTID]);
        $ads_id = $this->db_con->real_escape_string($req[ADSID]);
        $ads_cost = $this->db_con->real_escape_string($req[ADSCOST]);
        $datetime = $this->db_con->real_escape_string($req[DATETIME]);
        $ads_by_account_id = $this->db_con->real_escape_string($req[ADSACCOUNTID]);
        $last_update = date(STRDATETIME);

   		$sqlEditAdsCost = "UPDATE `ads_cost` 
   		SET omp_id = '{$omp_id}',
            group_id = '{$group_id}',
            product_id = '{$product_id}',
            ads_id = '{$ads_id}',
            ads_cost = '{$ads_cost}',
            datetime = '{$datetime}',
            ads_by_account_id = '{$ads_by_account_id}',
            last_update = '{$last_update}'
		WHERE id = '{$id}' AND omp_id = '{$omp_id}'";
		return $this->db_con->query($sqlEditAdsCost);
    }

    public function deleteAdsID($ompID,$adsID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $adsID = $this->db_con->real_escape_string($adsID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
    	$sql = "DELETE FROM `ads_cost` WHERE id = '{$adsID}' $where";
        $resultSearchAcc = $this->db_con->query($sql);
        if (!mysqli_affected_rows($this->db_con)) {
            $status = 619;
        }else{
            $status = 200;
        }
        return $status;
    }

    public function searchAdsCostList($ompID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        ($ompID === "1") ? $where = "" : $where = " WHERE `ads_cost`.`omp_id` = '{$ompID}'";
        $sqlSearchAdsCost = "SELECT `ads_cost`.`id`,`ads_cost`.`omp_id`,`ads_cost`.`group_id`,`group`.`group_name`,`ads_cost`.`product_id`,`product`.`product_name`,`ads_cost`.`ads_id`,`ads`.`ads_name`,`ads_cost`.`ads_cost`,`ads_cost`.`datetime`,`ads_cost`.`ads_by_account_id`,`account`.`account_name`";
        $sqlSearchAdsCost .= "FROM `ads_cost`";
        $sqlSearchAdsCost .= "LEFT JOIN `group`";
        $sqlSearchAdsCost .= "ON `ads_cost`.`group_id` = `group`.`id`";
        $sqlSearchAdsCost .= "LEFT JOIN `product`";
        $sqlSearchAdsCost .= "ON `ads_cost`.`product_id` = `product`.`id`";
        $sqlSearchAdsCost .= "LEFT JOIN `ads`";
        $sqlSearchAdsCost .= "ON `ads_cost`.`ads_id` = `ads`.`id`";
        $sqlSearchAdsCost .= "LEFT JOIN `account`";
        $sqlSearchAdsCost .= "ON `ads_cost`.`ads_by_account_id` = `account`.`id` $where";
		$resultSearchAdsCost = $this->db_con->query($sqlSearchAdsCost);
        $arr_result[STRPRODUCTS] = mysqli_fetch_all($resultSearchAdsCost,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRPRODUCTS]);		

        return $arr_result;
    }

    public function searchAdsCostListByID($ompID,$adsID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $adsID = $this->db_con->real_escape_string($adsID);
        ($ompID === "1") ? $where = "" : $where = " WHERE `ads_cost`.`omp_id` = '{$ompID}' AND `ads_cost`.`id` = '{$adsID}'";
        $sqlSearchAdsCost = "SELECT `ads_cost`.`id`,`ads_cost`.`omp_id`,`ads_cost`.`group_id`,`group`.`group_name`,`ads_cost`.`product_id`,`product`.`product_name`,`ads_cost`.`ads_id`,`ads`.`ads_name`,`ads_cost`.`ads_cost`,`ads_cost`.`datetime`,`ads_cost`.`ads_by_account_id`,`account`.`account_name`";
        $sqlSearchAdsCost .= "FROM `ads_cost` LEFT JOIN `group`";
        $sqlSearchAdsCost .= "ON `ads_cost`.`group_id` = `group`.`id`";
        $sqlSearchAdsCost .= "LEFT JOIN `product`";
        $sqlSearchAdsCost .= "ON `ads_cost`.`product_id` = `product`.`id`";
        $sqlSearchAdsCost .= "LEFT JOIN `ads`";
        $sqlSearchAdsCost .= "ON `ads_cost`.`ads_id` = `ads`.`id`";
        $sqlSearchAdsCost .= "LEFT JOIN `account`";
        $sqlSearchAdsCost .= "ON `ads_cost`.`ads_by_account_id` = `account`.`id` $where";
		$resultSearchAdsCost = $this->db_con->query($sqlSearchAdsCost);
        $arr_result[STRPRODUCTS] = mysqli_fetch_all($resultSearchAdsCost,MYSQLI_ASSOC);

        return $arr_result;
    }
    
}
