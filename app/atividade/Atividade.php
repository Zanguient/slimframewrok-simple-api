<?php

/**
 * @isSecure: true
 */
class Atividade extends IApi {

	private $query = array();

	public function __construct(){
		
		parent::__construct();

	}

	public function getIndex($name){
		
		echo $name;
		
	}

	public function atividadeList(){
		
        $atividadesList = ORM::for_table('atividade')->table_alias('a')
			->select('a.id')
			->select('a.id', 'id_atividade')
			->select('a.id_pai')
			->select('a.id_tipo_atividade')
			->select_expr("CONCAT(p.descr,' ', a.descr)", 'descr')
			->select('a.ativo')
			
			->join('atividade', array('p.id', '=', 'a.id_pai'),'p')

			->where('ativo', 1)

			->order_by_asc('a.id')
			
			->find_array();

            
		echo json_encode($atividadesList);
		
	}

}