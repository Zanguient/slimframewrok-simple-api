<?php

/**
 * @isSecure: true
 */
class Cultura extends IApi {

	private $query = array();

	public function __construct(){
		
		parent::__construct();

	}

	public function getIndex($name){
		echo $name;
	}

	/**/
	public function culturaList() {
		
        $culturasList = ORM::for_table('cultura')->table_alias('c')
			->select('c.id')
			->select('c.id_cultura')
			->select('c.descr')
			
			->find_array();

		echo json_encode($culturasList);
		
	}

}