<?php
	//в этом файле содержатся все классы и методы api
	class db{//отдельный клас для работы с базой данных
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
			$name=json_decode($par1)->name;
			if ($name=="")//если переданный параметр пустой то возвращаем ошибку
			{
				return $this->error_message('you must enter a value');
			}
			else{
				$query=mysql_query('SELECT t_id,t_uid,t_type,t_name,t_city,t_game_name,t_description,t_params,t_rating,t_created FROM o_tournaments WHERE t_name LIKE "%'.mysql_real_escape_string($name).'%"');
				if (mysql_error()!='')//если ошибка в запросе то выводим ее
				{
					return $this->error_message(mysql_error());
				}
				else{
					if (mysql_num_rows($query)>0)
					{
						return json_encode(mysql_fetch_array($query));//возвращаем Первый найденый турнир
					}
					else
					{
						return $this->error_message('no matches found');//или ошибку если ничего не нашли
					}
				}
			}
		}
		
		function get_tournament_by_id($par1){//поиск турнира по ID и игр к нему
			$id=json_decode($par1)->id;
			if ($id==""){
				return $this->error_message('you must enter a value');
			}
			else
			{
				$query=mysql_query('SELECT t_id, t_type FROM o_tournaments WHERE t_id='.mysql_real_escape_string($id));//сначала находим турнир по его ID
				if (mysql_error()!='')
				{
					return $this->error_message(mysql_error());
				}
				else
				{
					if (mysql_num_rows($query)>0)//если турнир с таким ID существует то начинаем искать игры
					{
						$row=mysql_fetch_array($query);
						switch($row['t_type'])//тут использовал оператор switch чтобы не использовать if лишний раз надеюсь так можно делать
						{
							case 2: //если тип_турнира=2 тогда берем игры из таблицы o_games
								$query=mysql_query('SELECT * FROM o_games WHERE trn_id='.mysql_real_escape_string($id));
								if (mysql_num_rows($query)>0)
								{
									return json_encode(mysql_fetch_array($query));
								}
								else
								{
									return $this->error_message('no matches found');
								} break;
							case 3://если тип_турнира=3 или 4 тогда берем игры из таблицы o_games_e
							case 4:
								$query=mysql_query('SELECT * FROM o_games_e WHERE trn_id='.mysql_real_escape_string($id));
								if (mysql_num_rows($query)>0)
								{
									return json_encode(mysql_fetch_array($query));
								}
								else
								{
									return $this->error_message('no matches found');
								} break;
							default:	return $this->error_message('nothing');//в случае если тип турнира не подходит надо что-то вернуть
						}
					}
					else
					{
						return $this->error_message('no matches found');//если турнира с таким ID нету тогда возвратить ошибку
					}
				}
			}
		}
	}
?>