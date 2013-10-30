<?php
require_once 'Db.php';

class TranzitNumber{

	public static function get($num, $country = 'US', $fields = ['bank_name' => 'bank_name']){
		$mysql = Db::get();

		$query = self::makeQuery($country, $fields);
		$stmt = $mysql->stmt_init();
		$stmt->prepare($query);
		$stmt->bind_param('s', $num);
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
		$query.=" from {$country}_tranzit_numbers where `num` = ? limit 1";
		return $query;
	}
};