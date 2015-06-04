<?php
	//из этого файла происходит вызов функциq api
	include 'clases.php';//подключаем файл с api 
	$action=$_GET['action'];//получаем название метода которое надо и записываем в переменную action
	
	$database= new db();//подключаем базу данных
	$database->db_connect("localhost","root","","oballru");
	$database->db_select(oballru);
	
	$api=new api_functions();//тут мы вызываем нужный метод
	if (method_exists($api,$action))//если он существует
	{
		echo $api->$action($_GET['par']);//так как параметров у каждого метода может быть разное кол-во я решил что лучше их передавать в формате json и уже внутри метода разбирать на переменные
	}
	else
	{
		echo json_encode(array('error'=>'method '.$action.'is not exists'));//если пользователь хочет метод которого нету то ошибка
	}
	$database->db_disconnect();//отключить базу
?>