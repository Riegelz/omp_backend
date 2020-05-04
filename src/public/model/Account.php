<?php

namespace src\omp\model;

use src\omp\General as General;

class Account extends General
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
        define("USERNAME", "username");
        define("ACCOUNTNAME", "account_name");
        define("PASSWORD", "password");
        define("STATUS", "status");
        define("ACCOUNTROLE", "account_role");
        define("OMPID", "omp_id");
        define("ID", "id");
        define("ACCOUNTID", "account_id");
        ## String Name ##
        define("STRACCOUNTS", "accounts");
        define("STRGROUPS", "groups");
        define("STRPRODUCTS", "products");
        define("STRTOTAL", "total");
        define("STRDATETIME", "Y-m-d H:i:s");
    }

    #### ACCOUNT MODEL ####

    public function checkDuplicateAccount($req) {
        $username = $this->db_con->real_escape_string($req[USERNAME]);
   		$sqlCheckDup = "SELECT * FROM account WHERE username = '$username'";
		$resultCheckDup = $this->db_con->query($sqlCheckDup);
        $arr_result = mysqli_fetch_array($resultCheckDup,MYSQLI_ASSOC);

		if(isset($arr_result)){
			return $response_code = '600';
		}
		return true;
    }

    public function createNewAccount($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $account_name = $this->db_con->real_escape_string($req[ACCOUNTNAME]);
        $username = $this->db_con->real_escape_string($req[USERNAME]);
        $password = md5($this->db_con->real_escape_string($req[PASSWORD]));
        $status = $this->db_con->real_escape_string($req[STATUS]);
        $create_date = date(STRDATETIME);
        $account_role = $this->db_con->real_escape_string($req[ACCOUNTROLE]);
   
        $sqlCreateAccount = "INSERT INTO account 
            (omp_id, account_name, username, password, status, create_date, last_update, account_role)
        VALUES (
            '{$omp_id}',
            '{$account_name}',
            '{$username}',
            '{$password}',
            '{$status}',
            '{$create_date}',
            '{$create_date}',
            '{$account_role}'
        )";
        $result_add_register = $this->db_con->query($sqlCreateAccount); 
        return $this->db_con->insert_id;
    }

    public function editAccount($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
   		$id = $this->db_con->real_escape_string($req[ID]);
   		$account_name = $this->db_con->real_escape_string($req[ACCOUNTNAME]);
        $username = $this->db_con->real_escape_string($req[USERNAME]);
        if (!empty($req[PASSWORD]) || !$req[PASSWORD] == "") {
            $password = md5($this->db_con->real_escape_string($req[PASSWORD]));
            $sqlpass = ",password = '{$password}'";
        }
        $status = $this->db_con->real_escape_string($req[STATUS]);
        $currentDate = date(STRDATETIME);
   		$account_role = $this->db_con->real_escape_string($req[ACCOUNTROLE]);

   		$sqlEditAccount = "UPDATE account 
   		SET account_name = '{$account_name}',
            username = '{$username}',
            status = '{$status}',
            last_update = '{$currentDate}',
            account_role = '{$account_role}'$sqlpass
		WHERE id = '{$id}' AND omp_id = '{$omp_id}'";
		return $this->db_con->query($sqlEditAccount);
    }

    public function searchAccount($req) {
        $ompID = $this->db_con->real_escape_string($req);
        ($ompID === "1") ? $where = "" : $where = " WHERE omp_id = '{$ompID}' AND id != '1' And status != '0'";
   		$sqlSearchAcc = "SELECT id,account_name,username,status,account_role,create_date FROM account $where";
		$resultSearchAcc = $this->db_con->query($sqlSearchAcc);
        $arr_result[STRACCOUNTS] = mysqli_fetch_all($resultSearchAcc,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRACCOUNTS]);		

        return $arr_result;
    }

    public function searchAccountID($ompID,$accountID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $accountID = $this->db_con->real_escape_string($accountID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
   		$sqlSearchAcc = "SELECT id,account_name,username,status,account_role,create_date FROM account WHERE id = '{$accountID}' $where";
		$resultSearchAcc = $this->db_con->query($sqlSearchAcc);
        $arr_result[STRACCOUNTS] = mysqli_fetch_all($resultSearchAcc,MYSQLI_ASSOC);

        return $arr_result;
    }

    public function deleteAccountID($ompID,$accountID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $accountID = $this->db_con->real_escape_string($accountID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
    	$sql = "DELETE FROM account WHERE id = '{$accountID}' $where";
        $resultSearchAcc = $this->db_con->query($sql);
        if (!mysqli_affected_rows($this->db_con)) {
            $status = 601;
        }else{
            $status = 200;
        }
        return $status;
    }

    public function deleteAccountAllGroup($req) {
        $accountID = $this->db_con->real_escape_string($req);
    	$sql = "DELETE FROM `group_member` WHERE account_id = '{$accountID}'";
        $resultSearchAcc = $this->db_con->query($sql);
    }

    public function searchOmpID($ompuser,$omptoken) {
        $ompuser = $this->db_con->real_escape_string($ompuser);
        $omptoken = $this->db_con->real_escape_string($omptoken);
   		$sqlSearchAcc = "SELECT id,omp_name,omp_secret_id,status FROM `omp_account` WHERE omp_name = '{$ompuser}' AND omp_secret_id = '{$omptoken}'";
		$resultSearchAcc = $this->db_con->query($sqlSearchAcc);
        $arr_result[STRACCOUNTS] = mysqli_fetch_all($resultSearchAcc,MYSQLI_ASSOC);

        return $arr_result;
    }

    public function Login($req) {
        $ompid = $this->db_con->real_escape_string($req['omp_id']);
        $user = $this->db_con->real_escape_string($req['username']);
        $password = md5($this->db_con->real_escape_string($req['password']));
   		$sqlSearchAcc = "SELECT * FROM `account` WHERE username = '{$user}' AND password = '{$password}'";
		$resultSearchAcc = $this->db_con->query($sqlSearchAcc);
        $arr_result[STRACCOUNTS] = mysqli_fetch_all($resultSearchAcc,MYSQLI_ASSOC);

        return $arr_result;
    }
    
}
