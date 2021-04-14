<?php
abstract class Abstract_class{

	/*********************************************************************************
	 * ------> GENERIC CLASS FOR DATABASE CRUD OPERATION - MYSQLi <-----------
	 *
	 * Author: MD Danish <trade.danish@gmail.com>
	 * Date Written: Jan 19, 2016
	 * Usage: $object = new db_api;
	 *        $object-> delete ( ... ) OR insert( ... ) etc...
	 ********************************************************************************/

	
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


	/***************************************************************
    Manual: function to retrieve the record from database
    $myfields = "first_name,last_name";
    $mytable = "iws_profiles";
    $where = "profile_id='1' AND is_verified=1";
    var_dump($this->select( $myfields , $mytable ,$where ) );
    Usage: $this->select($myfields,$mytable,$where);
    ***************************************************************/

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


	/**************************************************************
    Manual: function to update the record into database
    $fields = array(    'first_name'=>'MD','last_name'=>'Danish');
    $mytable = "iws_profiles";
    $where = "profile_id='1' AND is_verified=1";
    var_dump($this->update( $fields , $mytable ,$where ) );
    Usage: $this->update($fields,$mytable,$where);
    return : 0 on failure or 1 on success
    **************************************************************/


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

 	
 	/**************************************************************
    Manual: Function to delete the record from database
    $mytable = "iws_profiles";
    $where = "profile_id='4'";
    var_dump($this->delete( $mytable ,$where ) );
    Usage: $this->delete($mytable,$where);
    return : 0 on failure or 1 on success
    **************************************************************/


 	protected  function delete_db($table_name, $where)
    {
     	$resArray = [];    
        $query = "DELETE FROM $table_name WHERE 1";
        if (!empty($where)) {
            $query = "DELETE FROM $table_name WHERE $where";
        }
        $result = mysqli_query($connection, $query);
        return $result;
    }





    /***************************************************************
    Manual: function to insert the record from database
    $tbl_name = "iws_profiles";
    $a_data=array("username"=>"nalini","email"=>"nalini@gmail.com","mobile_no"=>"XXXX","zip_code"=>"500072");
    var_dump($this->insert( $myfields , $mytable ) );
    Usage: $this->insert($myfields,$mytable);
    return  : 0 on failure or 1 on success
    ***************************************************************/
    function insert($a_data, $tbl_name)
    {
        global $connection;
        $fields = array_keys($a_data);
        $sql    = "INSERT INTO " . $tbl_name . " (`" . implode('`,`', $fields) . "`) VALUES('" . implode("','", $a_data) . "')"; //echo $sql; die();
        $result = mysqli_query($connection, $sql);
        return $result;
    }


    /***************************************************************
    Manual: function to execute given query
    var_dump($this->custom( "Select count(*) as Number_Of_Stars from Sky" ) );
    return  : 0 on Failure Or No Data And Array on Operation Success
    ***************************************************************/
    function custom($query) {
        global $connection;
        $result = mysqli_query($connection, $query);
      
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;				
            }			 
            return $data;
        } else {
            return 0;
        }
    }







}