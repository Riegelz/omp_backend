<?php

namespace src\omp\model;

use src\omp\General as General;

class Order extends General
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
        define("OMPID", "omp_id");
        define("ID", "id");
        define("GROUPID", "group_id");
        define("PRODUCTID", "product_id");
        define("ORDERTRANSACTION","transaction_id");
		define("ORDERNAME","order_name");
		define("ORDERADDRESS","order_address");
		define("ORDERDIST","order_district");
		define("ORDERSUBDIST","order_subdistrict");
		define("ORDERZIPCODE","order_zipcode");
		define("ORDERPROV","order_province");
		define("ORDERTEL","order_telnumber");
		define("ORDERCOST","order_cost");
		define("ORDERLOGISTICID","order_logistics_id");
		define("ORDERDATETIME","order_datetime");
		define("ORDERPAYMENTID","order_payment_id");
		define("ORDERDESCRIPTION","order_description");
		define("ORDERTRACKING","order_tracking_id");
		define("ORDERSTATUS","order_status");
		define("ORDERCOUNTRY","order_country");
        define("ORDERACCOUNTID", "order_by_account_id");
        define("ORDERPROMOTIONID", "order_promotion_id");
        ## String Name ##
        define("STRACCOUNTS", "accounts");
        define("STRGROUPS", "groups");
        define("STRPRODUCTS", "products");
        define("STRORDER", "order");
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

    public function checkExistUserInGroup($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $order_by_account_id = $this->db_con->real_escape_string($req[ORDERACCOUNTID]);
        if ($order_by_account_id !== "1") {
            $where = " AND `group_member`.`account_id` = '{$order_by_account_id}'";
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

    #### ORDER MODEL ####

    public function AddOrder($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $transaction_id = $this->db_con->real_escape_string($req[ORDERTRANSACTION]);
        $product_id = $this->db_con->real_escape_string($req[PRODUCTID]);
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $order_name = $this->db_con->real_escape_string($req[ORDERNAME]);
        $order_address = $this->db_con->real_escape_string($req[ORDERADDRESS]);
        $order_district = $this->db_con->real_escape_string($req[ORDERDIST]);
        $order_subdistrict = $this->db_con->real_escape_string($req[ORDERSUBDIST]);
        $order_zipcode = $this->db_con->real_escape_string($req[ORDERZIPCODE]);
        $order_province = $this->db_con->real_escape_string($req[ORDERPROV]);
        $order_telnumber = $this->db_con->real_escape_string($req[ORDERTEL]);
        $order_cost = $this->db_con->real_escape_string($req[ORDERCOST]);
        $order_logistics_id = $this->db_con->real_escape_string($req[ORDERLOGISTICID]);
        $order_datetime = $this->db_con->real_escape_string($req[ORDERDATETIME]);
        $order_payment_id = $this->db_con->real_escape_string($req[ORDERPAYMENTID]);
        $order_promotion_id = $this->db_con->real_escape_string($req[ORDERPROMOTIONID]);
        $order_description = $this->db_con->real_escape_string($req[ORDERDESCRIPTION]);
        $order_tracking_id = $this->db_con->real_escape_string($req[ORDERTRACKING]);
        $order_status = $this->db_con->real_escape_string($req[ORDERSTATUS]);
        $order_country = $this->db_con->real_escape_string($req[ORDERCOUNTRY]);
        $order_by_account_id = $this->db_con->real_escape_string($req[ORDERACCOUNTID]);
        $order_lastupdate = date(STRDATETIME);

        $sqlAddOrder = "INSERT INTO `order_transaction` 
            (omp_id, transaction_id, product_id, group_id, order_name, order_address, order_district, order_subdistrict, order_zipcode, order_province, order_telnumber, order_cost, order_logistics_id, order_datetime, order_payment_id, order_promotion_id, order_description, order_tracking_id, order_status, order_country, order_lastupdate, order_by_account_id)
        VALUES (
            '{$omp_id}',
            '{$transaction_id}',
            '{$product_id}',
            '{$group_id}',
            '{$order_name}',
            '{$order_address}',
            '{$order_district}',
            '{$order_subdistrict}',
            '{$order_zipcode}',
            '{$order_province}',
            '{$order_telnumber}',
            '{$order_cost}',
            '{$order_logistics_id}',
            '{$order_datetime}',
            '{$order_payment_id}',
            '{$order_promotion_id}',
            '{$order_description}',
            '{$order_tracking_id}',
            '{$order_status}',
            '{$order_country}',
            '{$order_lastupdate}',
            '{$order_by_account_id}'
        )";
        $result_add_order = $this->db_con->query($sqlAddOrder); 
        return $this->db_con->insert_id;
    }

    public function EditOrder($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $id = $this->db_con->real_escape_string($req[ID]);
        $transaction_id = $this->db_con->real_escape_string($req[ORDERTRANSACTION]);
        $product_id = $this->db_con->real_escape_string($req[PRODUCTID]);
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $order_name = $this->db_con->real_escape_string($req[ORDERNAME]);
        $order_address = $this->db_con->real_escape_string($req[ORDERADDRESS]);
        $order_district = $this->db_con->real_escape_string($req[ORDERDIST]);
        $order_subdistrict = $this->db_con->real_escape_string($req[ORDERSUBDIST]);
        $order_zipcode = $this->db_con->real_escape_string($req[ORDERZIPCODE]);
        $order_province = $this->db_con->real_escape_string($req[ORDERPROV]);
        $order_telnumber = $this->db_con->real_escape_string($req[ORDERTEL]);
        $order_cost = $this->db_con->real_escape_string($req[ORDERCOST]);
        $order_logistics_id = $this->db_con->real_escape_string($req[ORDERLOGISTICID]);
        $order_datetime = $this->db_con->real_escape_string($req[ORDERDATETIME]);
        $order_payment_id = $this->db_con->real_escape_string($req[ORDERPAYMENTID]);
        $order_promotion_id = $this->db_con->real_escape_string($req[ORDERPROMOTIONID]);
        $order_description = $this->db_con->real_escape_string($req[ORDERDESCRIPTION]);
        $order_tracking_id = $this->db_con->real_escape_string($req[ORDERTRACKING]);
        $order_status = $this->db_con->real_escape_string($req[ORDERSTATUS]);
        $order_country = $this->db_con->real_escape_string($req[ORDERCOUNTRY]);
        $order_by_account_id = $this->db_con->real_escape_string($req[ORDERACCOUNTID]);
        $order_lastupdate = date(STRDATETIME);

   		$sqlEditLogisticsCost = "UPDATE `order_transaction` 
   		SET omp_id = '{$omp_id}',
            transaction_id = '{$transaction_id}',
            product_id = '{$product_id}',
            group_id = '{$group_id}',
            order_name = '{$order_name}',
            order_address = '{$order_address}',
            order_district = '{$order_district}',
            order_subdistrict = '{$order_subdistrict}',
            order_zipcode = '{$order_zipcode}',
            order_province = '{$order_province}',
            order_telnumber = '{$order_telnumber}',
            order_cost = '{$order_cost}',
            order_logistics_id = '{$order_logistics_id}',
            order_datetime = '{$order_datetime}',
            order_payment_id = '{$order_payment_id}',
            order_promotion_id = '{$order_promotion_id}',
            order_description = '{$order_description}',
            order_tracking_id = '{$order_tracking_id}',
            order_status = '{$order_status}',
            order_country = '{$order_country}',
            order_lastupdate = '{$order_lastupdate}',
            order_by_account_id = '{$order_by_account_id}'
		WHERE id = '{$id}' AND omp_id = '{$omp_id}'";
		return $this->db_con->query($sqlEditLogisticsCost);
    }

    public function deleteOrderID($ompID,$orderID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $orderID = $this->db_con->real_escape_string($orderID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
    	$sql = "DELETE FROM `order_transaction` WHERE id = '{$orderID}' $where";
        $resultSearchAcc = $this->db_con->query($sql);
        if (!mysqli_affected_rows($this->db_con)) {
            $status = 620;
        }else{
            $status = 200;
        }
        return $status;
    }

    public function searchOrderList($ompID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        ($ompID === "1") ? $where = "" : $where = " WHERE `order_transaction`.`omp_id` = '{$ompID}'";
        $sqlSearchOrder = "SELECT `order_transaction`.`id`, `order_transaction`.`omp_id`, `order_transaction`.`transaction_id`, `order_transaction`.`product_id`, `product`.`product_name`, `order_transaction`.`group_id`, `group`.`group_name`, `order_transaction`.`order_name`, `order_transaction`.`order_address`, `order_transaction`.`order_district`, `order_transaction`.`order_subdistrict`, `order_transaction`.`order_zipcode`, `order_transaction`.`order_province`, `order_transaction`.`order_telnumber`, `order_transaction`.`order_cost`, `order_transaction`.`order_logistics_id`, `logistics`.`logistics_name`, `order_transaction`.`order_datetime`, `order_transaction`.`order_payment_id`, `payment`.`payment_code`, `order_transaction`.`order_description`, `order_transaction`.`order_tracking_id`, `order_transaction`.`order_status`, `order_transaction`.`order_country`, `order_transaction`.`order_lastupdate`, `order_transaction`.`order_by_account_id`, `account`.`account_name`";
        $sqlSearchOrder .= "FROM `order_transaction` LEFT JOIN `group`";
        $sqlSearchOrder .= "ON `order_transaction`.`group_id` = `group`.`id` LEFT JOIN `product`";
        $sqlSearchOrder .= "ON `order_transaction`.`product_id` = `product`.`id` LEFT JOIN `logistics`";
        $sqlSearchOrder .= "ON `order_transaction`.`order_logistics_id` = `logistics`.`id` LEFT JOIN `account`";
        $sqlSearchOrder .= "ON `order_transaction`.`order_by_account_id` = `account`.`id` LEFT JOIN `payment`";
        $sqlSearchOrder .= "ON `order_transaction`.`order_payment_id` = `payment`.`id` $where";
		$resultSearchOrder = $this->db_con->query($sqlSearchOrder);
        $arr_result[STRORDER] = mysqli_fetch_all($resultSearchOrder,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRORDER]);		

        return $arr_result;
    }

    public function searchOrderListByID($ompID,$orderID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $orderID = $this->db_con->real_escape_string($orderID);
        ($ompID === "1") ? $where = "" : $where = " WHERE `order_transaction`.`omp_id` = '{$ompID}' AND `order_transaction`.`id` = '{$orderID}'";
        $sqlSearchOrder = "SELECT `order_transaction`.`id`, `order_transaction`.`omp_id`, `order_transaction`.`transaction_id`, `order_transaction`.`product_id`, `product`.`product_name`, `order_transaction`.`group_id`, `group`.`group_name`, `order_transaction`.`order_name`, `order_transaction`.`order_address`, `order_transaction`.`order_district`, `order_transaction`.`order_subdistrict`, `order_transaction`.`order_zipcode`, `order_transaction`.`order_province`, `order_transaction`.`order_telnumber`, `order_transaction`.`order_cost`, `order_transaction`.`order_logistics_id`, `logistics`.`logistics_name`, `order_transaction`.`order_datetime`, `order_transaction`.`order_payment_id`, `payment`.`payment_code`, `order_transaction`.`order_description`, `order_transaction`.`order_tracking_id`, `order_transaction`.`order_status`, `order_transaction`.`order_country`, `order_transaction`.`order_lastupdate`, `order_transaction`.`order_by_account_id`, `account`.`account_name`";
        $sqlSearchOrder .= "FROM `order_transaction` LEFT JOIN `group`";
        $sqlSearchOrder .= "ON `order_transaction`.`group_id` = `group`.`id` LEFT JOIN `product`";
        $sqlSearchOrder .= "ON `order_transaction`.`product_id` = `product`.`id` LEFT JOIN `logistics`";
        $sqlSearchOrder .= "ON `order_transaction`.`order_logistics_id` = `logistics`.`id` LEFT JOIN `account`";
        $sqlSearchOrder .= "ON `order_transaction`.`order_by_account_id` = `account`.`id` LEFT JOIN `payment`";
        $sqlSearchOrder .= "ON `order_transaction`.`order_payment_id` = `payment`.`id` $where";
		$resultSearchOrder = $this->db_con->query($sqlSearchOrder);
        $arr_result[STRORDER] = mysqli_fetch_all($resultSearchOrder,MYSQLI_ASSOC);

        return $arr_result;
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
    
}
