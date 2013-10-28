<?php
	
	// Disable on live
	error_reporting( E_ALL );
	
	define('ACCESS', TRUE);
	include('config.php');
	
	$data = array();
	
	if(!mysql_select_db($database, mysql_connect($host, $user, $password)))
	{
		$data['success'] = false;
		$data['timestamp'] = time();
		$data['error'] = 'A database error occurred.';
	}
	else
	{
		$query = "SELECT * from `" . $_GET["type"] ."` ";
		if(isset($_GET["order"]))
				$query .= "ORDER BY `" . $_GET["type"] . "`.`". $_GET["order"] ."` ASC";

		if($execute = mysql_query($query))
		{
			$data['success'] = true;
			$data['timestamp'] = time();
			$data['type'] = $_GET["type"];
			$data['data'] = array();
		
			if($_GET["type"] == "pipeline")
			{
				$i = 0;
				while($row = mysql_fetch_array($execute))
				{
					// First 4 rows of the data are information, not data.
					if($row["id"] > 4)
					{
						$arr = array();
						
						foreach( $row as $key => $value ) {
							if(!is_numeric($key))
								$arr[$key] = $value;
						}

						$data["data"][] = $arr;

					}
				}
			}
			elseif($_GET["type"] == "g-cloud" || $_GET["type"] == "construction")
			{
				while($row = mysql_fetch_array($execute))
				{
					$arr = array();
					
					foreach( $row as $key => $value ) {
						if(!is_numeric($key))
							$arr[$key] = $value;
					}
						
					$data["data"][] = $arr;
				}
			}
		}
		else
		{
			$data['success'] = false;
			$data['timestamp'] = time();
			$data['error'] = 'Invalid parameters.';
		}
	}
	
	print json_encode($data);
	
	mysql_close($connect);
?>