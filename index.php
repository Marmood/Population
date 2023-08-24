<?php
try {
	$dbh = new PDO('mysql:host=localhost;dbname=pays', 'User', 'xa_DO1B.q*cc4Kre');
	$resultat1=$dbh->query('SELECT libelle_continent, id_continent, SUM(population_pays), AVG(taux_natalite_pays), AVG(taux_mortalite_pays), AVG(esperance_vie_pays), AVG(taux_mortalite_infantile_pays), AVG(nombre_enfants_par_femme_pays) ,AVG(taux_croissance_pays), SUM(population_plus_65_pays) FROM t_pays INNER JOIN t_continents ON t_continents.id_continent=t_pays.continent_id GROUP BY libelle_continent');
	$rows1 = $resultat1->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Population du monde</title>
	<script type="text/javascript" src="Monde.js"></script>
</head>
<body onload="init()">
	<header>
		<article><h1>Population du monde</h1></article>
		<form action="" id="formulaire" method="GET">
			<article style="display:flex;">
				<article><h2>Par Continent</h2></article>
				<select id="continents" name="continent" onChange="changeContinent()">
					<option value="0">Monde</option>
					<?php
						foreach($rows1 as $row) {
							?><option value='<?php print $row["id_continent"]?>' <?php if(isset($_GET['continent'])&&$_GET['continent'] == $row["id_continent"]) {print 'selected="selected"';} ?>><?php print $row["libelle_continent"]?></option><?php ;
						}
					?>
				</select>
			</article>
			<article id="regions" style="display:none">
				<article><h2>Pays par région</h2></article>
				<select id="reg" name="region" onChange="changeRegion()">
					<option value="0">--</option>
					<?php
					$resultat2=$dbh->query('SELECT libelle_region, id_region, continent_id FROM t_regions INNER JOIN t_continents ON t_continents.id_continent=t_regions.continent_id WHERE continent_id='.$_GET['continent'].' ORDER BY libelle_region');
					$rows2 = $resultat2->fetchAll();
						foreach($rows2 as $row) {
							?><option value='<?php print $row["id_region"]?>' <?php if(isset($_GET['region'])&&$_GET['region'] == $row["id_region"]) {print 'selected="selected"';} ?>><?php print $row["libelle_region"]?></option><?php ;
						}
					?>
				</select>
			</article>
		</form>
	</header>
	<main>
		<table>
			<thead>
				<th>Pays</th>
				<th>Population totale (en milliers)</th>
				<th>Taux de natalité</th>
				<th>Taux de mortalité</th>
				<th>Espérance de vie</th>
				<th>Taux de mortalité infantile</th>
				<th>Nombre d'enfants par femme</th>
				<th>Taux de croissance</th>
				<th>Population de 65 ans et plus</th>
			</thead>
			<tbody name="Tableau">
				<?php
					$totalPopulation = 0;
					$totalNatalite = 0;
					$totalMortalite = 0;
					$totalEsperance = 0;
					$totalMortaliteInf = 0;
					$totalNbrEnf = 0;
					$totalTxCroi = 0;
					$totalPop65 = 0;
					$count = 0;
					
					if($_GET['continent'] == 0){
					foreach($rows1 as $row) {
						$totalPopulation += $row["SUM(population_pays)"];
						$totalNatalite += $row["AVG(taux_natalite_pays)"];
						$totalMortalite += $row["AVG(taux_mortalite_pays)"];
						$totalEsperance += $row["AVG(esperance_vie_pays)"];
						$totalMortaliteInf += $row["AVG(taux_mortalite_infantile_pays)"];
						$totalNbrEnf += $row["AVG(nombre_enfants_par_femme_pays)"];
						$totalTxCroi += $row["AVG(taux_croissance_pays)"];
						$totalPop65 += $row["SUM(population_plus_65_pays)"];
						$count++;
						?><tr>
							<td><?php print $row["libelle_continent"]?></td>
							<td><?php print number_format($row["SUM(population_pays)"], 0, '.', ' ')?></td>
							<td><?php print number_format($row["AVG(taux_natalite_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["AVG(taux_mortalite_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["AVG(esperance_vie_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["AVG(taux_mortalite_infantile_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["AVG(nombre_enfants_par_femme_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["AVG(taux_croissance_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["SUM(population_plus_65_pays)"], 0, '.', ' ')?></td>
						</tr>
					<?php } ?>
						<tr>
							<td>Total</td>
							<td><?php print number_format($totalPopulation, 0, '.', ' ')?></td>
							<td><?php print number_format($totalNatalite / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalMortalite / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalEsperance / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalMortaliteInf / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalNbrEnf / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalTxCroi / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalPop65, 0, '.', ' ') ?></td>
						</tr>
					<?php }
					if($_GET['region'] == 0 && $_GET['continent'] != 0 && $_GET['continent'] != 3){
						$resultat5=$dbh->query('SELECT libelle_region, id_continent, SUM(population_pays), AVG(taux_natalite_pays), AVG(taux_mortalite_pays), AVG(esperance_vie_pays), AVG(taux_mortalite_infantile_pays), AVG(nombre_enfants_par_femme_pays) ,AVG(taux_croissance_pays), SUM(population_plus_65_pays) FROM t_pays INNER JOIN t_regions ON t_regions.id_region=t_pays.region_id INNER JOIN t_continents ON t_pays.continent_id=t_continents.id_continent WHERE id_continent='.$_GET['continent'].' GROUP BY libelle_region');
						$rows5 = $resultat5->fetchAll();
					foreach($rows5 as $row) {
						$totalPopulation += $row["SUM(population_pays)"];
						$totalNatalite += $row["AVG(taux_natalite_pays)"];
						$totalMortalite += $row["AVG(taux_mortalite_pays)"];
						$totalEsperance += $row["AVG(esperance_vie_pays)"];
						$totalMortaliteInf += $row["AVG(taux_mortalite_infantile_pays)"];
						$totalNbrEnf += $row["AVG(nombre_enfants_par_femme_pays)"];
						$totalTxCroi += $row["AVG(taux_croissance_pays)"];
						$totalPop65 += $row["SUM(population_plus_65_pays)"];
						$count++;
						?><tr>
							<td><?php print $row["libelle_region"]?></td>
							<td><?php print number_format($row["SUM(population_pays)"], 0, '.', ' ')?></td>
							<td><?php print number_format($row["AVG(taux_natalite_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["AVG(taux_mortalite_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["AVG(esperance_vie_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["AVG(taux_mortalite_infantile_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["AVG(nombre_enfants_par_femme_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["AVG(taux_croissance_pays)"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["SUM(population_plus_65_pays)"], 0, '.', ' ')?></td>
						</tr>
					<?php }?>
						<tr>
							<td>Total</td>
							<td><?php print number_format($totalPopulation, 0, '.', ' ')?></td>
							<td><?php print number_format($totalNatalite / $count, 2, '.', ' ');  ?></td>
							<td><?php print number_format($totalMortalite / $count, 2, '.', ' ');  ?></td>
							<td><?php print number_format($totalEsperance / $count, 2, '.', ' ');  ?></td>
							<td><?php print number_format($totalMortaliteInf / $count, 2, '.', ' ');  ?></td>
							<td><?php print number_format($totalNbrEnf / $count, 2, '.', ' ');  ?></td>
							<td><?php print number_format($totalTxCroi / $count, 2, '.', ' ');  ?></td>
							<td><?php print number_format($totalPop65, 0, '.', ' ') ?></td>
						</tr>
					<?php 
					}
					if($_GET['continent'] == 3){
						$resultat4=$dbh->query('SELECT libelle_pays, population_pays, taux_natalite_pays, taux_mortalite_pays, esperance_vie_pays, taux_mortalite_infantile_pays, nombre_enfants_par_femme_pays, taux_croissance_pays, population_plus_65_pays, continent_id, region_id FROM t_pays WHERE id_pays=297 OR id_pays=298 ORDER BY libelle_pays');
						$rows4 = $resultat4->fetchAll();
					foreach($rows4 as $row) {
						$totalPopulation += $row["population_pays"];
						$totalNatalite += $row["taux_natalite_pays"];
						$totalMortalite += $row["taux_mortalite_pays"];
						$totalEsperance += $row["esperance_vie_pays"];
						$totalMortaliteInf += $row["taux_mortalite_infantile_pays"];
						$totalNbrEnf += $row["nombre_enfants_par_femme_pays"];
						$totalTxCroi += $row["taux_croissance_pays"];
						$totalPop65 += $row["population_plus_65_pays"];
						$count++;
						?><tr>
							<td><?php print $row["libelle_pays"]?></td>
							<td><?php print print number_format($row["population_pays"], 0, '.', ' ')?></td>
							<td><?php print number_format($row["taux_natalite_pays"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["taux_mortalite_pays"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["esperance_vie_pays"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["taux_mortalite_infantile_pays"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["nombre_enfants_par_femme_pays"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["taux_croissance_pays"], 2, '.', ' '); ?></td>
							<td><?php print print number_format($row["population_plus_65_pays"], 0, '.', ' ')?></td>
						</tr>
					<?php }?>
						<tr>
							<td>Total</td>
							<td><?php print print number_format($totalPopulation, 0, '.', ' ')?></td>
							<td><?php print number_format($totalNatalite / $count, 2, '.', ' ');  ?></td>
							<td><?php print number_format($totalMortalite / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalEsperance / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalMortaliteInf / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalNbrEnf / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalTxCroi / $count, 2, '.', ' '); ?></td>
							<td><?php print print number_format($totalPop65, 0, '.', ' ')?></td>
						</tr>
					<?php 
					}
					if($_GET['continent'] != 3 && $_GET['continent'] != 0 && $_GET['region'] != 0){
						$resultat3=$dbh->query('SELECT libelle_pays, population_pays, taux_natalite_pays, taux_mortalite_pays, esperance_vie_pays, taux_mortalite_infantile_pays, nombre_enfants_par_femme_pays, taux_croissance_pays, population_plus_65_pays, continent_id, region_id FROM t_pays WHERE region_id='.$_GET['region'].' ORDER BY libelle_pays');
						$rows3 = $resultat3->fetchAll();
					foreach($rows3 as $row) {
						$totalPopulation += $row["population_pays"];
						$totalNatalite += $row["taux_natalite_pays"];
						$totalMortalite += $row["taux_mortalite_pays"];
						$totalEsperance += $row["esperance_vie_pays"];
						$totalMortaliteInf += $row["taux_mortalite_infantile_pays"];
						$totalNbrEnf += $row["nombre_enfants_par_femme_pays"];
						$totalTxCroi += $row["taux_croissance_pays"];
						$totalPop65 += $row["population_plus_65_pays"];
						$count++;
						?><tr>
							<td><?php print $row["libelle_pays"]?></td>
							<td><?php print print number_format($row["population_pays"], 0, '.', ' ')?></td>
							<td><?php print number_format($row["taux_natalite_pays"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["taux_mortalite_pays"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["esperance_vie_pays"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["taux_mortalite_infantile_pays"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["nombre_enfants_par_femme_pays"], 2, '.', ' '); ?></td>
							<td><?php print number_format($row["taux_croissance_pays"], 2, '.', ' '); ?></td>
							<td><?php print print number_format($row["population_plus_65_pays"], 0, '.', ' ')?></td>
						</tr>
					<?php }?>
						<tr>
							<td>Total</td>
							<td><?php print print number_format($totalPopulation, 0, '.', ' ')?></td>
							<td><?php print number_format($totalNatalite / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalMortalite / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalEsperance / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalMortaliteInf / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalNbrEnf / $count, 2, '.', ' '); ?></td>
							<td><?php print number_format($totalTxCroi / $count, 2, '.', ' '); ?></td>
							<td><?php print print number_format($totalPop65, 0, '.', ' ')?></td>
						</tr>
					<?php 
					}
				?>
			</tbody>
		</table>
	</main>
	
</body>
</html>	

<?php
$dbh = null;
}
catch (PDOException $e) {
	print "Oups !: Perdu<br/>";
	die();
}
?>

