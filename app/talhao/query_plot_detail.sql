SELECT
	id_talhao Cod,
	safra Safra,
	cultura Cultura,
	fazenda Fazenda,
	talhao_bloco Bloco,
	talhao Talhao,
	GROUP_CONCAT( CONCAT(insumo, ' - ', quantidade, ' ha') SEPARATOR ' <br>') Variedade
FROM v_plantio_talhao
WHERE id_safra = $id_safra
AND id_fazenda = $id_fazenda
AND id_cultura IN ($id_cultura)
AND id_talhao IN ( SELECT GROUP_CONCAT(t.id) FROM talhao t WHERE t.id_talhao = $id_talhao AND t.id_fazenda = $id_fazenda )
GROUP BY id_talhao
