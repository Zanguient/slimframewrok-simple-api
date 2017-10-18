<?php

/**
 * @isSecure: true
 */
class Fazenda extends IApi {

	private $query = array();

	public function __construct(){
		
		parent::__construct();

	}

	public function getIndex($name){
		
		echo $name;
		
	}

	public function fazendaList(){
		
        $fazendaList = ORM::for_table('fazenda')->table_alias('f')
			->select('f.id')
			->select('f.id_fazenda')
			->select('f.id_empresa')
            ->select('f.nome')
            ->select('f.area')
            ->select('f.pesagem_automatica')
			->find_array();

            
		echo json_encode($fazendaList);
		
	}

}