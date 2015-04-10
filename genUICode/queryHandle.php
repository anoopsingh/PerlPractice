<?php

		function executeQuery( $sql ) {

			include_once('include/config.php');
			$dbHost   = constant("DB_HOST");
			$user       = constant("DB_USERNAME");
			$pass      = constant("DB_PASSWORD");
			$dbName  = constant("DB_NAME");

			$conn = mysql_connect($dbHost, $user, $pass);
			if(! $conn )
			{
				die('Could not connect: ' . mysql_error());
			}
			mysql_select_db($dbName);
			$retval = mysql_query( $sql, $conn );
			$numOfAffectedRows = mysql_affected_rows();
			echo "Total num of rows affected is $numOfAffectedRows \n <br>";
			if(! $retval )
			{
				die('Could not get data: ' . mysql_error());
			} else if ( $numOfAffectedRows == 1 ) {
				processResult( $retval );
				mysql_close($conn);
				$roleId = $GLOBALS['roleId'];
				echo "role id is $roleId \n";
				return $roleId;
			} else {
				echo "Wrong username or password";
				return 0;
			}
		}

		function processResult ( $result ) {
           
			while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				echo "id :{$row['id']}  <br> ".
						"date: {$row['creationDate']} <br> ".
						"Status: {$row['status']} <br> ".
						"Role Id : {$row['roleId']} <br> ".
							"--------------------------------<br>";
				$GLOBALS['roleId'] =  $row['roleId'];
			} 
			
		}
?>
