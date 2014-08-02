<?php
//inicio medição performance
$antes = microtime(true);

Class GroupInterval
{
	/*
	* Function group
	*
	* Agrupa em subarrays por intervalo de dezenas
	*
	* @vArray (array) - Vetor de entrada de dados.
	* @range  (int)   - Intervalo do grupo.
	* @return (array) - Matriz de agrupamentos, ordenado e agrupado.
	*/
	public function group( $vArray, $range = 10 ){

		$ordenado = $this->quicksort($vArray);
		$menor = 0;
		$resultado = array();
		$grupo = array();

		foreach ($ordenado as $k => $valor):

				$diferencaAnt = ( ($valor - $menor) < 0 )? ($valor - $menor) * (-1) : ($valor - $menor);

				if( ( $diferencaAnt >= $range ) ):
					
					if($k > 0 ):
						$resultado[] = $grupo;
						$grupo = array();
					endif;

					$parcial = ($valor/$range);
					$arredondado = floor( $parcial ); //PEGA O MENOR VALOR DO GRUPO
					$diff = ($parcial - $arredondado)*$range;
					$menor = ($valor - $diff)+1; //menor valor desse grupo
					
				endif;

				$grupo[] = $valor;

		endforeach;
		$resultado[] = $grupo;


		return $resultado;
	}


	/*
	* Function quicksort
	* http://en.wikibooks.org/wiki/Algorithm_Implementation/Sorting/Quicksort#PHP
	*
	* Ordena o array em ordem crescente
	*
	* @array (array) - Vetor de entrada de dados.
	* @return (array) - Vetor ordenado.
	*/
	private function quicksort($array) {
	    if(count($array) < 2) return $array;
	 
	    $left = $right = array();
	 
	    reset($array);
	    $pivot_key = key($array);
	    $pivot = array_shift($array);
	 
	    foreach($array as $k => $v) {

 		if( !is_numeric($v) ) throw new Exception('InvalidArgumentException');

		if($v < $pivot)
	            $left[$k] = $v;
	        else
	            $right[$k] = $v;
	    }
	 
	    return array_merge($this->quicksort($left), array($pivot_key => $pivot), $this->quicksort($right));
	}

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Teste Rocket</title>
</head>
<body>
	
<?php 

	$problemas = array(
			array(
				'range' => 10,
				'number_set' => array(10, 1, -20,  14, 99, 136, 19, 20, 117, 22, 93,  120, 131)
			),
			array(
				'range' => 15,
				'number_set' => array(10, 1, -20,  14, 99, 136, 19, 20, 117, 22, 93, 120, 131)
			),
			array(
				'range' => 15,
				'number_set' => array(10, 1, "A",  14, 99, 133, 19, 20, 117, 22, 93,  120, 131)
			),
			array(
				'range' => null,
				'number_set' => array()
			)
		);
?>

<h1>Teste Rocket</h1>

<?php foreach( $problemas as $problema ): ?>

	<p>Range: <?php echo $problema['range']; ?></p>
	<p>Number Set: <?php echo json_encode( $problema['number_set'] ); ?></p>
	<p>Resultado:</p>
	<pre><code>
<?php

	try {

		$resultado = array();
		$group_interval = new GroupInterval();
		$resultado = $group_interval->group( $problema['number_set'], $problema['range'] );

		echo json_encode( $resultado );
		
	} catch (Exception $e) {
		
		echo 'Seguinte erro ocorreu: ' . $e->getMessage();

	}

?>
	</code></pre>


<?php endforeach; ?>


<hr>
<?php
//fim medição performance
$depois = microtime(true);
echo "\n" . "\n" . ($depois-$antes);
?>

</body>
</html>
