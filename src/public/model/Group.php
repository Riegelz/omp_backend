<?php

namespace src\omp\model;

use src\omp\General as General;

class Group extends General
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
        define("STRACCOUNTS", "accounts");
        define("STRGROUPS", "groups");
        define("STRPRODUCTS", "products");
        define("STRTOTAL", "total");
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

   		$sqlCheckDup = "SELECT * FROM `group` WHERE group_name = '{$group_name}' AND id != '{$id}'";
		$resultCheckDup = $this->db_con->query($sqlCheckDup);
        $arr_result = mysqli_fetch_array($resultCheckDup,MYSQLI_ASSOC);

		if(isset($arr_result)){
			return $response_code = '602';
		}else{
            $sqlEditGroup = "UPDATE `group` 
            SET group_name = '{$group_name}',
                group_description = '{$group_description}',
                status = '{$status}',
                last_update = '{$currentDate}'
            WHERE id = '{$id}' AND omp_id = '{$omp_id}'";
            $this->db_con->query($sqlEditGroup);
        }
		return $response_code = '200';
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
   		$sqlSearchGroup = "SELECT id,group_name,group_description,status,create_date FROM `group` $where";
		$resultSearchGroup = $this->db_con->query($sqlSearchGroup);
        $arr_result[STRGROUPS] = mysqli_fetch_all($resultSearchGroup,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRGROUPS]);		

        return $arr_result;
    }

    public function searchGroupByAccountID($ompID,$accountID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $accountID = $this->db_con->real_escape_string($accountID);
        ($ompID === "1") ? $where = "" : $where = " WHERE `group`.`omp_id` = '{$ompID}' AND `group`.`status` = '1' AND `group_member`.`account_id` = '{$accountID}'";
           
        $sqlSearchGroupByAccountID = "SELECT `group`.`id`,`group`.`group_name`,`group`.`group_description`,`group`.`status`,`group_member`.`group_role`,`group`.`create_date`";
        $sqlSearchGroupByAccountID .= "FROM `group`";
        $sqlSearchGroupByAccountID .= "LEFT JOIN `group_member`";
        $sqlSearchGroupByAccountID .= "ON `group`.`id` = `group_member`.`group_id` $where";
		$resultSearchGroupByAccountID = $this->db_con->query($sqlSearchGroupByAccountID);
        $arr_result[STRGROUPS] = mysqli_fetch_all($resultSearchGroupByAccountID,MYSQLI_ASSOC);
        $arr_result[STRTOTAL] = count($arr_result[STRGROUPS]);		

        return $arr_result;
    }

    public function searchGroupID($ompID,$groupID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $groupID = $this->db_con->real_escape_string($groupID);
        ($ompID === "1") ? $where = "" : $where = " AND omp_id = '{$ompID}'";
   		$sqlSearchGroup = "SELECT id,group_name,group_description,status,create_date FROM `group` WHERE id = '{$groupID}' $where";
		$resultSearchGroup = $this->db_con->query($sqlSearchGroup);
        $arr_result[STRGROUPS] = mysqli_fetch_all($resultSearchGroup,MYSQLI_ASSOC);

        return $arr_result;
    }

    public function searchGroupMember($ompID,$groupID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $groupID = $this->db_con->real_escape_string($groupID);
        ($ompID === "1") ? $where = "" : $where = " AND `group`.`omp_id` = '{$ompID}'";
        $sqlSearchGroup .= "SELECT `group`.`group_name`,`account`.`id`,`account`.`account_name`,`account`.`username`,`group_member`.`group_role`,`group_member`.`last_update`";
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

    public function searchGroupMemberByID($ompID,$groupID,$accountID) {
        $ompID = $this->db_con->real_escape_string($ompID);
        $groupID = $this->db_con->real_escape_string($groupID);
        $accountID = $this->db_con->real_escape_string($accountID);
        ($ompID === "1") ? $where = "" : $where = " AND `group`.`omp_id` = '{$ompID}'";
        $sqlSearchGroup .= "SELECT `group`.`group_name`,`account`.`id`,`account`.`account_name`,`account`.`username`,`group_member`.`group_role`,`group_member`.`last_update`";
        $sqlSearchGroup .= "FROM `group_member`";
        $sqlSearchGroup .= "LEFT JOIN `group`";
        $sqlSearchGroup .= "ON `group_member`.`group_id` = `group`.`id`";
        $sqlSearchGroup .= "LEFT JOIN `account`";
        $sqlSearchGroup .= "ON `group_member`.`account_id` = `account`.`id`";
   		$sqlSearchGroup .= "WHERE `group_member`.`group_id` = '{$groupID}' AND `group_member`.`account_id` = '{$accountID}' $where";
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

    public function deleteAccountAllGroup($req) {
        $accountID = $this->db_con->real_escape_string($req);
    	$sql = "DELETE FROM `group_member` WHERE account_id = '{$accountID}'";
        $resultSearchAcc = $this->db_con->query($sql);
    }
    
}
