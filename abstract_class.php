<?php
abstract class Abstract_class{

	protected function dbConnect(){
		
		$dbHost     = "localhost";
		$dbUserName = "root";
		$dbPassword = "";
		$dbDatabase = "atif";

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

}