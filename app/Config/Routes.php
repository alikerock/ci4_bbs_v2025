<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');

//Board
$routes->get('/board', 'Board::list');
$routes->get('/boardWrite', 'Board::write'); //글쓰는 페이지
$routes->match(['GET','POST'],'writeSave', 'Board::save'); //글저장
$routes->get('/boardView/(:num)', 'Board::view/$1');
$routes->get('/modify/(:num)', 'Board::modify/$1');
$routes->get('/delete/(:num)', 'Board::delete/$1');
$routes->post('/save_image', 'Board::save_image');
$routes->post('/file_delete', 'Board::file_delete');



//Member 회원관련
$routes->get('/login', 'Member::login'); //로그인 페이지로 이동
$routes->get('/register', 'Member::register'); //회원가입 페이지로 이동
$routes->get('/logout', 'Member::logout'); //로그아웃
$routes->match(['GET','POST'],'loginOK', 'Member::loginok'); //로그인 절차 진행
$routes->match(['GET','POST'],'registerOK', 'Member::registerok'); //회원가입 절차 진행
