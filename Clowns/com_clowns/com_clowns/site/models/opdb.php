<?php

/*
 * @package Joomla 1.7
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component clowns
 * @copyright Copyright (C) Piero Canu 
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class OpDBModelOpDB extends JModelItem{	 //principali funzioni sul db

	
	function aggClown($nome,$cognome,$nomeClown,$mailClown,$cellClown){ //aggiunge il clown nel database
		
		if(!isset($nomeClown) || !isset($mailClown) || $nomeClown=='' || $mailClown==''){
			return 2;
		}
		
		
		$array_params = array ($nome,$cognome,$nomeClown,$mailClown,$cellClown);
		
		
		for($i=0;$i<4;$i++){
				
			$array_params[$i]=trim($array_params[$i]); //Elimina gli spazi vuoti da inizio e fine
			
			if($i<3){
				$array_params[$i]=ucfirst($array_params[$i]);	//Prima lettera maiuscola
				$array_params[$i]=htmlspecialchars($array_params[$i], ENT_QUOTES);
				$array_params[$i]=str_replace(' ','_' ,$array_params[$i]); //Sostituisce gli spazi in mezzo con '_'
			}
		}
		
		$nome=$array_params[0];
		$cognome=$array_params[1];
		$nomeClown=$array_params[2];
		$mailClown=$array_params[3];
		$cellClown=$array_params[4];
		
		$tabella= "#__clowns";
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		 
		//Crea la tabella se non esiste
		$query='CREATE TABLE IF NOT EXISTS '.$tabella.'(`Nome` VARCHAR(128), `Cognome` VARCHAR(128), `Nome_Clown` VARCHAR(128) , `Mail` VARCHAR(150) , `Cell` VARCHAR(16)) ';
		$db->setQuery($query);
		$db->query();
		
		//Cerca se esiste già il clown
		$query="SELECT * FROM `$tabella` WHERE Nome_Clown='$nomeClown' OR Mail='$mailClown'";
		$db->setQuery($query);
		$db->query();
		$rows = $db->loadObjectList();

		if ($rows == NULL){
						
			//inserisce i dati 
			$query="INSERT INTO `$tabella` (Nome, Cognome, Nome_Clown, Mail, Cell) VALUES (\"$nome\", \"$cognome\", \"$nomeClown\", \"$mailClown\", \"$cellClown\")";
			$db->setQuery($query);
			$db->query();
	
			return 0;
		
		}
		return 1;

	}

	function rimClown($nomeClown){

		$nomeClown=trim($nomeClown);
		
		$nomeClown=ucfirst($nomeClown);
		
		$nomeClown=htmlspecialchars_decode($nomeClown, ENT_QUOTES);
		$nomeClown=htmlspecialchars($nomeClown, ENT_QUOTES);
		
		$nomeClown=str_replace(' ','_' ,$nomeClown);


		$tabella= "#__clowns";
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query="DELETE FROM `$tabella` WHERE Nome_Clown = '$nomeClown' ";
		$db->setQuery($query);
		if($db->query()==1){
			return 0;
		}
		return 1;
		 
	}

	function leggiElencoClowns(){
			
		$tabella= "#__clowns";
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		 
		//Recupera i nomi dei clown in elenco (nomi, cognomi e nomi clown)
		$query="SELECT * FROM `$tabella` ORDER BY Nome_Clown";
		$db->setQuery($query);
		$db->query();
		
		$rows = $db->loadObjectList();

		if ($rows != NULL){	 //La tabella non è vuota
			$n=0;	
			foreach ( $rows as $row ) {
			
			$nomi[$n]=$row->Nome;
			$nomi[$n]=str_replace('_',' ' ,$nomi[$n]);	
				
			$cognomi[$n]=$row->Cognome;
			$cohnomi[$n]=str_replace('_',' ' ,$cognomi[$n]);	
						
			$nomiClowns[$n]=$row->Nome_Clown;
			$nomiClowns[$n]=str_replace('_',' ' ,$nomiClowns[$n]);
			
			$mail[$n]=$row->Mail;
			$cell[$n]=$row->Cell;
			
			$n++;
			}	
	
		$clowns=array('nomi'=>$nomi,'cognomi'=>$cognomi,'nomiClowns'=>$nomiClowns, 'mail'=>$mail, 'cell'=>$cell);
		return $clowns;
		
		}
		
		else { 
			return NULL;
		}
		
	}


}


?>