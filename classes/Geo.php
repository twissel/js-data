<?php



require_once 'Db.php';


class PostCode{


	public static function get($postCode, $country = 'us', $fields = ["city" => "CityMixedCase", "state" => "State"]){

		$mysql = Db::get();

		$query = self::makeQuery($country, $fields);
		$stmt = $mysql->stmt_init();
		$stmt->prepare($query);
		$stmt->bind_param('s', $postCode);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$row = $result->fetch_row();
		return Db::makeResult($fields, $row);

	}



	protected static function makeQuery($country, $fields){
		$query = "select ";
		foreach ($fields as $key => $value) {
			$query.="`{$value}` as {$key},";
		}
		$query = substr($query, 0, strlen($query)-1);
		$query.=" from {$country}_postcodes where `PostCode` = ? limit 1";
		return $query;
	}

	public static function getRu($postCode){
		$streets = self::findStreets($postCode);
		$uniqueParents = [];
		foreach ($streets as $street) {
			if(!in_array($street['PARENTGUID'], $uniqueParents)){
				$uniqueParents[] = $street['PARENTGUID'];
			}
		}
		$res = [];
		foreach ($uniqueParents as $parent) {
			$res[$parent] = self::fullRuStreetAddress($parent);
		}
		$return = [];
		foreach ($streets as $street) {
			$t = [0 => $street] + $res[$street['PARENTGUID']];
			$one = ["region" => $t[1]["REGIONCODE"] , "street" => $t[0]["FORMALNAME"]];
			if(isset($t[4])){
				$one['city'] = $t[4]["FORMALNAME"];
			}else if(isset($t[5])){
				$one['city'] = $t[5]["FORMALNAME"];
			}else if(isset($t[6])){
				$one['city'] = $t[6]["FORMALNAME"];
			}
			$return [] =  $one;
		}
		return $return;
	}

	public static function findStreets($postCode){
		$query = "select * from ru_postcodes where POSTALCODE = ? and ACTSTATUS = 1 and AOLEVEL in (7, 90, 91)  order by  FORMALNAME limit 100";
		$mysql = Db::get();
		$stmt  = $mysql->stmt_init();
		$stmt->prepare($query);
		$stmt->bind_param('s', $postCode);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$return = [];
		while ($row = $result->fetch_array()) {
			$return[] = $row;
		}
		return $return;
	}

	public static function fullRuStreetAddress($code){
	$row['AOLEVEL'] = 7;
	$row['PARENTGUID'] = $code;
	$msql = Db::get();
	$tree = [];

	$sql = 'SELECT AOGUID,FORMALNAME,SHORTNAME,PARENTGUID,AOLEVEL,OKATO,POSTALCODE
	      FROM ru_postcodes fa
	      WHERE fa.AOGUID=? AND fa.ACTSTATUS=1';
	while ($row['AOLEVEL'] !== 1) {
			$stmt  = $msql->prepare($sql);
			$stmt->bind_param('s', $row['PARENTGUID']);
			$stmt->execute();
		    $res = $stmt->get_result();
		    $row = $res->fetch_array();
		    $tree[$row['AOLEVEL']] = $row;
	}
	return $tree;
}

}
