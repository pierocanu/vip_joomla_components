<?php

/*
 * @package Joomla 1.6
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component formazioni
 * @copyright Copyright (C) Piero Canu 
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class OpDBModelOpDB extends JModelItem{	 //principali funzioni sul db
	
	function leggiFormazioni($mFrom,$aFrom,$mTo,$aTo){
	
		$model=new FormazioniModelFormazioni();
		$modelData = new DataModelData();
	
		$suff_db=$model->getSuffissoDB();
		$tabella= "#__formazioni_".$suff_db;

		//if($mFrom<=9){$mFrom = "0".$mFrom;}
		//if($mTo<=9){$mTo = "0".$mTo;}
		
		$gTo=$modelData->days_in_month($mTo, $aTo);
		$giorni_settimana = $modelData->getInfo('GiorniSettimana');

		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query="SELECT * FROM `$tabella` WHERE  DATA >= '$aFrom-$mFrom-1' AND DATA <= '$aTo-$mTo-$gTo' ORDER BY DATA ";
		$db->setQuery($query);
		$db->query();
		$rows = $db->loadObjectList();
		
		//array di mesi intercorrenti tra $mFrom e $mTo (1,mese,0,  1,mese,0  ecc..):
		$a_Tmp=$aFrom; 
		$m_Tmp=$mFrom;
		$data_fine = "$mTo-$aTo";
		
		for($i=0; strcasecmp("$m_Tmp-$a_Tmp" ,$data_fine) != '0' ;$i++){
			
						
			$array_formazioni[$i]= 1;
			$i++;
			$array_formazioni[$i]=$modelData->meseFromNumToText($m_Tmp);
			$array_formazioni[$i].=" $a_Tmp";
			$i++;
			$array_formazioni[$i]= 0 ;
			$m_Tmp++; 	
			
			if($m_Tmp == 13){
				$m_Tmp = 1;
				$a_Tmp++;
			}
			
			// mese restante - da fare prima che si chiuda il ciclo for
			if(strcasecmp("$m_Tmp-$a_Tmp" ,$data_fine) == '0'){
				$i++;
				$array_formazioni[$i]= 1;
				$i++;
				$array_formazioni[$i]=$modelData->meseFromNumToText($m_Tmp);
				$array_formazioni[$i].=" $a_Tmp";
				$i++;
				$array_formazioni[$i]= 0 ;
			}
			
		} 
	
		if ($rows == NULL){ 
			//NESSUNA FORMAZIONE TROVATA,
			
			$formazioni[0]=$array_formazioni; 
			$formazioni[1]=$array_formazioni;
			$formazioni[2]=$array_formazioni;
			
			return $formazioni;
			
		} else {

			$i=0;
			foreach ( $rows as $row ) { 
				$date_formazioni_db[$i]= $row->Data;
				$note_formazioni_db[$i]= $row->Note;
				$presenze_formazioni_db[$i]= $row->Presenze;
				$i++;
			}
			
			$m=0;
			$t=0;
			for($n=0;$n<count($array_formazioni);$n++){
					
				$date_formazioni[$t]=$array_formazioni[$n];
				$note_formazioni[$t]=$array_formazioni[$n];
				$presenze_formazioni[$t]=$array_formazioni[$n];
				$t++;
				
				if(isset($date_formazioni_db[$m])){
					$data=explode('-',$date_formazioni_db[$m]);
					$mese = $data[1];
					$mese=$modelData->meseFromNumToText($mese);
					
					$anno = $data[0];
					
					while(strcasecmp("$mese $anno",$array_formazioni[$n])== 0){
					
						$date_formazioni[$t]=$date_formazioni_db[$m];
						$note_formazioni[$t]=$note_formazioni_db[$m];
						$presenze_formazioni[$t]=$presenze_formazioni_db[$m];
						
						$m++;
						
						$data=explode('-',$date_formazioni_db[$m]);
						$mese = $data[1];
						$mese=$modelData->meseFromNumToText($mese);
						$anno = $data[0];
					
						$t++;
											
					}
				}		
				
								
																
			}
			
			//formatta le date per le select:
			$index=0;
			for ($i=0; $i <count($date_formazioni); $i++) {
				 if($date_formazioni[$i] != '0' && $date_formazioni[$i] != '1' && $date_formazioni[$i-1] != '1'){
				  	$data=explode('-',$date_formazioni[$i]);
					if($data[2]<=9){$data[2]=substr($data[2], 1);}
					if($data[1]<=9){$data[1]=substr($data[1], 1);}
					
					$date_formazioni_formattate[$index] = $data[2]." ".$modelData->meseFromNumToText($data[1]);
				 	$index++;
				 }
				
			}

			$formazioni[0]=$date_formazioni; 
			$formazioni[1]=$note_formazioni;
			$formazioni[2]=$presenze_formazioni;
			$formazioni[3]=$date_formazioni_formattate;		
			
			return $formazioni;	
		}	
			
	}
	

	function aggFormaz($giorno,$mese,$anno,$note){ //aggiunge una formazione nel database
		
		$model=new FormazioniModelFormazioni();
		$modelData = new DataModelData();
	
		$suff_db=$model->getSuffissoDB();
		$tabella= "#__formazioni_".$suff_db;
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		if($note=='Null' || $note==' ' || $note==''){$Note='noNote';}
		 
		//Verifica se esiste già la tabella/campo con il campo di quel mese e giorno e anno
		if($mese<=9 && strlen($mese)<2){$mese = "0".$mese;}
		
		$query="SELECT * FROM `$tabella` WHERE  YEAR(Data) = $anno AND MONTH(Data) = $mese AND DAY(Data) = $giorno ";
		$db->setQuery($query);
 		$db->query();
		$rows = $db->loadObjectList();

		if ($rows == NULL){	 //La tabella non esiste
				
			$query='CREATE TABLE '.$tabella.'(`Data` DATE NOT NULL ,`Note` VARCHAR( 550 ) NULL ,`Presenze` VARCHAR( 550 ) NULL)';
			$db->setQuery($query);
			$db->query();
			
			//inserisce i dati (per la prima volta)

			$query="INSERT INTO `$tabella` (Data, Note) VALUES (\"$anno-$mese-$giorno\", \"$note\")";
			$db->setQuery($query);
			$db->query();
	
			return 0;
		
		} else { // la tabella - e quindi la formazione - esistono già
	
			return 1;
		}
		
	
	}
	

		

	function rimFormaz($giorno,$numMese,$anno){
		
		$model=new FormazioniModelFormazioni();
		$modelData = new DataModelData();
	
		$suff_db=$model->getSuffissoDB();
		$tabella= "#__formazioni_".$suff_db;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		 
		 
		$query="SELECT * FROM `$tabella` WHERE  YEAR(Data) = $anno AND MONTH(Data) = $numMese AND DAY(Data) = $giorno ";
		$db->setQuery($query);
 		$db->query();
		$rows = $db->loadObjectList();

		if ($rows == NULL){	 //La tabella non esiste
			return 1;
			}

		 else if($numMese<=9){$numMese = "0".$numMese;}
		
		//Cancella il campo 
		$query="DELETE FROM `$tabella` WHERE YEAR(Data) = $anno AND MONTH(Data) = $numMese AND DAY(Data) = $giorno ";
		$db->setQuery($query);
		$db->query();
		
		return 0;
		
	}

	
		
	function modificaNoteFormaz($modifica, $data, $note){
		
		$model=new FormazioniModelFormazioni();
		$modelData = new DataModelData();
	
		$suff_db=$model->getSuffissoDB();
		$tabella= "#__formazioni_".$suff_db;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$dataExplode=explode('-',$data);
		$aa=$dataExplode[0];
		$mm=$dataExplode[1];
		$gg=$dataExplode[2];
		
		//verifica che esista la tabella/campo
		$query="SELECT * FROM `$tabella` WHERE  YEAR(Data) = $aa AND MONTH(Data) = $mm AND DAY(Data) = $gg ";
		$db->setQuery($query);
 		$db->query();
		$rows = $db->loadObjectList();

		if($rows==NULL){ //formazione non trovata
			return NULL;
		}
			
		else { 
			$query="UPDATE `$tabella` SET Note='$note' WHERE  YEAR(Data) = $aa AND MONTH(Data) = $mm AND DAY(Data) = $gg ";
			$db->setQuery($query);
 			$db->query();
			if($note == '' || $note == ' '){ //note cancellate
				return 1;
			} else { //note modificate
				return 0;	
			}
			
		}return 0;

	}
	
	function leggiComunicazioni(){
		
		$model=new FormazioniModelFormazioni();
		$modelData = new DataModelData();
	
		$suff_db=$model->getSuffissoDB();
		$tabella= "#__comunicazioni";
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query="SELECT * FROM `$tabella` WHERE  Titolo_Comunicazione = \"$suff_db\" ";
		$db->setQuery($query);
 		$db->query();
		$rows = $db->loadObjectList();
		
		if($rows!=NULL){
			foreach ( $rows as $row ) {
				if ($row->Testo_Comunicazione=='' || $row->Testo_Comunicazione==' '){ // campo comunicazioni vuoto
					return NULL;
				} else {
					$comunicaz = $row->Testo_Comunicazione;		
				} 
			} 
			
			return $comunicaz;
		} else {
			return 0;
			}
		
	}
	
	function modificaComunicaz($comunicazioni){
		
		$model=new FormazioniModelFormazioni();	
			
		$model = new FormazioniModelFormazioni;
		$modelData = new DataModelData();
	
		$suff_db=$model->getSuffissoDB();		
		$tabella= "#__comunicazioni";
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		
	
		$comunicazioni=str_replace("\n","<br />",$comunicazioni);	

		//verifica che esista la tabella/campo
		$query="SELECT * FROM `$tabella` WHERE  Titolo_Comunicazione = \"$suff_db\" ";
		$db->setQuery($query);
 		$db->query();
		$rows = $db->loadObjectList();

		if($rows==NULL){//La tabella non esiste ancora, creala e inserisci il valore
			$query='CREATE TABLE '.$tabella.' (`Titolo_Comunicazione` VARCHAR(200), `Testo_Comunicazione` VARCHAR(850))';
			$db->setQuery($query);
			$db->query();
			
			$query="INSERT INTO `$tabella` (Titolo_Comunicazione, Testo_Comunicazione) VALUES (\"$suff_db\", \"$comunicazioni\")";
			$db->setQuery($query);
			$db->query();
			
			if($comunicazioni == '' || $comunicazioni == ' '){ //comunicazioni cancellate
				return 1;
			} else { //comunicazioni modificate
				return 0;	
			}
			
		} else{
			$query="UPDATE `$tabella` SET Testo_Comunicazione=\"$comunicazioni\" WHERE  Titolo_Comunicazione = \"$suff_db\" ";
			$db->setQuery($query);
 			$db->query();
	
			if($comunicazioni == '' || $comunicazioni == ' '){ //comunicazioni cancellate
				return 1;
			} else { //comunicazioni modificate
				return 0;	
			}	
			
		}
		
		return 1;

	}
	
	function leggiClowns(){
		$tabella= "#__clowns";
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query="SELECT * FROM `$tabella` ORDER BY Nome ";
		$db->setQuery($query);
 		$db->query();
		$rows = $db->loadObjectList();
		
		if($rows != NULL){
			$n=0;
			foreach($rows as $row){
				$ret[$n]=$row->Nome;
				$n++;
			}
			return $ret;
		}
	}
	
}


?>