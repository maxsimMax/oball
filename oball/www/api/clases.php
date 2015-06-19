<?php
	//в этом файле содержатся все классы и методы api
	class db{ 
		//отдельный клас для работы с базой данных
		protected $base;
		
		function db_connect($DB_HOST="localhost",$DB_USER="root",$DB_PASS="")
		{
			if (!$conn = mysql_connect($DB_HOST,$DB_USER,$DB_PASS)) {
				return json_encode(array('error'=>'Cant connect to database '.$DB_NAME));
			}
			else{
				$this->base=$conn;
			}
		}
		
		function db_select($DB_NAME="")
		{
			if (!mysql_select_db($DB_NAME))
			{
				return json_encode(array('error'=>'Cant select database '.$DB_NAME));
			}
		}
		
		function db_disconnect()
		{
			if (!mysql_close($this->base))
			{
				return json_encode(array('error'=>'Cant close connection '.$this->base));
			}
		}
	}
	
	class api_functions{//класс api в котором находятся все методы
		
		function error_message($error='unknow error')//функция возвращает ошибку и прекращает выполнения скрипта
		{
			return json_encode(array("error:".$error));
			exit;
		}
		
		
		function get_tournament($par1)//поиск турнира по названию
		{
			$name=mysql_real_escape_string($par1['name']);
			if ($name=="")//если переданный параметр пустой то возвращаем ошибку
			{
				return $this->error_message('не получено название турнира');
			}
			else{
				$query=mysql_query('SELECT t_id,t_uid,t_type,t_name,t_city,t_game_name,t_description,t_params,t_rating,t_created FROM o_tournaments WHERE t_name LIKE "%'.$name.'%" OR t_description LIKE "%'.$name.'%"');
				if (mysql_error()!='')//если ошибка в запросе то выводим ее
				{
					return $this->error_message(mysql_error());
				}
				else{
					if (mysql_num_rows($query)>0)
					{
						$tournaments=array();
						$i=0;
						while ($row=mysql_fetch_assoc($query))
						{
							$tournaments[$i]=$row;
							$i++;
						}
						return json_encode($tournaments);//возвращаем Первый найденый турнир
					}
					else
					{
						return $this->error_message('no matches found');//или ошибку если ничего не нашли
					}
				}
			}
		}
		
		function get_tournament_by_id($par1){//поиск турнира по ID и игр к нему
			$id=mysql_real_escape_string($par1['id']);
			if ($id==""){
				return $this->error_message('не получен id турнира');
			}
			else
			{
				$query=mysql_query('SELECT t_id,t_uid,t_type,t_name,t_city,t_game_name,t_description,t_params,t_rating,t_created FROM o_tournaments WHERE t_id='.$id);//сначала находим турнир по его ID
				if (mysql_error()!='')
				{
					return $this->error_message(mysql_error());
				}
				else
				{
					if (mysql_num_rows($query)>0)//если турнир с таким ID существует то начинаем искать игры
					{
						$row=mysql_fetch_assoc($query);
						$games=array();
						$games[0]=$row;//записываем данные о турнире в массив
						switch($row['t_type'])//тут использовал оператор switch чтобы не использовать if лишний раз надеюсь так можно делать
						{
							case 2: //если тип_турнира=2 тогда берем игры из таблицы o_games
								$query=mysql_query('SELECT * FROM o_games WHERE o_games.trn_id='.$id);
								if (mysql_num_rows($query)>0)
								{
									$i=1;
									while ($row=mysql_fetch_assoc($query))
									{
										$games[$i]=$row;//записываем игры в массив
										$i++;
									}
								}break;
							case 3://если тип_турнира=3 или 4 тогда берем игры из таблицы o_games_e
							case 4:
								$query=mysql_query('SELECT * FROM o_games_e WHERE trn_id='.mysql_real_escape_string($id));
								if (mysql_num_rows($query)>0)
								{
									$i=1;
									while ($row=mysql_fetch_assoc($query))
									{
										$games[$i]=$row;
										$i++;
									}
								} break;
						}
						return json_encode($games);
					}
					else
					{
						return $this->error_message('турнир с таким id не найден');//если турнира с таким ID нету тогда возвратить ошибку
					}
				}
			}
		}

		function get_game($par1){
			$id=$par1['id'];
			if ($id=="")//если переданный параметр пустой то возвращаем ошибку
			{
				return $this->error_message('не получен id игры');
			}
			else{
				$query=mysql_query('SELECT * FROM o_games WHERE id='.$id);
				if (mysql_error()!='')//если ошибка в запросе то выводим ее
				{
					return $this->error_message(mysql_error());
				}
				else{
					if (mysql_num_rows($query)>0)
					{
						return json_encode(mysql_fetch_assoc($query));//возвращаем найденую игру
					}
					else
					{
						return $this->error_message('no matches found');//или ошибку если ничего не нашли
					}
				}
			}
		}
		function update_game($par1){
			$id=json_decode($par1)->id;
			if ($id=="")//если переданный параметр пустой то возвращаем ошибку
			{
				return $this->error_message('не получен id игры');
			}
			else{
				$query=mysql_query('SELECT * FROM o_games WHERE id='.$id);
				if (mysql_error()!='')//если ошибка в запросе то выводим ее
				{
					return $this->error_message(mysql_error());
				}
				else{
					if (mysql_num_rows($query)>0)
					{
						return json_encode(mysql_fetch_assoc($query));//возвращаем найденую игру
					}
					else
					{
						return $this->error_message('no matches found');//или ошибку если ничего не нашли
					}
				}
			}
		}
	}
?>