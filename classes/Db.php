<?php

class Db{
	protected static $mysql = null;

	protected static function init(){
		if(!self::$mysql){
			self::$mysql = new mysqli('localhost', 'root', '', 'geo');
			if(!self::$mysql){
				die('Failed to connect');
			} 
		}
	}

	public static function get(){
		self::init();
		return self::$mysql;
	}

	public static function makeResult($fields, $row){
		$indxs = array_keys($fields);
		$j = 0;
		$res = [];
		foreach ($indxs as $indx) {
			$res[$indx] = $row[$j];
			$j++;
		}
		return $res;
	}
};