<?php

use App\Controllers\IndexController;

$action = new IndexController();
//Запускаем разбор адресной строки
//Получаем нужное нам значение
$route = explode("/", $_SERVER['REQUEST_URI']);     
$route = str_replace(".php", "", $route);

//Главная страница
if($route[2] == "index" || $route[2] == ""){
    $action->SelectPage();
}

//Запускаем скрипт, для заполнения таблицы расписания schedule
if($route[2] == "script"){
    $action->FillingSchedule();
}

//Страница с выбором расписания по дате, шаблон с ajax
if($route[2] == "show"){
    $action->ShowSchedule();
}

//Страница с выбором расписания по дате
if($route[2] == "table"){
    $action->SelectSchedule();
}

//Страница с добавлением отправления, шаблон с ajax
if($route[2] == "add"){
    $action->AddSchedule();
}

//Форма ввода
if($route[2] == "form"){
    $action->FormSchedule();
}

//Добавление строки
if($route[2] == "insert"){
    $action->InsertSchedule();
}