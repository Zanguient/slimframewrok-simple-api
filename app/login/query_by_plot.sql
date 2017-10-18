SELECT
	cultura,
	talhao,
	area_total,
	  SUM(IF(subgrupo_insumo='CORRETIVO',valor_total,0)) 		        	AS corretivo,
	( SUM(IF(subgrupo_insumo='CORRETIVO',valor_total,0)) / area_total )     AS corretivo_ha,
	
	  SUM(IF(subgrupo_insumo='FERTILIZANTE',valor_total,0)) 	        	AS fertilizante,
	( SUM(IF(subgrupo_insumo='FERTILIZANTE',valor_total,0)) / area_total )  AS fertilizante_ha,
	
	  SUM(IF(subgrupo_insumo='FUNGICIDA',valor_total,0)) 		        	AS fungicida,
	( SUM(IF(subgrupo_insumo='FUNGICIDA',valor_total,0)) / area_total )     AS fungicida_ha,
	
	  SUM(IF(subgrupo_insumo='HERBICIDA',valor_total,0)) 					AS herbicida,
	( SUM(IF(subgrupo_insumo='HERBICIDA',valor_total,0)) / area_total )     AS herbicida_ha,
	
	  SUM(IF(subgrupo_insumo='INSETICIDA',valor_total,0)) 					AS inseticida,
	( SUM(IF(subgrupo_insumo='INSETICIDA',valor_total,0)) / area_total )    AS inseticida_ha,
	
	  SUM(IF(subgrupo_insumo='SOJA',valor_total,0)) 					AS sementes,
	( SUM(IF(subgrupo_insumo='SOJA',valor_total,0)) / area_total )  	AS sementes_ha,
	(
		SUM(IF(subgrupo_insumo='EMULSIFICANTE'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='INOCULANTE'	,valor_total,0)) +  
		SUM(IF(subgrupo_insumo='LUBRIFICANTE'	,valor_total,0))
	) outros,
	((
		SUM(IF(subgrupo_insumo='EMULSIFICANTE'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='INOCULANTE'	,valor_total,0)) +  
		SUM(IF(subgrupo_insumo='LUBRIFICANTE'	,valor_total,0))
	) / area_total ) outros_ha,
	(
		SUM(IF(subgrupo_insumo='CORRETIVO'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='EMULSIFICANTE'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='FERTILIZANTE'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='FUNGICIDA'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='HERBICIDA'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='INOCULANTE'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='INSETICIDA'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='LUBRIFICANTE'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='SOJA'		,valor_total,0)) 
	) geral,
	((
		SUM(IF(subgrupo_insumo='CORRETIVO'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='EMULSIFICANTE'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='FERTILIZANTE'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='FUNGICIDA'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='HERBICIDA'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='INOCULANTE'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='INSETICIDA'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='LUBRIFICANTE'	,valor_total,0)) + 
		SUM(IF(subgrupo_insumo='SOJA'		,valor_total,0)) 
	) / area_total ) geral_ha
FROM
	v_apontamento_atividade_insumo vaai
WHERE
	id_fazenda IN ($id_fazenda)
	AND id_safra = $id_safra
	AND id_cultura IN ($id_cultura)
	AND id_talhao IN ($id_talhao)
GROUP BY talhao
ORDER BY area_total