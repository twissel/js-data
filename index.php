<!DOCTYPE html>
<html>
<head>
	<META http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
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
			case 'ru':
				$info = PostCode::getRu($_GET['postcode']);
				die();
				break;
			case 'uk':
				$fields = ["town" => "town", "county" => "county"];
				$info = PostCode::get($_GET['postcode'], $country, $fields);
				break;
			case 'ca':
				$fields = ["city" => "city", "province" => "province"];
				$info = PostCode::get($_GET['postcode'], $country, $fields);
				break;
			default:
				$fields = ["city" => "CityMixedCase", "state" => "State"];
				$info = PostCode::get($_GET['postcode'], $country, $fields);
				break;
		}
		break;
	case 'tranzit':

		$info = TranzitNumber::get($_GET['tranzitNumber'], $country);

		break;		
	default:
		break;
}



echo $c.'('.json_encode($info).');';
?>
</body>
</html>







