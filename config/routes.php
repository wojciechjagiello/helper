<?php

$app->get('/', '\App\Controllers\MainController:index')->setName('home');

$app->get('/settings', '\App\Controllers\SettingsController:index')->setName('settings');
$app->post('/settings', '\App\Controllers\SettingsController:save')->setName('saveSettings');


$app->post('/logfullContent', '\App\Controllers\LogController:fullContent')->setName('logfullContent');
$app->post('/runBat', '\App\Controllers\BatController:run')->setName('runBat');


$app->get('/watcher', '\App\Controllers\LogController:watcher')->setName('watcher');
$app->post('/watcher', '\App\Controllers\LogController:watcher')->setName('saveWatcher');
$app->get('/check', '\App\Controllers\LogController:checkErrors')->setName('checkLogErrors');
$app->get('/error/{key}', '\App\Controllers\LogController:lastError')->setName('lastError');





//
//$app->get('/checkWatcher', '\App\Controllers\WatcherController:check')->setName('watcherCheck');
//$app->get('/log/{key}', '\App\Controllers\WatcherController:log')->setName('watcherLog');
//
//
