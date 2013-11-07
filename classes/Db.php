<?php

class Db{
	protected static $mysql = null;
	protected static $host  = null;
	protected static $user  = null;
	protected static $password = null;
	protected static $db    = null;

	protected static function init(){
		if(!self::$mysql){
			self::readConfig();
			echo '1';
			self::$mysql = new mysqli(self::$host, self::$user, self::$password, self::$db);
			echo '2';
			if(!self::$mysql){
				die('Failed to connect');
			} 
		}
	}

	public static  function readConfig(){
		$file = "./conf/db.ini";
		$data = parse_ini_file($file);
		self::$user = $data['user'];
		self::$host = $data['host'];
		self::$password = $data['password'];
		self::$db = $data['db'];	
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