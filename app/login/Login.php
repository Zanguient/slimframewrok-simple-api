<?php

/**
 * @isSecure: true
 */
class Login extends IApi {

	private $query = array();

	public function __construct(){
		
		parent::__construct();
		// Query for cost by plot
		$this->query['queryLogin'] = file_get_contents(BASEDIR.'/login/query_by_plot.sql');

	}

	public function getIndex($name){
		
		echo $name;
		
	}

	/**
	* @param: foo bar
	* @return: baz
	*/
	public function posLogin(){
		
		$params = $this->getParamsAsArray();
		$params['senha'] = md5($params['senha']);

		$usuario = ORM::for_table('usuario')->table_alias('u')
			->select('u.*')
			->select('f.*')
			->select('f.razao_social', 'nome')
		 	->join('funcionario', array('u.id_funcionario', '=', 'f.id'), 'f')
			->where($params)
			->find_array();
		
		$return = (
			count($usuario) > 0 ? 
			$this->prepareObj($usuario[0], true, 'usuario') : 
			$this->prepareObj()
		);

		$this->xp($return);

	}

	/**
	* @param: foo bar
	* @return: baz
	*/
	public function getLogout($session){

		var_dump($_SESSION[USERSS]);

	}


}
	