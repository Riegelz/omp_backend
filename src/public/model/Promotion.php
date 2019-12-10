<?php

namespace src\omp\model;

use src\omp\General as General;

class Promotion extends General
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
        define("ID", "id");
        define("GROUPID", "group_id");
        define("PRODUCTID", "product_id");
        define("PROMOTIONNAME", "promotion_name");
		define("PROMOTIONAMOUNT", "promotion_product_amount");
        define("PROMOTIONPRICE", "promotion_price");
        define("PROMOTIONDATESTART", "promotion_period_begin");
        define("PROMOTIONDATEEND", "promotion_period_end");
        ## String Name ##
        define("STRACCOUNTS", "accounts");
        define("STRGROUPS", "groups");
        define("STRPRODUCTS", "products");
        define("STRTOTAL", "total");
        define("STRDATETIME", "Y-m-d H:i:s");
    }

    #### CHECK EXIST IN DB ####

    public function checkExistProduct($req) {
        $account_id = $this->db_con->real_escape_string($req[PRODUCTID]);
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $sqlCheckExist = "SELECT * FROM `product` WHERE id = '{$account_id}' AND omp_id = '{$omp_id}'";
		$resultCheckExist = $this->db_con->query($sqlCheckExist);
        $arr_result = mysqli_fetch_array($resultCheckExist,MYSQLI_ASSOC);

		if(!isset($arr_result)){
            return $response_code = '610';
		}
		return true;
    }

    public function checkExistGroup($req) {
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $sqlCheckExist = "SELECT * FROM `group` WHERE id = '{$group_id}' AND omp_id = '{$omp_id}'";
		$resultCheckExist = $this->db_con->query($sqlCheckExist);
        $arr_result = mysqli_fetch_array($resultCheckExist,MYSQLI_ASSOC);

		if(!isset($arr_result)){
            return $response_code = '606';
		}
		return true;
    }
    
    #### PROMOTION MODEL ####

    public function checkDuplicatePromotion($req) {
        $promotion_name = $this->db_con->real_escape_string($req[PROMOTIONNAME]);
   		$sqlCheckDup = "SELECT * FROM `promotion` WHERE promotion_name = '$promotion_name'";
		$resultCheckDup = $this->db_con->query($sqlCheckDup);
        $arr_result = mysqli_fetch_array($resultCheckDup,MYSQLI_ASSOC);

		if(isset($arr_result)){
			return $response_code = '611';
		}
		return true;
    }

    public function createNewPromotion($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $product_id = $this->db_con->real_escape_string($req[PRODUCTID]);
        $promotion_name = $this->db_con->real_escape_string($req[PROMOTIONNAME]);
        $promotion_product_amount = $this->db_con->real_escape_string($req[PROMOTIONAMOUNT]);
        $promotion_price = $this->db_con->real_escape_string($req[PROMOTIONPRICE]);
        if (!empty($req[PROMOTIONDATESTART]) || !empty($req[PROMOTIONDATEEND])) {
            $promotion_period_begin = $this->db_con->real_escape_string($req[PROMOTIONDATESTART]);
            $promotion_period_end = $this->db_con->real_escape_string($req[PROMOTIONDATEEND]);
            $sql = ", promotion_period_begin, promotion_period_end";
            $value = ",'{$promotion_period_begin}','{$promotion_period_end}'";
        }
        $status = $this->db_con->real_escape_string($req[STATUS]);
        $create_date = date(STRDATETIME);
   
        $sqlCreateProduction = "INSERT INTO `promotion` 
            (omp_id, group_id, product_id, promotion_name, promotion_product_amount, promotion_price, status, create_date, last_update $sql)
        VALUES (
            '{$omp_id}',
            '{$group_id}',
            '{$product_id}',
            '{$promotion_name}',
            '{$promotion_product_amount}',
            '{$promotion_price}',
            '{$status}',
            '{$create_date}',
            '{$create_date}' $value
        )";
        $result_add_register = $this->db_con->query($sqlCreateProduction); 
        return $this->db_con->insert_id;
    }

    public function editPromotion($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
   		$id = $this->db_con->real_escape_string($req[ID]);
   		$group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $product_id = $this->db_con->real_escape_string($req[PRODUCTID]);
        $promotion_name = $this->db_con->real_escape_string($req[PROMOTIONNAME]);
        $promotion_product_amount = $this->db_con->real_escape_string($req[PROMOTIONAMOUNT]);
        $promotion_price = $this->db_con->real_escape_string($req[PROMOTIONPRICE]);
        if (!empty($req[PROMOTIONDATESTART]) || !empty($req[PROMOTIONDATEEND])) {
            $promotion_period_begin = $this->db_con->real_escape_string($req[PROMOTIONDATESTART]);
            $promotion_period_end = $this->db_con->real_escape_string($req[PROMOTIONDATEEND]);
            $sql = ", promotion_period_begin = '{$promotion_period_begin}', promotion_period_end = '{$promotion_period_end}'";
        }else{
            $sql = ", promotion_period_begin = NULL, promotion_period_end = NULL";
        }
        $status = $this->db_con->real_escape_string($req[STATUS]);
        $currentDate = date(STRDATETIME);

   		$sqlEditPromotion = "UPDATE `promotion` 
   		SET group_id = '{$group_id}',
            product_id = '{$product_id}',
            promotion_name = '{$promotion_name}',
            promotion_product_amount = '{$promotion_product_amount}',
            promotion_price = '{$promotion_price}',
            status = '{$status}',
            last_update = '{$currentDate}'$sql
		WHERE id = '{$id}' AND omp_id = '{$omp_id}'";
		return $this->db_con->query($sqlEditPromotion);
    }

    public function deletePromotionID($ompID,$promotionID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $promotionID = $this->db_con->real_escape_string($promotionID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
    	$sql = "DELETE FROM `promotion` WHERE id = '{$promotionID}' $where";
        $resultSearchAcc = $this->db_con->query($sql);
        if (!mysqli_affected_rows($this->db_con)) {
            $status = 612;
        }else{
            $status = 200;
        }
        return $status;
    }

    public function searchPromotionList($ompID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        ($ompID === "1") ? $where = "" : $where = " WHERE omp_id = '{$ompID}'";
   		$sqlSearchPromotion = "SELECT group_id,product_id,promotion_name,promotion_product_amount,promotion_price,promotion_period_begin,promotion_period_end,status,create_date FROM `promotion` $where";
		$resultSearchPromotion = $this->db_con->query($sqlSearchPromotion);
        $arr_result[STRPRODUCTS] = mysqli_fetch_all($resultSearchPromotion,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRPRODUCTS]);		

        return $arr_result;
    }

    public function searchPromotionListByPromotionID($ompID,$promotionID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $promotionID = $this->db_con->real_escape_string($promotionID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
   		$sqlSearchPromotion = "SELECT group_id,product_id,promotion_name,promotion_product_amount,promotion_price,promotion_period_begin,promotion_period_end,status,create_date FROM `promotion` WHERE `id` = '{$promotionID}' $where";
		$resultSearchPromotion = $this->db_con->query($sqlSearchPromotion);
        $arr_result[STRPRODUCTS] = mysqli_fetch_all($resultSearchPromotion,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRPRODUCTS]);		

        return $arr_result;
    }

    public function searchPromotionListByGroupID($ompID,$groupID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $groupid = $this->db_con->real_escape_string($groupID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
   		$sqlSearchPromotion = "SELECT group_id,product_id,promotion_name,promotion_product_amount,promotion_price,promotion_period_begin,promotion_period_end,status,create_date FROM `promotion` WHERE `group_id` = '{$groupID}' $where";
		$resultSearchPromotion = $this->db_con->query($sqlSearchPromotion);
        $arr_result[STRPRODUCTS] = mysqli_fetch_all($resultSearchPromotion,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRPRODUCTS]);		

        return $arr_result;
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
    
}
