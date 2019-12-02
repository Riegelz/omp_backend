<?php

namespace src\omp\model;

use src\omp\General as General;

class Product extends General
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
        define("PRODUCTNAME", "product_name");
        define("PRODUCTPREFIX", "product_prefix");
        define("PRODUCTPRICE", "product_price");
        define("PRODUCTDETAIL", "product_detail");
        define("PRODUCTGROUPID", "product_group_id");
        ## String Name ##
        define("STRACCOUNTS", "accounts");
        define("STRGROUPS", "groups");
        define("STRPRODUCTS", "products");
        define("STRTOTAL", "total");
        define("STRDATETIME", "Y-m-d H:i:s");
    }
    
    #### PRODUCT MODEL ####

    public function checkDuplicateProduct($req) {
        $product_name = $this->db_con->real_escape_string($req[PRODUCTNAME]);
   		$sqlCheckDup = "SELECT * FROM `product` WHERE product_name = '$product_name'";
		$resultCheckDup = $this->db_con->query($sqlCheckDup);
        $arr_result = mysqli_fetch_array($resultCheckDup,MYSQLI_ASSOC);

		if(isset($arr_result)){
			return $response_code = '608';
		}
		return true;
    }

    public function createNewProduct($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $product_name = $this->db_con->real_escape_string($req[PRODUCTNAME]);
        $product_prefix = $this->db_con->real_escape_string($req[PRODUCTPREFIX]);
        $product_price = $this->db_con->real_escape_string($req[PRODUCTPRICE]);
        $product_detail = $this->db_con->real_escape_string($req[PRODUCTDETAIL]);
        $product_group_id = $this->db_con->real_escape_string($req[PRODUCTGROUPID]);
        $status = $this->db_con->real_escape_string($req[STATUS]);
        $create_date = date(STRDATETIME);
   
        $sqlCreateAccount = "INSERT INTO `product` 
            (omp_id, product_name, product_prefix, product_price, product_detail, product_group_id, status, create_date, last_update)
        VALUES (
            '{$omp_id}',
            '{$product_name}',
            '{$product_prefix}',
            '{$product_price}',
            '{$product_detail}',
            '{$product_group_id}',
            '{$status}',
            '{$create_date}',
            '{$create_date}'
        )";
        $result_add_register = $this->db_con->query($sqlCreateAccount); 
        return $this->db_con->insert_id;
    }

    public function editProduct($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
   		$id = $this->db_con->real_escape_string($req[ID]);
   		$product_name = $this->db_con->real_escape_string($req[PRODUCTNAME]);
        if(empty($req[PRODUCTPREFIX])){$product_prefix = $this->generateRandomString();}else{$product_prefix = $this->db_con->real_escape_string($req[PRODUCTPREFIX]);}
        $product_price = $this->db_con->real_escape_string($req[PRODUCTPRICE]);
        $product_detail = $this->db_con->real_escape_string($req[PRODUCTDETAIL]);
        $product_group_id = $this->db_con->real_escape_string($req[PRODUCTGROUPID]);
        $status = $this->db_con->real_escape_string($req[STATUS]);
        $currentDate = date(STRDATETIME);

   		echo $sqlEditProduct = "UPDATE `product` 
   		SET product_name = '{$product_name}',
            product_prefix = '{$product_prefix}',
            product_price = '{$product_price}',
            product_detail = '{$product_detail}',
            product_group_id = '{$product_group_id}',
            status = '{$status}',
            last_update = '{$currentDate}'
		WHERE id = '{$id}' AND omp_id = '{$omp_id}'";
		return $this->db_con->query($sqlEditProduct);
    }

    public function deleteProductID($ompID,$productID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $productID = $this->db_con->real_escape_string($productID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
    	$sql = "DELETE FROM `product` WHERE id = '{$productID}' $where";
        $resultSearchAcc = $this->db_con->query($sql);
        if (!mysqli_affected_rows($this->db_con)) {
            $status = 609;
        }else{
            $status = 200;
        }
        return $status;
    }

    public function searchProductList($ompID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        ($ompID === "1") ? $where = "" : $where = " WHERE omp_id = '{$ompID}'";
   		$sqlSearchProduct = "SELECT product_name,product_prefix,product_price,product_detail,status,create_date FROM `product` $where";
		$resultSearchProduct = $this->db_con->query($sqlSearchProduct);
        $arr_result[STRPRODUCTS] = mysqli_fetch_all($resultSearchProduct,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRPRODUCTS]);		

        return $arr_result;
    }

    public function searchProductListByGroupID($ompID,$groupID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $groupid = $this->db_con->real_escape_string($groupID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
   		$sqlSearchProduct = "SELECT product_name,product_prefix,product_price,product_detail,status,create_date FROM `product` WHERE `product_group_id` = '{$groupid}' $where";
		$resultSearchProduct = $this->db_con->query($sqlSearchProduct);
        $arr_result[STRPRODUCTS] = mysqli_fetch_all($resultSearchProduct,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRPRODUCTS]);		

        return $arr_result;
    }

    public function searchProductListByProductID($ompID,$productID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $productID = $this->db_con->real_escape_string($productID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
   		$sqlSearchProduct = "SELECT product_name,product_prefix,product_price,product_detail,status,create_date FROM `product` WHERE `id` = '{$productID}' $where";
		$resultSearchProduct = $this->db_con->query($sqlSearchProduct);
        $arr_result[STRPRODUCTS] = mysqli_fetch_all($resultSearchProduct,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRPRODUCTS]);		

        return $arr_result;
    }

    public function generateRandomString($length = 5) {
        return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    
}
