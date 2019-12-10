<?php

namespace src\omp;

use Respect\Validation\Validator as v;

class Validate extends General
{

	public function __construct() {
		## JSON Name ##
		define("JSONOMPID", "omp_id");
		define("JSONSTATUS", "status");
		define("JSONACCOUNTNAME", "account_name");
		define("JSONUSERNAME", "username");
		define("JSONPASSWORD", "password");
		define("JSONACCOUNTROLE", "account_role");
		define("JSONGROUPNAME", "group_name");
		define("JSONID", "id");
		define("JSONGROUPID", "group_id");
		define("JSONACCOUNTID", "account_id");
		define("JSONGROUPROLE", "group_role");
		define("JSONPRODUCTID", "product_id");
		define("JSONPRODUCTNAME", "product_name");
		define("JSONPRODUCTPRICE", "product_price");
		define("JSONPRODUCTGROUPID", "product_group_id");
		define("JSONPROMOTIONNAME", "promotion_name");
		define("JSONPROMOTIONAMOUNT", "promotion_product_amount");
		define("JSONPROMOTIONPRICE", "promotion_price");
		define("JSONCOSTLOGISTICSID", "logistics_id");
		define("JSONCOSTLOGISTICSCOST", "logistics_cost");
		define("JSONCOSTLOGISTICSBYID", "logistics_by_account_id");
		define("JSONCOSTADSID", "ads_id");
		define("JSONCOSTADSCOST", "ads_cost");
		define("JSONCOSTADSBYID", "ads_by_account_id");
		define("JSONDATETIME", "datetime");
		define("JSONDATETIMEFORMAT", "Y-m-d H:i:s");
		define("JSONORDERTRANSACTION","transaction_id");
		define("JSONORDERNAME","order_name");
		define("JSONORDERADDRESS","order_address");
		define("JSONORDERDIST","order_district");
		define("JSONORDERSUBDIST","order_subdistrict");
		define("JSONORDERZIPCODE","order_zipcode");
		define("JSONORDERPROV","order_province");
		define("JSONORDERTEL","order_telnumber");
		define("JSONORDERCOST","order_cost");
		define("JSONORDERLOGISTICID","order_logistics_id");
		define("JSONORDERDATETIME","order_datetime");
		define("JSONORDERPAYMENTID","order_payment_id");
		define("JSONORDERDESCRIPTION","order_description");
		define("JSONORDERTRACKING","order_tracking_id");
		define("JSONORDERSTATUS","order_status");
		define("JSONORDERCOUNTRY","order_country");
		define("JSONORDERBYACCOUNT","order_by_account_id");
		
    }

    public static function exec($request, $response)
	{
		if($request->getAttribute('has_errors')) {
			return $errors = $request->getAttribute('errors');
		}
		return null;
	}

	public static function validateCreateAccount()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONACCOUNTNAME => v::stringType()->length(5, 100)->notBlank(),
			JSONUSERNAME => v::stringType()->length(5, 20)->noWhitespace()->notBlank(),
			JSONPASSWORD => v::stringType()->length(5, 50)->noWhitespace()->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
			JSONACCOUNTROLE => v::intVal()->between('0', '1'),
		);
	}

	public static function validateEditAccount()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONID => v::intVal()->notBlank(),
			JSONACCOUNTNAME => v::stringType()->length(5, 100)->notBlank(),
			JSONUSERNAME => v::stringType()->length(5, 20)->noWhitespace()->notBlank(),
			JSONPASSWORD => v::stringType()->length(5, 50)->noWhitespace()->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
			JSONACCOUNTROLE => v::intVal()->between('0', '1'),
		);
	}

	public static function validateCreateGroup()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONGROUPNAME => v::stringType()->length(3, 100)->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
		);
	}

	public static function validateEditGroup()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONID => v::intVal()->notBlank(),
			JSONGROUPNAME => v::stringType()->length(3, 100)->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
		);
	}

	public static function validateAddGroupMember()
	{
		$self = New Self();
		return array(
			JSONGROUPID => v::intVal()->notBlank(),
			JSONACCOUNTID => v::intVal()->notBlank(),
			JSONGROUPROLE => v::intVal()->between('0', '2'),
		);
	}

	public static function validateDelGroupMember()
	{
		$self = New Self();
		return array(
			JSONGROUPID => v::intVal()->notBlank(),
			JSONACCOUNTID => v::intVal()->notBlank(),
		);
	}

	public static function validateCreateProduct()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONPRODUCTNAME => v::stringType()->length(3, 100)->notBlank(),
			JSONPRODUCTPRICE => v::intVal()->length(1, 10)->notBlank(),
			JSONPRODUCTGROUPID => v::intVal()->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
		);
	}

	public static function validateEditProduct()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONID => v::intVal()->notBlank(),
			JSONPRODUCTNAME => v::stringType()->length(3, 100)->notBlank(),
			JSONPRODUCTPRICE => v::intVal()->length(1, 10)->notBlank(),
			JSONPRODUCTGROUPID => v::intVal()->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
		);
	}

	public static function validateCreatePromotion()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONGROUPID => v::intVal()->notBlank(),
			JSONPRODUCTID => v::intVal()->notBlank(),
			JSONPROMOTIONNAME => v::stringType()->length(3, 100)->notBlank(),
			JSONPROMOTIONAMOUNT => v::intVal()->length(1, 10)->notBlank(),
			JSONPROMOTIONPRICE => v::intVal()->length(1, 10)->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
		);
	}

	public static function validateEditPromotion()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONID => v::intVal()->notBlank(),
			JSONGROUPID => v::intVal()->notBlank(),
			JSONPRODUCTID => v::intVal()->notBlank(),
			JSONPROMOTIONNAME => v::stringType()->length(3, 100)->notBlank(),
			JSONPROMOTIONAMOUNT => v::intVal()->length(1, 10)->notBlank(),
			JSONPROMOTIONPRICE => v::intVal()->length(1, 10)->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
		);
	}

	public static function validateAddLogisticsCost()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONGROUPID => v::intVal(),
			JSONPRODUCTID => v::intVal(),
			JSONCOSTLOGISTICSID => v::intVal()->notBlank(),
			JSONCOSTLOGISTICSCOST => v::intVal()->length(1, 10)->notBlank(),
			JSONCOSTLOGISTICSBYID => v::intVal()->notBlank(),
			JSONDATETIME => v::date(JSONDATETIMEFORMAT)->notBlank(),
		);
	}

	public static function validateEditLogisticsCost()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONID => v::intVal()->notBlank(),
			JSONGROUPID => v::intVal(),
			JSONPRODUCTID => v::intVal(),
			JSONCOSTLOGISTICSID => v::intVal()->notBlank(),
			JSONCOSTLOGISTICSCOST => v::intVal()->length(1, 10)->notBlank(),
			JSONCOSTLOGISTICSBYID => v::intVal()->notBlank(),
			JSONDATETIME => v::date(JSONDATETIMEFORMAT)->notBlank(),
		);
	}

	public static function validateAddAdsCost()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONGROUPID => v::intVal(),
			JSONPRODUCTID => v::intVal(),
			JSONCOSTADSID => v::intVal()->notBlank(),
			JSONCOSTADSCOST => v::intVal()->length(1, 10)->notBlank(),
			JSONCOSTADSBYID => v::intVal()->notBlank(),
			JSONDATETIME => v::date(JSONDATETIMEFORMAT)->notBlank(),
		);
	}

	public static function validateEditAdsCost()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONID => v::intVal()->notBlank(),
			JSONGROUPID => v::intVal(),
			JSONPRODUCTID => v::intVal(),
			JSONCOSTADSID => v::intVal()->notBlank(),
			JSONCOSTADSCOST => v::intVal()->length(1, 10)->notBlank(),
			JSONCOSTADSBYID => v::intVal()->notBlank(),
			JSONDATETIME => v::date(JSONDATETIMEFORMAT)->notBlank(),
		);
	}

	public static function validateAddOrder()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONORDERTRANSACTION => v::stringType()->length(6, 20)->notBlank(),
			JSONPRODUCTID => v::intVal(),
			JSONGROUPID => v::intVal(),
			JSONORDERNAME => v::stringType()->length(3, 100)->notBlank(),
			JSONORDERADDRESS => v::stringType()->length(3, 500)->notBlank(),
			JSONORDERDIST => v::stringType()->length(1, 50)->notBlank(),
			JSONORDERSUBDIST => v::stringType()->length(1, 50)->notBlank(),
			JSONORDERZIPCODE => v::intVal()->length(5, 5)->notBlank(),
			JSONORDERPROV => v::stringType()->length(1, 50)->notBlank(),
			JSONORDERDATETIME => v::date(JSONDATETIMEFORMAT)->notBlank(),
			JSONORDERPAYMENTID => v::intVal(),
			JSONORDERBYACCOUNT => v::intVal()->notBlank(),
		);
	}

	public static function validateEditOrder()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONID => v::intVal()->notBlank(),
			JSONORDERTRANSACTION => v::stringType()->length(6, 20)->notBlank(),
			JSONPRODUCTID => v::intVal(),
			JSONGROUPID => v::intVal(),
			JSONORDERNAME => v::stringType()->length(3, 100)->notBlank(),
			JSONORDERADDRESS => v::stringType()->length(3, 500)->notBlank(),
			JSONORDERDIST => v::stringType()->length(1, 50)->notBlank(),
			JSONORDERSUBDIST => v::stringType()->length(1, 50)->notBlank(),
			JSONORDERZIPCODE => v::intVal()->length(5, 5)->notBlank(),
			JSONORDERPROV => v::stringType()->length(1, 50)->notBlank(),
			JSONORDERDATETIME => v::date(JSONDATETIMEFORMAT)->notBlank(),
			JSONORDERPAYMENTID => v::intVal(),
			JSONORDERBYACCOUNT => v::intVal()->notBlank(),
		);
	}
}