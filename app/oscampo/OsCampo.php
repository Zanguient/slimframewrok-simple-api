<?php

/**
 * @isSecure: true
 */
class OsCampo extends IApi {

	private $query = array();

	public function __construct(){
		
		parent::__construct();

	}

	public function getIndex($name){
		
		echo $name;
		
	}

	public function osList(){
		
        $listOs = ORM::for_table('ordem_servico')->table_alias('os')
			->select('os.id')
			->select('os.id', 'id_real')
			->select('os.id_fazenda')
			->select('os.id_ordem_servico')
			->select('f.nome', 'fazenda_nome')
			->select('os.id_safra')
			->select('vs.descr', 'safra')
			->select('os.id_tipo_ordem_servico')
			->select('tos.descr', 'tipo_os')
			->select('os.id_cultura')
			->select('c.descr', 'cultura')
			->select('os.id_fase')
			->select('fs.descr', 'fase')
			->select('os.id_atividade')
			->select('a.descr', 'atividade')
			->select('os.id_subatividade')
			->select('s.descr', 'subatividade')
			->select_expr("date_format(os.dt_abertura, '%d/%m/%Y')", 'dt_abertura')
			->select_expr("date_format(os.dt_fechamento, '%d/%m/%Y')", 'dt_fechamento')
		 	
            ->join('fazenda', array('f.id', '=', 'os.id_fazenda'),'f')
            ->join('v_safra', array('vs.id', '=', 'os.id_safra'),'vs')
            ->join('tipo_ordem_servico', array('tos.id', '=', 'os.id_tipo_ordem_servico'),'tos')
            ->join('cultura', array('c.id', '=', 'os.id_cultura'),'c')
            ->join('fase', array('fs.id', '=', 'os.id_fase'),'fs')
            ->join('atividade', array('a.id', '=', 'os.id_atividade'),'a')
            ->join('atividade', array('s.id', '=', 'os.id_subatividade'),'s')
			
            ->where('id_safra', 13)
            ->where('id_fazenda', 7)
			->find_array();
            //foreach($listOs as &$os) {
			//
            //    $os['os_talhao'] = ORM::for_table('ordem_servico_talhao')->where('id_ordem_servico', $os['id'])->find_array();
			//
            //}

            
		echo json_encode($listOs);
		
	}

	public function osItemList() {

		$osItemList = array();

        $listOs = ORM::for_table('ordem_servico')->table_alias('os')
			->select('os.id')
			
            ->where('id_safra', 13)
            ->where('id_fazenda', 7)
			->find_array();
			
		$osItemList = ORM::for_table('ordem_servico_talhao')
			->select('id')
			->select('id', 'id_ordem_servico_item')
			->select('item')
			->select('id_ordem_servico')
			->select('id_talhao')
			->select('id_situacao_ordem_servico_talhao')
			->select('area_total')
			->select('area_trabalhada')
			->select('dt_abertura')
			->select('dt_fechamento')
			->where_in('id_ordem_servico', __::flatten($listOs) )
			->order_by_asc('id')
			->find_array();
			
		echo json_encode($osItemList);
		
	}

	public function talhaoList() {

        $listOs = ORM::for_table('talhao')->table_alias('t')
		->select('t.id')
		->select('t.id', 'id_talhao')
		->select('t.id_fazenda')
		->select('t.id_talhao_bloco')
		->select('t.descr')
		->select('t.geojson')
		->select('t.pluviometria')
		
		->where('id_fazenda', 7)
		->find_array();
		
		echo json_encode($listOs);
	}

}