<?php

/*
 * @package Joomla 1.6
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
	
		protected $giornoAttuale;
		protected $NumGiornoAttuale;
		protected $NumMeseAttuale;
		protected $NumMeseProssimo;
		protected $MeseAttuale;
		protected $MeseProssimo;
		protected $AnnoAttuale;	
		protected $AnnoMeseProssimo;
		
		protected $GiorniMeseAttuale;
		protected $GiorniMeseProssimo;
		
		protected $giorniSettimana;
		protected $mesi;
		
		protected $giorniDiServizio;
		
		function __construct(){

			$this->giorniSettimana = array("Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab");
			$this->mesi = array("Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre","Novembre", "Dicembre",);
			
			$this->NumGiornoAttuale = date( "d",time());
			$this->NumMeseAttuale=date( "n",time());
			$this->AnnoAttuale = date( "Y",time());
			
			if ($this->NumMeseAttuale == 12){
				$this->NumMeseProssimo =1;
			} else {
				$this->NumMeseProssimo = date( "n",time())+1;
			}
			
			if($this->NumMeseAttuale  == '12'){
				$this->AnnoMeseProssimo=$this->AnnoAttuale+1;
			} else { 
				$this->AnnoMeseProssimo=$this->AnnoAttuale;
			}

			$this->GiorniMeseAttuale=$this->days_in_month($this->NumMeseAttuale,$this->AnnoAttuale);
			$this->GiorniMeseProssimo = $this->days_in_month($this->NumMeseProssimo,$this->AnnoAttuale);
			
			$this->giornoAttuale=$this->giorniSettimana[$this->NumGiornoAttuale];
			$this->MeseAttuale=$this->mesi[$this->NumMeseAttuale];
			$this->MeseProssimo=$this->mesi[$this->NumMeseProssimo];

			$this->giorniDiServizio=array_fill(0,6,0);
			
			if(JRequest::getInt('Lun') == 1){$this->giorniDiServizio['0']='Lun';} else if(JRequest::getInt('Lun') == 2){$this->giorniDiServizio['0']='UltimoLun';}
			if(JRequest::getInt('Mar') == 1){$this->giorniDiServizio['1']='Mar';} else if(JRequest::getInt('Mar') == 2){$this->giorniDiServizio['1']='UltimoMar';}
			if(JRequest::getInt('Mer') == 1){$this->giorniDiServizio['2']='Mer';} else if(JRequest::getInt('Mer') == 2){$this->giorniDiServizio['2']='UltimoMer';}
			if(JRequest::getInt('Gio') == 1){$this->giorniDiServizio['3']='Gio';} else if(JRequest::getInt('Gio') == 2){$this->giorniDiServizio['3']='UltimoGio';}
			if(JRequest::getInt('Ven') == 1){$this->giorniDiServizio['4']='Ven';} else if(JRequest::getInt('Ven') == 2){$this->giorniDiServizio['4']='UltimoVen';}
			if(JRequest::getInt('Sab') == 1){$this->giorniDiServizio['5']='Sab';} else if(JRequest::getInt('Sab') == 2){$this->giorniDiServizio['5']='UltimoSab';}
			if(JRequest::getInt('Dom') == 1){$this->giorniDiServizio['6']='Dom';} else if(JRequest::getInt('Dom') == 2){$this->giorniDiServizio['6']='UltimoDom';}						

		}
	
		//visto che cal_days_in_month crea dei problemi con le versioni recenti di php implemento una funzione che faccia la stessa cosa

		function days_in_month($month, $year) {
			return date('t', mktime(0, 0, 0, $month, 1, $year)); //ritorna il numero di giorni in un mese
		}	

		//Getter, per mia comodità definisco la funzione "getInfo"
		public function getInfo($ret){

			if ($ret=='NumGiornoAttuale'){return $this->NumGiornoAttuale; }
			else if($ret=='NumMeseAttuale'){return $this->NumMeseAttuale; } 
			else if ($ret=='NumMeseProssimo'){ return $this->NumMeseProssimo; }
			else if ($ret=='giornoAttuale'){return $this->giornoAttuale; }			
			else if ($ret=='MeseAttuale'){return $this->MeseAttuale; }
			else if ($ret=='MeseProssimo'){return $this->MeseProssimo; }
			else if ($ret=='AnnoAttuale'){return $this->AnnoAttuale;}
			else if ($ret=='AnnoMeseProssimo'){return $this->AnnoMeseProssimo;}
			else if($ret=='GiorniMeseAttuale'){return $this->GiorniMeseAttuale;}
			else if($ret=='GiorniMeseProssimo'){return $this->GiorniMeseProssimo;}
			else if($ret=='GiorniSettimana'){return $this->giorniSettimana;}
			else if($ret=='GiorniDiServizio'){return $this->giorniDiServizio;}
			else if($ret=='MesiAnno'){return $this->mesi;}
			
		}

}

?>