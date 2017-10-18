SELECT
	t.id_talhao id,
	t.id_fazenda,
	t.descr AS talhao,
	tb.descr AS bloco,
	i.nome AS insumo,
	vpsrt. id_cultura,
	vpsrt.dt_inicial,
	vpsrt.dt_final,
	vpsrt.peso_armazem,
	vpsrt.peso_silobag,
	vpsrt.peso_silosweep,
	vpsrt.peso_limpo,
	vpsrt.area_total,
	vpsrt.area_plantada,
	vpsrt.area_colhida,
	vpsrt.idade,
	vpsrt.media,
	vpsrt.situacao
FROM
	v_painel_safra_resultado_temp11 vpsrt
INNER JOIN
	talhao t
	ON
	t.id = vpsrt.id_talhao
INNER JOIN
	talhao_bloco tb
	ON
	tb.id = t.id_talhao_bloco
INNER JOIN
	insumo i
	ON
	i.id = vpsrt.id_insumo
WHERE
	vpsrt.id_fazenda IN ($id_fazenda) AND
	vpsrt.id_safra = $id_safra AND
	vpsrt.id_cultura IN ($id_cultura)
ORDER BY
	t.descr
