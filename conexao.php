<?php
/*
   * Wanderson Rodrigues Marques
   * Web Developer
   * https://github.com/wanderson-r-marques
*/
function conectar()
{
	// Conexão com o PDO
	try {
		define("DB_HOST", "50.116.87.69");
		define("DB_USER", "infor407_online");
		define("DB_PASS", "infor2525@");
		define("DB_NAME", "infor407_palamares_saude");

		$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $pdo;
	} catch (PDOException $e) {
		echo $e->getMessage() . '<hr>';
	}
}