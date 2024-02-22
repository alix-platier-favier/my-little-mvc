<?php

require_once 'vendor/autoload.php';
session_start();

$router = new AltoRouter();
$router-> setBasePath('/pwd');

use App\Controller;
use App\Model;
use App\View;
use App\Controller\AuthentificationController;
use App\Controller\ShopController;
use App\Model\User;




// RENDERING
$router->map('GET', '/register', function(){
    require_once 'View/register.php';
}, 'register');

$router->map('GET', '/login', function(){
    require_once 'View/login.php';
}, 'login');

$router->map('GET', '/', function(){
    require_once 'View/home.php';
    echo "Hello Homepage";
}, 'home');

$router->map('GET', '/product', function(){
    require_once 'View/product.php';
    echo "Hello Products list";
}, 'product');

$router->map('GET', '/product/[i:id_product]', function($id_product){ // voir si on peut renvoyer des data et pas une View
    require_once 'View/product.php';
    echo "Hello Product ID: $id_product";
}, 'productId');

$router->map('GET', '/shop', function(){
    require_once 'View/shop.php';
}, 'shop');

$router->map('GET', '/profile', function(){
    require_once 'View/profile.php';
}, 'profile');


// AUTH
$router->map('POST', '/register', function(){
    $auth = new AuthentificationController();
    $auth->register($_POST['fullname'], $_POST['email'], $_POST['password']);
}, 'register_post');


$router->map('POST', '/login', function(){
    $auth = new AuthentificationController();
    $auth->login($_POST['email'], $_POST['password']);
}, 'login_post');


$router->map('POST', '/profile', function(){
    $auth = new AuthentificationController();
    $auth->updateProfile($_POST['fullname'], $_POST['email'], $_POST['oldPassword'], $_POST['newPassword']);
}, 'profile_post');

// ADMIN

$router->map('GET', '/admin/users/list', function(){ // voir si on peut renvoyer des data et pas une View
    $findUsers = new User();
    var_dump($findUsers->findAll());
    // require_once 'View/admin-test.php';
}, 'users_list');

$router->map('GET', '/admin/users/show/[i:id]', function($id){ // voir si on peut renvoyer des data et pas une View
    $findUserById = new User();
    // $toto = $findUserById->findOneById($id);
    $findUserById->findOneById($id);
    require_once 'View/admin-test.php';
}, 'user_by_id');

$match = $router->match();

// call closure or throw 404 status
if( is_array($match) && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] );
} else {
	// no route was matched
	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}