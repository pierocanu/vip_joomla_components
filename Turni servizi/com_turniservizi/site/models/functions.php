<?php

/*
 * @package Joomla 1.7
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component turni servizi
 * @copyright Copyright (C) Piero Canu
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class FunctionsModelFunctions extends JModelItem {

	public function servizi($mese, $anno) {

		$model = new TurniServiziModelTurniServizi();
		$modelData = new DataModelData();
		$modelDB = new OpDBModelOpDB();
		
		
		$giorni_servizio = $model->getServizi();
		$giorni_settimana = $modelData -> getInfo('GiorniSettimana');

		$giorni_del_mese = $modelData -> days_in_month($mese, $anno);
 
		$servizi_nel_mese = array_fill(1, $giorni_del_mese, 0);
		$servizi_nel_mese[0] = '0';
		
		$UltimiDelMese = array_fill(0, 6, 0); 

		//Se uno dei giorni è settato come "Solo ultimo del mese" cerca qual'è l'ultimo 
		// di quei giorni nel mese.
		
		for($t = 0; $t <= 6; $t++) {

			if($t < 6) {
				$S = $giorni_settimana[$t + 1];
			} else {
				$S = $giorni_settimana[0];
			}

			if(strcasecmp($giorni_servizio[$t], 'Ultimo' . $S) == '0') { 

				for($n = 1; $n <= $giorni_del_mese; $n++) {
					$g = $giorni_settimana[date("w", strtotime("$anno-$mese-$n"))];

					if(strcasecmp($g, $S) == '0') {
						$UltimiDelMese[$t] = $n;
					}
				} 
			}

		}
		
		//crea un array in cui inserisce i giorni di servizio

		for($n = 1; $n <= $giorni_del_mese; $n++) {

			for($t = 0; $t <= 6; $t++) {
				$g = $giorni_settimana[date("w", strtotime("$anno-$mese-$n"))];

				if(strcasecmp($giorni_servizio[$t], ($g)) == '0') {
					$servizi_nel_mese[$n] = $g;
				}

				if(strcasecmp($giorni_servizio[$t], ('Ultimo' . $g)) == '0') {
					if($n == $UltimiDelMese[$t]) {
						$servizi_nel_mese[$n] = $g;
					}
				}

			}

		}
	
	
		//controlla se ci sono altri servizi nel DB
	
		$servizi_in_db=$modelDB->serviziInDb($mese, $anno);
	
		if($servizi_in_db != NULL ){ 
			for($n=0;$n<=$giorni_del_mese;$n++){
				if($servizi_in_db[$n]!= '0'){
					$servizi_nel_mese[$n]=$servizi_in_db[$n];
				}
				
			}
		}

		return $servizi_nel_mese;

	}

	public function formattaPresenze($presenze_mese) {
				
		//elimina i '-' dei servizi etichettati
			 
		$presenze_mese_formattato=$presenze_mese;
		
		for($giorno=0;$giorno<=count($presenze_mese);$giorno++){
			
				
			if($presenze_mese[$giorno] != '0' ){
					
				$clowns=explode(' ', $presenze_mese[$giorno]);
				
				for($t=0;$t<count($clowns);$t++){
					
					if($clowns[$t]!='0'){
						$quante_volte=$this->quanteVolteFigliuolo($presenze_mese,$clowns[$t],$giorno);				
						
						//sostituisco eventuali underscore con uno spazio nel nome del clown:
						$quante_volte= str_replace('_', ' ', $quante_volte);
												
						
						if($t==0){ //primo clown tra le presenze del giorno
							$presenze_mese_formattato[$giorno]=$quante_volte;
						} else {
							$presenze_mese_formattato[$giorno]=$presenze_mese_formattato[$giorno].$quante_volte;
						}
						
						if(isset($clowns[$t+1])){ // Se ci sono altri clowns da esaminare metti due spazi
							$presenze_mese_formattato[$giorno] = $presenze_mese_formattato[$giorno].'&nbsp; '; 
						}
					} 
				}
					
			}
		
		} 
	
		return $presenze_mese_formattato;
		
	}

	public function quanteVolteFigliuolo($presenze_mese,$peccatore,$g) {
		
		
		$quante_volte=0;
		
        for($n=0;$n<=$g; $n++){
        	 
			$clowns=explode(' ',$presenze_mese[$n]);
			for($t=0;$t<=count($clowns);$t++){
				
				if(strcasecmp($clowns[$t],$peccatore)== 0){
					$quante_volte++;
				}
			}
		}
	
		if($quante_volte==1){$html = "<span class=\"uno\">".$peccatore."</span> <span class=\"num\"> (".$quante_volte.")</span>"; }
		elseif($quante_volte==2){$html = "<span class=\"bis\">".$peccatore."</span> <span class=\"num\"> (".$quante_volte.")</span>"; }
		else if($quante_volte==3){$html = "<span class=\"recidivo\">".$peccatore."</span> <span class=\"num\"> (".$quante_volte.")</span>"; }
		else if($quante_volte==4){$html = "<span class=\"urka\">".$peccatore."</span> <span class=\"num\"> (".$quante_volte.")</span>"; }
		else if($quante_volte>4){$html = "<span class=\"eroe\">".$peccatore."</span> <spanclass=\"num\"> (".$quante_volte.")</span>"; }
		
		return $html;

	}


	function stampaNote($numMese, $anno, $SuffissoDB) {
		require_once (JPATH_COMPONENT . DS . 'models' . DS . 'opdb.php');

		$dbModel = new OpDbModelOpDb();
		$Data = new DataModelData();

		$Note = $dbModel -> leggiNote($SuffissoDB, $numMese, $anno);
		return $Note;

	}



}
?>