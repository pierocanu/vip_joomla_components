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

class FunctionsModelFunctions extends JModelItem{ //tutte le funzioni, comprese quelle che preparano l'output per view
	
	public function inviaMailRichiesta($nome, $mailClown, $g, $m, $agg, $mail){
			
			$nome=trim($nome);	//Elimina spazi vuoti da inizio e fine

			/* Messaggio txt*/

			$subject = "Turno $nome - ( $g $m )";
			
			if($agg==NULL || $agg=="Nessuna comunicazione aggiuntiva." || $agg==" "){
				$message = "Ciao staff Ospedale!\n\n ".$nome." chiede di essere inserito/a in turno il giorno ".$g." ".$m."\n \n Grazie. \nPotete scrivere a ".$nome." all'indirizzo ".$mailClown." oppure rispondendo a questa mail.";
			} else {
				$message = "Ciao staff Ospedale!\n\n ".$nome." chiede di essere inserito/a in turno il giorno ".$g." ".$m.".\n \n Inoltre scrive: .".$agg." \n \nPotete scrivere a ".$nome." all'indirizzo ".$mailClown." oppure rispondendo a questa mail.";
				}

			$headers = "From: $nome\r\n";
			$headers .= "Reply-To:  $mailClown\r\n";
						
			if ( mail($mail,$subject,$message,$headers) ) {
			   return 0;
			   } else {
			   return 1;
			   }


		}
	
	
	public function getServiziNelMese($mese,$anno){ //ritorna un array della dimensione dei giorni del mese che in corrispondenza dei giorni in cui c'è servizio contiene il giorno (ad es. Lun, Mar ecc..)
		
		$D = new DataModelData();
		$giorniServizio = $D-> getInfo('GiorniDiServizio');
		$Sett=$D->getInfo('GiorniSettimana');
		$this->giorniDelMese=$D->days_in_month($mese,$anno);
		$serviziNelMese=array_fill(1,$this->giorniDelMese,0);
		$serviziNelMese[0]='0'; 
		$UltimiDelMese=array_fill(0,6,0);

		
		//Se uno dei giorni è settato come "Solo ultimo del mese" cerca qual'è l'ultimo di quei giorni nel mese.
		for($t=0; $t<=6; $t++){

			if($t<6){$S=$Sett[$t+1];}else{$S=$Sett[0];}
						
			if(strcasecmp($giorniServizio[$t],'Ultimo'.$S) == '0'){ 

				for($n=1;$n <= $this->giorniDelMese; $n++){
					$g = $Sett[date("w",strtotime("$anno-$mese-$n"))];

					if( strcasecmp($g,$S )== '0'){
						$UltimiDelMese[$t] = $n;
						}	
					}			
			}
			
		}
		
		
		for($n=1;$n <= $this->giorniDelMese; $n++){

				for($t=0; $t<=6; $t++){
					$g = $Sett[date("w",strtotime("$anno-$mese-$n"))];
					
					if( strcasecmp($giorniServizio[$t],($g)) == '0' ){
						$serviziNelMese[$n] = $g;
						}

					if( strcasecmp($giorniServizio[$t],('Ultimo'.$g)) == '0' ){
						if($n == $UltimiDelMese[$t] ){
							$serviziNelMese[$n] = $g;
							}
						}
						
					}
				
			}			
		
		return $serviziNelMese;
		
		}

	function stampaMese($m,$a,$serviziNelMese,$SuffissoDB){ //funzione principale che ritorna l'html da stampare

		$dbModel = new OpDbModelOpDb();
		$Data = new DataModelData();
		
		$tabella = '#__servizi_'.$SuffissoDB;
		
		$tmp = $dbModel->serviziInDb($m,$a,$tabella);
		$clownInServ=$tmp[0];
		$servExtra=$tmp[1];
		
		$GiorniMese = $Data->days_in_month($m,$a);
		$GiorniSettimana=$Data->getInfo('GiorniSettimana');
		$color = 1;	
							
		for($n=1; $n <= $GiorniMese; $n++){
			
			$data = $a."-".$m."-".$n;
			$tmp = $GiorniSettimana[date("w",strtotime($data))];
		
			if($servExtra[$n]!=NULL && strcasecmp($servExtra[$n],'0')!=0 && strcasecmp($serviziNelMese[$n],'0')==0 ){  //Servizio "speciale"
				$html .= "\n		<div class=\"extra\">";
				$html .= "\n".'		<span class="giorni">&nbsp;'.$n.' ';
				$html .= $GiorniSettimana[date("w",strtotime($data))];
				$html .= ' ('.$servExtra[$n].')</span>';
				$html .= $this->elaboraServizio($n,$m,$a,$clownInServ[$n],$tabella);
				$html .= "\n		</div>";
	
			} else if(strcasecmp($serviziNelMese[$n],'0')!=0){  // Servizio normale
							
				$html .= "\n		<div >"; 
				if($color%2 == 0){$html .= "<span class=color>";}
				$html .= "\n\t\t<span class=\"giorni\">&nbsp;$n ";
				$html .= $serviziNelMese[$n];
				if($servExtra[$n]!='0' && $servExtra[$n]!=NULL){$html .= " ($servExtra[$n]) </span>";}else{$html .= "</span>";}
				$html .=$this->elaboraServizio($n,$m,$a,$clownInServ[$n],$tabella);
				
				$html .= "\t\t</div>\n";
				if($color%2 == 0){$html .= "\t\t</span>\n";}
				$color++;
				
				 
			} 
			
		}
		
		return $html;
	}
	
	function stampaDateMese($m,$a,$serviziNelMese,$SuffissoDB){ //funzione principale che ritorna l'html da stampare
		require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');

		$dbModel = new OpDbModelOpDb();
		$Data = new DataModelData();
		
		$tabella = '#__servizi_'.$SuffissoDB;
		
		$tmp = $dbModel->serviziInDb($m,$a,$tabella);
		$servExtra=$tmp[1];
		
		$GiorniMese = $Data->days_in_month($m,$a);
		$GiorniSettimana=$Data->getInfo('GiorniSettimana');
		$mesi=$Data->getInfo('MesiAnno');
		$mese=$mesi[$m];
							
		$t=0;
		for($n=1; $n < $GiorniMese; $n++){
			
			$data = $a."-".$m."-".$n;
		
			if($servExtra[$n]!=NULL && strcasecmp($servExtra[$n],'0')!=0 && strcasecmp($serviziNelMese[$n],'0')==0 ){  //Servizio "speciale"
				$html[$t] = "<br />".$GiorniSettimana[date("w",strtotime($data))]." $n $mese";
				$t++; 
				
			} else if(strcasecmp($serviziNelMese[$n],'0')!=0){  // Servizio normale
							
				$html[$t] = "<br />".$GiorniSettimana[date("w",strtotime($data))]." $n $mese";
				if($servExtra[$n]!='0' && $servExtra[$n]!=NULL){$html[$t] .= " ($servExtra[$n])";}else{$html[$t] .= "</span>";}
				$t++;  
			}
			
			
		}
		
		return $html;
	}

		
	public function elaboraServizio($n,$m,$a,$clownInServ,$tabella){ //ritorna i clown in servizio in stringa
		$dbModel = new OpDbModelOpDb();		
		
		if(strcasecmp($clownInServ,'0') != 0 && $clownInServ!=NULL){
			$ArrayTurno=explode(' ',$clownInServ);
	
			for($x=0;$ArrayTurno[$x];$x++){
				$howMany=$dbModel->quanteVolteFigliolo($ArrayTurno[$x],$n,$m, $a,$tabella);
				$html .= "\n		".$howMany;
				if(!$ArrayTurno[$x+1]){ // Se alla fine vai a capo
					$html .= "\n<br />\n";
					} else {
						$html .= ','; //altrimenti metti una virgola
						}
			}
		} else {
			$html .= "\n		<br />\n";
			}
			
		return $html;
	
	}
		


	function stampaNote($numMese,$anno,$SuffissoDB){
		require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');

		$dbModel = new OpDbModelOpDb();
		$Data = new DataModelData();
		
		$Note = $dbModel->leggiNote($SuffissoDB,$numMese,$anno);
		return $Note;
		
		
		}


}

?>