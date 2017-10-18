<?php

class Talhao {

	private $query = array();

	public function __construct(){

		// Query for cost by plot
		$this->query['queryByPlot'] = file_get_contents(BASEDIR.'/talhao/query_by_plot.sql');

		// Query for cost by plot
		$this->query['queryPlotDetail'] = file_get_contents(BASEDIR.'/talhao/query_plot_detail.sql');

		// Query for cost by plot
		$this->query['queryFarmAverage'] = file_get_contents(BASEDIR.'/talhao/query_average.sql');
	}

	public function getQuery($query){
		return $this->query[$query];
	}


	public function getIndex($name){
		echo $name;
	}

	public function getAll(){

	}

	// JS way to call:
	// ../api/talhao/getcostbyplot/5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20/1
	public function getCostByPlot($id_fazenda, $id_cultura, $id_talhao, $id_safra){

		$query = $this->getQuery('queryByPlot');
		$query = str_replace(array('$id_fazenda', '$id_cultura', '$id_talhao', '$id_safra'),
												 array($id_fazenda  , $id_cultura   , $id_talhao ,  10 ), $query);

	    $resultado = result($query);

	    $json = array();
		while($r = $resultado->fetch_object()) {
			$json[] = $r;
		}

		echo json_encode($json);
	}



	// JS way to call:
	// ../api/talhao/getplotdetails/7/1/8/10
	public function getPlotDetails($id_fazenda, $id_cultura, $id_talhao, $id_safra){

		if($id_cultura == 0){
			$id_cultura = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20';
		}

		$query = $this->getQuery('queryPlotDetail');
		$query = str_replace(array('$id_fazenda', '$id_cultura', '$id_talhao', '$id_safra'),
												 array($id_fazenda  , $id_cultura   , $id_talhao ,  $id_safra ), $query);

	  $resultado = result($query);

	  $json = array();
		while($r = $resultado->fetch_object()) {
			$json[] = $r;
		}

		// CASO NAO ENCONTRE NADA NA CULTURA INFORMADA, FAZ UMA BUSCA GERAL
		if(!$json){

			$id_cultura = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20';

			$query = $this->getQuery('queryPlotDetail');
			$query = str_replace(array('$id_fazenda', '$id_cultura', '$id_talhao', '$id_safra'),
													 array($id_fazenda  , $id_cultura   , $id_talhao ,  $id_safra ), $query);

		  $resultado = result($query);

		  $json = array();
			while($r = $resultado->fetch_object()) {
				$json[] = $r;
			}

		} // END IF

		$result = ($json
			? array('success' => true , 'data' => $json)
			: array('success' => false, 'data' => 'Consulta não retornou nenhum valor.')
		);

		echo json_encode($result);
	}



	// JS way to call:
	// ../api/talhao/getplotdetails/7/1/8/10
	public function getFarmAverage($id_fazenda, $id_safra, $id_cultura){

		if($id_cultura == 0){
			$id_cultura = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20';
		}

		$query = $this->getQuery('queryFarmAverage');
		$query = str_replace(array('$id_fazenda', '$id_safra', '$id_cultura'),
												 array($id_fazenda  , $id_safra   , $id_cultura ), $query);

	    $resultado = result($query);

	    $json = array();
		while($r = $resultado->fetch_object()) {
			$json[] = $r;
		}

		$result = ($json
					? array('success' => true , 'data' => $json)
					: array('success' => false, 'data' => 'Consulta não retornou nenhum valor.')
				);

		echo json_encode($result);
	}


}
