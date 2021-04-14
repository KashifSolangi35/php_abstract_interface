<?php
require_once('abstract_class.php');
require_once('interface_data.php');

class Staff extends Abstract_class implements Interface_data
{

    public function __construct()
    {
        // attemp database connection
        $res = $this->dbConnect();
        if( $res['res'] != 200){
            echo 'Database? We have a problem';
            die;
        }
    }
    
    public function insert_db()
    {
        // Insert Code goes here...
    }

    public function delete_db($queryStringId)
    {
        // delete code goes here
    }

    public function getData($queryString)
    {
        // get data interface mathod
        $query ="SELECT * FROM staff_registered ";
        if($queryString != ''){
            $query .= " WHERE " . $queryString . " ";
        }

        $query .= " ORDER BY id DESC LIMIT 200";

        $staff_list = $this->getResult($query);

        return $staff_list;
    }
}

