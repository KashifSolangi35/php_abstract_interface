<?php
abstract class Abstract_class{

	protected function dbConnect(){
		
		$dbHost     = "localhost";
		$dbUserName = "root";
		$dbPassword = "";
		$dbDatabase = "";

		// establish a database connection
		if(!isset($GLOBALS['dbConntion'])){
			$GLOBALS['dbConntion'] = new mysqli($dbHost,$dbUserName, $dbPassword,$dbDatabase);
		}

		// if an error occured, display error
		if( mysqli_connect_errno()){
			// if an error occured, raise it.
			$resArray['res'] = 500;
			$resArray['msg'] = 'Mysql error: ' . mysqli_connect_errno() . ' ' . mysqli_connect_errno();
		}else {
			// connection sucess
			$resArray['res'] = 200;
			$resArray['msg'] = 'Database connect succesful.';
		}

		return $resArray;
	}



	protected function getResult($queryString){
		// execute db query
		$qeuryResult=$GLOBALS['dbConntion']->query($queryString);
		$resArray = [];
		// if error occured. raised it.
		if( isset($GLOBALS['dbConntion']->errno) && ($GLOBALS['dbConntion']->errno != 0)){
			// if error occured. raised it.
			$resArray['res'] = 500;
			$resArray['msg'] = 'Internal server error. MySQL error: ' . $GLOBALS['dbConntion']->errno;

		} else {
			// success
			$rowCount = $qeuryResult->num_rows;
			if ($rowCount != 0){
				// move result set to an associative array
				$rsArray = $qeuryResult->fetch_all(MYSQLI_ASSOC);
				
				// add array set
				$resArray['res'] =200;
				$resArray['msg'] ='Success';
				$resArray['dataArray'] =$rsArray;

				// Free result set
				$qeuryResult->free_result();

			} else {
				// no data returned
				$resArray['res']=204;
				$resArray['msg'] ='Query did not return any results.';

			}

		}
		return $resArray;

	}



	protected function update_db($table, $data, $where)
	{
		$resArray = [];
		if( isset($GLOBALS['dbConntion']->errno) && ($GLOBALS['dbConntion']->errno != 0))
		{
			// if error occured. raised it.
			$resArray['res'] = 500;
			$resArray['msg'] = 'Internal server error. MySQL error: ' . $GLOBALS['dbConntion']->errno;

		} 
		else 
		{
			try {
				
			    
			    $query='UPDATE `'.$table.'` SET ';
			    foreach($data as $key => $value)
			    {
			        $query .= '`'.$key.'`=:'.$key.','; 
			    }
			    $query = substr($query, 0, -1);
			    $query .= ' WHERE ';
			    foreach($where as $key => $value)
			    {
			        $query .= '`'.$key.'`=:'.$key.','; 
			    }
			    $query = substr($query, 0, -1);

			    $data += $where;

			    
			    $update = $GLOBALS['dbConntion']->prepare($query);
			    $update->execute($data);


			    $resArray['res'] =200;
				$resArray['msg'] ='Successfully updated!';
				 



			}
			catch (Exception $e) {
			   
			    $resArray['res'] =204;
				$resArray['msg'] = $e->getMessage();
			}	

		}


		return $resArray;

	}




}