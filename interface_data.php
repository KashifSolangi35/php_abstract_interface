<?php
interface Interface_data{
	public function insert_db();
	public function delete_db($queryStringId);
	public function getData($queryString);
}
