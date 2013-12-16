<?php


require_once 'classes/Geo.php';
require_once 'classes/TranzitNumber.php';

$c = isset($_GET['c']) ? $_GET['c'] : 'jsonp';

//$country = $_SERVER['HTTP_HOST'];
$country = $_GET['country'];
$info = [];
$mode = $_GET['mode'];
switch ($mode) {
	case 'postcode':
		switch ($country) {	
			case 'uk':
				$fields = ["town" => "town", "county" => "county"];
				break;
			case 'ca':
				$fields = ["city" => "city", "province" => "province"];
				break;
			default:
				$fields = ["city" => "CityMixedCase", "state" => "State"];
				break;
		}
		$info = PostCode::get($_GET['postcode'], $country, $fields);
		break;
	case 'tranzit':

		$info = TranzitNumber::get($_GET['tranzitNumber'], $country);

		break;		
	default:
		break;
}



echo $c.'('.json_encode($info).');';







