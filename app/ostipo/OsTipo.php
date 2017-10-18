<?php

/**
 * @isSecure: true
 */
class OsTipo extends IApi {

	private $query = array();

	public function __construct(){
		
		parent::__construct();

	}

	public function getIndex($name){
		
		echo $name;
		
	}

	public function osTipoList(){
		
        $osTipoList = ORM::for_table('tipo_ordem_servico')->table_alias('tos')
			->select('tos.id')
			->select('tos.id_tipo_ordem_servico')
			->select('tos.descr')
			->find_array();

            
		echo json_encode($osTipoList);
		
	}

}