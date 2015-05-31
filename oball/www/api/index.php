<?php
  if ($_GET['action'] == "gettournament") {
	  require_once('config.inc.php');
	 $name=$_GET['name'];
    $query=mysql_query('SELECT * FROM o_tournaments WHERE t_name="'.$name.'"');
	$num_query = mysql_num_rows($query);
	if ($num_query>0)
	{
		$row = mysql_fetch_array($query);
	}
	else
	{
		$row=array('error'=>'Совпадения не найдены');
	}
	$result=json_encode($row);
    echo $result;
  }
?>
