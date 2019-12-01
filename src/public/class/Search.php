<?php

namespace src\omp;

use src\omp\Model as Model;

class Search extends General
{
        public function __construct() {
                ## String Name ##
                define("SEARCHOMPID", "ompID");
                define("SEARCHACCOUNTID", "accountID");
                define("SEARCHGROUPID", "groupID");
        }

	public static function searchAccount($request,$response,$args)
	{
                $self = New Self();
                $model = New Model();
                $ompID = $args[SEARCHOMPID];
                ## Search account ##
                $searchAccount = $model->searchAccount($ompID);
                if(isset($searchAccount)) { return $response->withJson(General::responseFormat(200,$searchAccount)); }
        }
    
        public static function searchAccountID($request,$response,$args)
        {
                $self = New Self();
                $model = New Model();
                $ompID = $args[SEARCHOMPID];
                $accountID = $args[SEARCHACCOUNTID];
                ## Search account ID ##
                $searchAccountID = $model->searchAccountID($ompID,$accountID);
                if(isset($searchAccountID)) { return $response->withJson(General::responseFormat(200,$searchAccountID)); }
        }

        public static function searchGroup($request,$response,$args)
	{
                $self = New Self();
                $model = New Model();
                $ompID = $args[SEARCHOMPID];
                ## Search group ##
                $searchGroup = $model->searchGroup($ompID);
                if(isset($searchGroup)) { return $response->withJson(General::responseFormat(200,$searchGroup)); }
        }

        public static function searchGroupID($request,$response,$args)
        {
                $self = New Self();
                $model = New Model();
                $ompID = $args[SEARCHOMPID];
                $groupID = $args[SEARCHGROUPID];
                ## Search group ID ##
                $searchGroupID = $model->searchGroupID($ompID,$groupID);
                if(isset($searchGroupID)) { return $response->withJson(General::responseFormat(200,$searchGroupID)); }
        }

        public static function searchGroupMember($request,$response,$args)
        {
                $self = New Self();
                $model = New Model();
                $ompID = $args[SEARCHOMPID];
                $groupID = $args[SEARCHGROUPID];
                ## Search group member ##
                $searchGroupMember = $model->searchGroupMember($ompID,$groupID);
                if(isset($searchGroupMember)) { return $response->withJson(General::responseFormat(200,$searchGroupMember)); }
        }
}