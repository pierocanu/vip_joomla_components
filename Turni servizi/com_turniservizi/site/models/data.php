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

class DataModelData extends JModelItem{
	
		protected $giorno_attuale;
		protected $num_giorno_attuale;
		
		protected $mese_attuale;
		protected $num_mese_attuale;
		
		protected $mese_prossimo;
		protected $num_mese_prossimo;
		
		protected $anno_attuale;	
		protected $anno_mese_prossimo;
		
		protected $giorni_mese_attuale;
		protected $giorni_mese_prossimo;
		
		protected $giorni_settimana;
		protected $mesi;
		
		function __construct(){

			$this->giorni_settimana = array("Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab");
			$this->mesi = array("Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre","Novembre", "Dicembre");
			
			$this->num_giorno_attuale = date( "d",time());
					
			$this->num_mese_attuale=date( "n",time());
			$this->anno_attuale = date( "Y",time());
			
			if ($this->num_mese_attuale == 12){
				$this->num_mese_prossimo =1;
			} else {
				$this->num_mese_prossimo = date( "n",time())+1;
			}
			
			if($this->num_mese_attuale  == 12){
				$this->anno_mese_prossimo=$this->anno_attuale+1;
			} else { 
				$this->anno_mese_prossimo=$this->anno_attuale;
			}

			$this->giorni_mese_attuale=$this->days_in_month($this->num_mese_attuale,$this->anno_attuale);
			$this->giorni_mese_prossimo = $this->days_in_month($this->num_mese_prossimo,$this->anno_attuale);
			
			
			$this->mese_attuale=$this->mesi[$this->num_mese_attuale];
			$this->mese_prossimo=$this->mesi[$this->num_mese_prossimo];

		}
	
		//visto che cal_days_in_month crea dei problemi con le versioni recenti di php implemento una funzione che faccia la stessa cosa

		function days_in_month($month, $year) {
			return date('t', mktime(0, 0, 0, $month, 1, $year)); //ritorna il numero di giorni in un mese
		}	

		//Getter, per mia comodità definisco la funzione "getInfo"
		public function getInfo($ret){

			if ($ret=='NumGiornoAttuale'){return $this->num_giorno_attuale; }
			else if($ret=='NumMeseAttuale'){return $this->num_mese_attuale; } 
			else if ($ret=='NumMeseProssimo'){ return $this->num_mese_prossimo; }
			else if ($ret=='MeseAttuale'){return $this->mese_attuale; }
			else if ($ret=='MeseProssimo'){return $this->mese_prossimo; }
			else if ($ret=='AnnoAttuale'){return $this->anno_attuale;}
			else if ($ret=='AnnoMeseProssimo'){return $this->anno_mese_prossimo;}
			else if($ret=='GiorniMeseAttuale'){return $this->giorni_mese_attuale;}
			else if($ret=='GiorniMeseProssimo'){return $this->giorni_mese_prossimo;}
			else if($ret=='GiorniSettimana'){return $this->giorni_settimana;}
			else if($ret=='MesiAnno'){return $this->mesi;}
			
		}
		
		
		public function meseFromTextToNum($mese){ //trasforma il mese da testuale a numero

			$mesi=$this->mesi;
			for($m=1; $m<=12; $m++){
				if($mesi[$m] == $mese){
					$mese = $m;		
				}
			}
			
			return $mese;
		}
		
		
		public function meseFromNumToText($mm){ //trasforma il mese da numerico a testuale 
			
			if($mm<=9 && strlen($mm)>1){
				$mm=substr($mm, 1);
			}
			
			$mesi=$this->getInfo('MesiAnno');
			return $mesi[$mm];
		
		}
		
		
		public function calcolaAnno($mm){ //Calcola l'anno

			if($mm == $this->num_mese_attuale ){ 
				$aa = $this->anno_attuale;
			} else if($mm == $this->num_mese_prossimo){
				$aa = $this->anno_mese_prossimo;	
			} 
	
			return $aa;
		}
		
}

?>