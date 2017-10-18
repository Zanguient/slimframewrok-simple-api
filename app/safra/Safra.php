<?php

/**
 * @isSecure: true
 */
class Safra extends IApi {

	private $query = array();

	public function __construct(){
		
		parent::__construct();

	}

	public function getIndex($name){
		
		echo $name;
		
	}

	public function safraList(){
		
        $safraList = ORM::for_table('v_safra')->table_alias('v')
			->select('v.id')
			->select('v.id', 'id_safra')
			->select('v.descr')			
			->find_array();

            
		echo json_encode($safraList);
		
	}

}