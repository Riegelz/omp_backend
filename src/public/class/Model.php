<?php

namespace src\omp;

use src\model\RegisterDB as RegisterDB;

class Model extends General
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
        define("GROUPNAME", "group_name");
        define("GROUPDESCRIPT", "group_description");
        define("ID", "id");
        define("GROUPID", "group_id");
        define("ACCOUNTID", "account_id");
        define("GROUPROLE", "group_role");
        ## String Name ##
        define("STRACCOUNTS", "accounts");
        define("STRGROUPS", "groups");
        define("STRDATETIME", "Y-m-d H:i:s");
    }

    #### CHECK EXIST IN DB ####

    public function checkExistAccount($req) {
        $account_id = $this->db_con->real_escape_string($req[ACCOUNTID]);
   		$sqlCheckExist = "SELECT * FROM account WHERE id = '{$account_id}'";
		$resultCheckExist = $this->db_con->query($sqlCheckExist);
        $arr_result = mysqli_fetch_array($resultCheckExist,MYSQLI_ASSOC);

		if(!isset($arr_result)){
            return $response_code = '605';
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
   		$password = md5($this->db_con->real_escape_string($req[PASSWORD]));
        $status = $this->db_con->real_escape_string($req[STATUS]);
        $currentDate = date(STRDATETIME);
   		$account_role = $this->db_con->real_escape_string($req[ACCOUNTROLE]);

   		$sqlEditAccount = "UPDATE account 
   		SET account_name = '{$account_name}',
            username = '{$username}',
            password = '{$password}',
            status = '{$status}',
            last_update = '{$currentDate}',
            account_role = '{$account_role}'
		WHERE id = '{$id}' AND omp_id = '{$omp_id}'";
		return $this->db_con->query($sqlEditAccount);
    }

    public function searchAccount($req) {
        $ompID = $this->db_con->real_escape_string($req);
        ($ompID === "1") ? $where = "" : $where = " WHERE omp_id = '{$ompID}'";
   		$sqlSearchAcc = "SELECT account_name,username,status,create_date FROM account $where";
		$resultSearchAcc = $this->db_con->query($sqlSearchAcc);
        $arr_result[STRACCOUNTS] = mysqli_fetch_all($resultSearchAcc,MYSQLI_ASSOC);
        $arr_result['total'] = count($arr_result[STRACCOUNTS]);		

        return $arr_result;
    }

    public function searchAccountID($ompID,$accountID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $accountID = $this->db_con->real_escape_string($accountID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
   		$sqlSearchAcc = "SELECT account_name,username,status,create_date FROM account WHERE id = '{$accountID}' $where";
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

    #### GROUP MODEL ####

    public function checkDuplicateGroup($req) {
        $groupname = $this->db_con->real_escape_string($req[GROUPNAME]);
   		$sqlCheckDup = "SELECT * FROM `group` WHERE group_name = '{$groupname}'";
		$resultCheckDup = $this->db_con->query($sqlCheckDup);
        $arr_result = mysqli_fetch_array($resultCheckDup,MYSQLI_ASSOC);

		if(isset($arr_result)){
			return $response_code = '602';
		}
		return true;
    }

    public function createNewGroup($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
        $group_name = $this->db_con->real_escape_string($req[GROUPNAME]);
        $group_description = $this->db_con->real_escape_string($req[GROUPDESCRIPT]);
        $status = $this->db_con->real_escape_string($req[STATUS]);
        $create_date = date(STRDATETIME);
   
        $sqlCreateGroup = "INSERT INTO `group` 
            (omp_id, group_name, group_description, status, create_date, last_update)
        VALUES (
            '{$omp_id}',
            '{$group_name}',
            '{$group_description}',
            '{$status}',
            '{$create_date}',
            '{$create_date}'
        )";
        $result_add_group = $this->db_con->query($sqlCreateGroup); 
        return $this->db_con->insert_id;
    }

    public function editGroup($req) {
        $omp_id = $this->db_con->real_escape_string($req[OMPID]);
   		$id = $this->db_con->real_escape_string($req[ID]);
   		$group_name = $this->db_con->real_escape_string($req[GROUPNAME]);
        $group_description = $this->db_con->real_escape_string($req[GROUPDESCRIPT]);
        $status = $this->db_con->real_escape_string($req[STATUS]);
        $currentDate = date(STRDATETIME);

   		echo $sqlEditGroup = "UPDATE `group` 
   		SET group_name = '{$group_name}',
            group_description = '{$group_description}',
            status = '{$status}',
            last_update = '{$currentDate}'
		WHERE id = '{$id}' AND omp_id = '{$omp_id}'";
		return $this->db_con->query($sqlEditGroup);
    }

    public function deleteGroupID($ompID,$groupID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $groupID = $this->db_con->real_escape_string($groupID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
    	$sql = "DELETE FROM `group` WHERE id = '{$groupID}' $where";
        $resultSearchAcc = $this->db_con->query($sql);
        if (!mysqli_affected_rows($this->db_con)) {
            $status = 603;
        }else{
            $status = 200;
        }
        return $status;
    }

    public function searchGroup($req) {
        $ompID = $this->db_con->real_escape_string($req);
        ($ompID === "1") ? $where = "" : $where = " WHERE omp_id = '{$ompID}'";
   		$sqlSearchGroup = "SELECT group_name,group_description,status,create_date FROM `group` $where";
		$resultSearchGroup = $this->db_con->query($sqlSearchGroup);
        $arr_result[STRGROUPS] = mysqli_fetch_all($resultSearchGroup,MYSQLI_ASSOC);
        $arr_result['total'] = count($arr_result[STRGROUPS]);		

        return $arr_result;
    }

    public function searchGroupID($ompID,$groupID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $groupID = $this->db_con->real_escape_string($groupID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
   		$sqlSearchGroup = "SELECT group_name,group_description,status,create_date FROM `group` WHERE id = '{$groupID}' $where";
		$resultSearchGroup = $this->db_con->query($sqlSearchGroup);
        $arr_result[STRGROUPS] = mysqli_fetch_all($resultSearchGroup,MYSQLI_ASSOC);

        return $arr_result;
    }

    public function searchGroupMember($ompID,$groupID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $groupID = $this->db_con->real_escape_string($groupID);
        ($ompID === "1") ? $where = "" : $where = " AND `group`.`omp_id` = '{$ompID}'";
        $sqlSearchGroup .= "SELECT `group`.`group_name`,`account`.`account_name`,`account`.`username`,`group_member`.`group_role`,`group_member`.`last_update`";
        $sqlSearchGroup .= "FROM `group_member`";
        $sqlSearchGroup .= "LEFT JOIN `group`";
        $sqlSearchGroup .= "ON `group_member`.`group_id` = `group`.`id`";
        $sqlSearchGroup .= "LEFT JOIN `account`";
        $sqlSearchGroup .= "ON `group_member`.`account_id` = `account`.`id`";
   		$sqlSearchGroup .= "WHERE `group_member`.`group_id` = '{$groupID}' $where";
		$resultSearchGroup = $this->db_con->query($sqlSearchGroup);
        $arr_result[STRGROUPS] = mysqli_fetch_all($resultSearchGroup,MYSQLI_ASSOC);

        return $arr_result;
    }

    public function checkDuplicateGroupMember($req) {
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $account_id = $this->db_con->real_escape_string($req[ACCOUNTID]);
        $group_role = $this->db_con->real_escape_string($req[GROUPROLE]);
   		$sqlCheckDup = "SELECT * FROM `group_member` WHERE `group_id` = '{$group_id}' AND `account_id` = '{$account_id}'";
		$resultCheckDup = $this->db_con->query($sqlCheckDup);
        $arr_result = mysqli_fetch_array($resultCheckDup,MYSQLI_ASSOC);

		if(isset($arr_result)){
			return $response_code = '604';
		}
		return true;
    }

    public function addGroupMember($req) {
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $account_id = $this->db_con->real_escape_string($req[ACCOUNTID]);
        $group_role = $this->db_con->real_escape_string($req[GROUPROLE]);
        $last_update = date(STRDATETIME);
   
        $sqlAddGroupMember = "INSERT INTO `group_member` 
            (group_id, account_id, group_role, last_update)
        VALUES (
            '{$group_id}',
            '{$account_id}',
            '{$group_role}',
            '{$last_update}'
        )";
        $result_add_group = $this->db_con->query($sqlAddGroupMember); 
        return $this->db_con->insert_id;
    }

    public function delGroupMember($req) {
        $group_id = $this->db_con->real_escape_string($req[GROUPID]);
        $account_id = $this->db_con->real_escape_string($req[ACCOUNTID]);
    	$sql = "DELETE FROM `group_member` WHERE group_id = '{$group_id}' AND account_id = '{$account_id}'";
        $resultSearchAcc = $this->db_con->query($sql);
        if (!mysqli_affected_rows($this->db_con)) {
            $status = 607;
        }else{
            $status = 200;
        }
        return $status;
    }
    
}
