<?php
use think\facade\Route;

Route::get('login', 'login/login');


// 一般路由
Route::get('test2', 'test/test2');

// 路由加动态参数 back/test3/99 路由到 back/test/test3/name/99
Route::get('test3/:name', 'test/test3');

// Route::view('test4', 'test/test4', ['city'=>'shanghai']);

// 路由到闭包
Route::get('test5', function () {
    return 'hello,world!';
});
