<?php

namespace src\omp;

class Rule extends General
{   
    public function __construct() {
        ## String Name ##
        define("ADMIN", "Admin");
        define("MEMBER", "Member");
        define("OWNER", "Owner");
        define("STROMPID", "ompID");
        define("STRACCOUNTID", "accountID");
        ## Group Role ##
        $this->groupRole = ["0" => MEMBER, "1" => ADMIN, "2" => OWNER];
    }
    
    public function PermissionAll($role) {
        $self = New Self();
        $permissionRole = [MEMBER,ADMIN,OWNER];
        if (!in_array($this->groupRole[$role],$permissionRole)) {
            return $response_code = '616';
        }
        return true;
    }

    public function PermissionMedium($role) {
        $self = New Self();
        $permissionRole = [ADMIN,OWNER];
        if (!in_array($this->groupRole[$role],$permissionRole)) {
            return $response_code = '616';
        }
        return true;
    }

    public function PermissionHigh($role) {
        $self = New Self();
        $permissionRole = [OWNER];
        if (!in_array($this->groupRole[$role],$permissionRole)) {
            return $response_code = '616';
        }
        return true;
    }
}