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

class OpDBModelOpDB extends JModelItem{	 //principali funzioni sul db

	function serviziInDb($m,$a,$tabella) {	//restituisce i servizi in quel mese già inseriti nel db
			
		$data = new DataModelData();
	
		$giorniMese=$data->days_in_month($m, $a);
		$clownInServ=array_fill(1,$giorniMese,0);
		$servExtra=array_fill(1,$giorniMese,0);
		
		//Se il mese (e il giorno) è minore di 9 aggiungi uno zero
		if($giorno <= 9){$giorno="0".$giorno;}
		if($m <= 9){$m="0".$m;}
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query="SELECT * FROM `$tabella` WHERE  YEAR(Data) = $a AND MONTH(Data) = $m ORDER BY DAY(Data)";
		$db->setQuery($query);
		$db->query();
		$rows = $db->loadObjectList();
			
		if($rows != NULL){
			foreach ( $rows as $row ) {
				$tmp=explode('-',$row->Data);
				$giorno=$tmp[2];
				if($giorno<=9){$giorno=substr($giorno,1);}
				$clownInServ[$giorno]=$row->Nomi;
				$servExtra[$giorno]=$row->Altro;
			}
			$tmp[0]=$clownInServ;
			$tmp[1]=$servExtra;
			
			return 	$tmp;  
		}
		else {
			return NULL;
		}
		
	}
	
	
	public  function quanteVolteFigliolo($Peccatore, $FinoAQuando, $m, $a, $tabella){ //richiama dal db i nomi dei clown in servizio e li colora-numera 

        $quanteVolte=0;
        $db = JFactory::getDBO();
		
        for($g=1;$g<=$FinoAQuando; $g++){
			
			//Se il mese (e il giorno) è minore di 9 aggiungi uno zero
			if($g <= 9){$g="0".$g;}
			if($mese <= 9){$mese="0".$mese;}
			 
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query="SELECT * FROM `$tabella` WHERE  YEAR(Data) = $a AND MONTH(Data) = $m AND DAY(Data) = $g ORDER BY DAY(Data)";
			$db->setQuery($query);
			$db->query();
			$rows = $db->loadObjectList();
			
			if($rows != NULL ){	
				foreach ( $rows as $row ) {
					$Nomi=$row->Nomi;
					$Clown=explode(" ",$Nomi);
					}		

					for($j=0; $Clown[$j]!=NULL; $j++){						
						if(strcasecmp($Clown[$j],$Peccatore)== '0'){ 
							$quanteVolte++;
					}
					
				  }
			}
		}	
		
		$Peccatore=str_replace('_', ' ',$Peccatore);
		        
        if($quanteVolte==1){$html = "<span class=\"uno\">".$Peccatore."</span><span class=\"num\"> (".$quanteVolte.")</span>"; }
        if($quanteVolte==2){$html = "<span class=\"bis\">".$Peccatore."</span><span class=\"num\"> (".$quanteVolte.")</span>"; }
        if($quanteVolte==3){$html = "<span class=\"recidivo\">".$Peccatore."</span><span class=\"num\"> (".$quanteVolte.")</span>)"; }
        if($quanteVolte==4){$html = "<span class=\"urka\">".$Peccatore."</span><span class=\"num\"> (".$quanteVolte.")</span>"; }
        if($quanteVolte>4){$html = "<span class=\"eroe\">".$Peccatore."</span><spanclass=\"num\"> (".$quanteVolte.")</span>"; }
		
		return $html;
		
    	}
	
	

	function aggClown($SuffissoDB,$nome,$giorno,$mese,$anno,$altro=NULL){ //aggiunge il clown nel database

		$nome=trim($nome);	//Elimina gli spazi vuoti da inizio e fine
		$nome=str_replace(' ','_' ,$nome); //Sostituisce gli spazi in mezzo con '_'

		$tabella= "#__servizi_".$SuffissoDB;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		 
		//Se il mese (e il giorno) è minore di 9 aggiungi uno zero
		if($giorno <= 9){$giorno="0".$giorno;}
		if($mese <= 9){$mese="0".$mese;}
		 
		//Verifica se esiste già la tabella con il campo di quel mese e giorno o, se esiste, se è vuota
		$query="SELECT * FROM `$tabella` WHERE  YEAR(Data) = $anno AND MONTH(Data)= $mese AND DAY(Data) = $giorno";
		$db->setQuery($query);
		$db->query();
		$rows = $db->loadObjectList();

		if ($rows == NULL){
						
			$query='CREATE TABLE '.$tabella.'( `Data` DATE NOT NULL, `Nomi` VARCHAR(250), `Altro` VARCHAR(50) )';
			$db->setQuery($query);
			$db->query();
			
			//inserisce i dati (per la prima volta)
			$query="INSERT INTO `$tabella` (Data, Nomi, Altro) VALUES (\"$anno-$mese-$giorno\", \"$nome\", \"$altro\")";
			$db->setQuery($query);
			$db->query();
	
		return 0;
		
		} else {

			foreach ( $rows as $row ) {
				$nomi=$row->Nomi;
				}
			$nomiNuovo=$nomi." ".$nome;
			//fa l'update del campo
			$query="UPDATE $tabella SET Nomi='$nomiNuovo' WHERE YEAR(Data) = $anno AND MONTH(Data)= $mese AND DAY(Data) = $giorno";
			$db->setQuery($query);
			$db->query();
			
			if($altro!=NULL){
				$query="UPDATE $tabella SET Altro='$altro' YEAR(Data) = $anno AND MONTH(Data)= $mese AND DAY(Data) = $giorno";
				$db->setQuery($query);
				$db->query();
			}
						
			return 0;				
		}

	}

	function rimClown($SuffissoDB,$nome,$giorno,$mese,$anno){

		$nome=trim($nome);	//Elimina gli spazi vuoti da inizio e fine
		$nome=str_replace(' ','_' ,$nome); //Sostituisce gli spazi in mezzo con '_'

		$tabella= "#__servizi_".$SuffissoDB;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		 
		//Recupera i nomi dei clown presenti in servizio
		$query="SELECT * FROM `$tabella` WHERE YEAR(Data) = $anno AND MONTH(Data) = $mese AND DAY(Data) = $giorno ";
		$db->setQuery($query);
		$db->query();
		$rows = $db->loadObjectList();
		 
		if ($rows == NULL){ 
			return 1; //se non c'è il mese nel db
			} else {
				foreach ( $rows as $row ) {
					$nomi=$row->Nomi;
					}
				//Converti il campo in array di stringhe
				$nomiVecchi=explode(' ',$nomi);	
				
				//verifica che il nome sia nel db

				if(!in_array($nome,$nomiVecchi)){return 1;}
				
				//elimino nome dall'array
				
				if($nomiVecchi[1]==NULL){//se c'è un solo nome elimina la riga nel db
					$query="DELETE FROM `$tabella` WHERE Nomi='$nomi' AND YEAR(Data) = $anno AND MONTH(Data) = $mese AND DAY(Data) = $giorno "; 
					$db->setQuery($query);
					$db->query();
					return 0;
					} else {
						for($i=0; $nomiVecchi[$i]!=NULL;$i++){
							if($nomiVecchi[$i]==$nome){
								while($nomiVecchi[$i]!=NULL){
											$nomiVecchi[$i]=$nomiVecchi[$i+1];
											$i++;
								}
							}
						}
					}
							
				// Ricostruisci stringa unica per il db
				
				for($n=1; $nomiVecchi[$n]!=NULL;$n++){	
						$nomiVecchi[0]=$nomiVecchi[0]." ".$nomiVecchi[$n];
					}
					
				$nomiNuovi=$nomiVecchi[0];
				
				//Modifica nomi in campo
				$query="UPDATE `$tabella` SET Nomi=\"$nomiNuovi\" WHERE YEAR(Data) = $anno AND MONTH(Data) = $mese AND DAY(Data) = $giorno ";
				$db->setQuery($query);
				$db->query();
			
			
				return 0;	
					
		}
		
	}

	function modificaNote($SuffissoDB, $note, $mese, $anno){	

		$tabella= "#__noteServizi_".$SuffissoDB;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		if($note == NULL || $note == '' || $note== ' '){$note = 'Non ci sono note';}
		$note=str_replace("\n","<br />",$note);
		 
		//Verifica se esiste già la tabella con il campo di quel mese o, se esiste, se è vuota
		$query="SELECT * FROM `$tabella` WHERE  Mese = $mese AND Anno = $anno";
		$db->setQuery($query);
		$db->query();
		$rows = $db->loadObjectList();

		if ($rows == NULL){
						
			$query='CREATE TABLE '.$tabella.' ( Mese INT(12), Anno VARCHAR(4), Note TEXT )';
			$db->setQuery($query);
			$db->query();
			
		
			//Continua inserendo i dati
			$query="INSERT INTO `$tabella` (Mese, Anno, Note) VALUES (\"$mese\", \"$anno\", \"$note\")";
			$db->setQuery($query);
			$db->query();
		
			return 0;
			
	
		} else { //altrimenti fai direttamente l'upgrade della tabella
			
			foreach ( $rows as $row ) {
				$nomi=$row->Nomi;
				}
			$nomiNuovo=$nomi." ".$nome;
			//fa l'update del campo
			$query="UPDATE $tabella SET Note='$note' WHERE Mese = $mese AND Anno = $anno";
			$db->setQuery($query);
			$db->query();
						
			return 0;	
			
			}
	
	}
	
	function leggiNote($SuffissoDB,$numMese,$anno){
		$tabella= "#__noteServizi_".$SuffissoDB;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		 
		//Verifica se esiste già la tabella con il campo di quel mese o, se esiste, se è vuota
		$query="SELECT * FROM `$tabella` WHERE  Mese = $numMese AND Anno = $anno";
		$db->setQuery($query);
		$db->query();
		$rows = $db->loadObjectList();
		
		if ($rows == NULL){
			//NESSUNA NOTA
			return 'Nessuna nota';
			
		} else {
			foreach ( $rows as $row ) {
				$Note=$row->Note;
				}
			return $Note;
			}
		}

}


?>