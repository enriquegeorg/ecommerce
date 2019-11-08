<?php

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new \Slim\Slim();

$app->config("debug", true);

$app->get("/", function() {
	$page=new Page();
	$page->setTpl("Index");
});
$app->get("/admin", function() {
	User::verifyLogin();
	$page=new PageAdmin();
	$page->setTpl("Index");
});
$app->get("/admin/login", function() {
	$page=new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("login");
});
$app->post("/admin/login", function() {
	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;
});
$app->get("/admin/logout", function() {
	User::logout();
	header("Location: /admin/login");
	exit;
});
$app->get("/admin/users", function() {
	User::verifyLogin();
	$users= User::listAll();
	$counter1=-1;
	if( isset($users) && ( is_array($users) || $users instanceof Traversable ) && sizeof($users) ) foreach( $users as $key1 => $value1 ){
		$counter1++;
		echo htmlspecialchars( $value1["iduser"], ENT_COMPAT, 'UTF-8', FALSE );
		echo "<br>";
		echo htmlspecialchars( $value1["desperson"], ENT_COMPAT, 'UTF-8', FALSE );
		echo "<br>";
		echo htmlspecialchars( $value1["desemail"], ENT_COMPAT, 'UTF-8', FALSE );
		echo "<br>";
		echo htmlspecialchars( $value1["deslogin"], ENT_COMPAT, 'UTF-8', FALSE );
		echo "<br>";
	}
	exit;
	$page=new PageAdmin();
	$page->setTpl("users", array(
		"users"=>$users
	));
});
$app->get("/admin/users/create", function() {
	User::verifyLogin();
	$page=new PageAdmin();
	$page->setTpl("users-create");
});
$app->get("/admin/users/:iduser/delete", function($iduser){
	User::verifyLogin();
});
$app->get("/admin/users/:iduser", function($iduser) {
	User::verifyLogin();
	$page=new PageAdmin();
	$page->setTpl("users-update");
});
$app->post("/admin/users/create", function(){
	User::verifyLogin();
});
$app->post("/admin/users/:iduser", function($iduser){
	User::verifyLogin();
});
$app->run();

 ?>
