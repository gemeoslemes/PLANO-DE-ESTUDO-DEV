<?php 

use Pecee\SimpleRouter\SimpleRouter;
use system\core\Helpers;

try{
    SimpleRouter::setDefaultNamespace('system\Controllers');


SimpleRouter::get(URL_SITE, 'SiteController@index');
SimpleRouter::get(URL_SITE.'sobre-nos', 'SobreController@sobre');
SimpleRouter::get(URL_SITE.'post/{id}', 'SiteController@post');
SimpleRouter::get(URL_SITE.'categoria/{id}', 'SiteController@categoria');
SimpleRouter::post(URL_SITE.'buscar', 'SiteController@buscar');
SimpleRouter::get(URL_SITE.'404', 'SiteController@error404');

SimpleRouter::group(['namespace' => 'Admin'], function(){
    // Rotas de DASHBOARD
    SimpleRouter::get(URL_ADMIN.'dashboard', 'AdminDashboardController@dashboard');
    
    //Rota de POSTS
    SimpleRouter::get(URL_ADMIN.'posts/listar', 'AdminPosts@listar');
    SimpleRouter::match(['get','post'], URL_ADMIN.'posts/cadastrar', 'AdminPosts@cadastrar');
    SimpleRouter::match(['get','post'], URL_ADMIN.'posts/editar/{id}', 'AdminPosts@editar');
    SimpleRouter::get(URL_ADMIN.'posts/deletar/{id}', 'AdminPosts@deletar');

    //Rotas de CATEGORIAS
    SimpleRouter::get(URL_ADMIN.'categorias/listar', 'AdminCategorias@listar');
    SimpleRouter::match(['get','post'], URL_ADMIN.'categorias/cadastrar','AdminCategorias@cadastrar');
    SimpleRouter::match(['get','post'], URL_ADMIN.'categorias/editar/{id}','AdminCategorias@editar');
    SimpleRouter::get(URL_ADMIN.'categorias/deletar/{id}','AdminCategorias@deletar');
}); 

SimpleRouter::start();
} catch(Pecee\SimpleRouter\Exceptions\NotFoundHttpException $ex) {
    Helpers::redirecionar('404');
}