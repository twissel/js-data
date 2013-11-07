<?php



require_once 'Db.php';


class PostCode{


	public static function get($postCode, $country = 'us', $fields = ["city" => "CityMixedCase", "state" => "State"]){

		$mysql = Db::get();

		$query = self::makeQuery($country, $fields);
		$stmt = $mysql->stmt_init();
		$stmt->prepare($query);
		var_dump($stmt->bind_param('s', $postCode));
		var_dump($stmt->execute());
		var_dump((string)$stmt);
		$result = $stmt->get_result();
		var_dump($result);
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

}
