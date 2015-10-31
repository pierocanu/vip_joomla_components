<?php 

/*
 * @package Joomla 1.7
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component formazioni
 * @copyright Copyright (C) Piero Canu 
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>


<h1 style="text-align:center; font-size:28px;"><?php echo $this->titolo;?> </h1>

		<?php 
		
		$date_formazioni=$this->dati_formazioni[0]; 
		$note_formazioni=$this->dati_formazioni[1];
		
			
		$color=0;
		for($i=0; $i < count($date_formazioni); $i++){
			
			//Se c'è 1 inizia una nuova tabella
			if($date_formazioni[$i] == '1'){ 
				?>
				<div class="containerVisFormazioni"> 
					<div class="visualizzaMeseFormazioni"> 
						<h2 class="titoloTabellaFormazioni"><?php echo $date_formazioni[$i+1]; ?></h2> 
						<br />
				<?php
			$i++;
			}
			
			// se c'è 0 chiudi la tabella, ma se prima non c'erano servizi stampa un messaggio
			else if($date_formazioni[$i] == '0'){
				if($date_formazioni[$i-2] == '1'){
					echo "Nessuna formazione o date formazioni non ancora inserite <br/ >";
				}	
				
				?>
					</div>
				</div>
				<br /> 
				
				<?php
			}
			
			// stampa eventuali formazioni
			else{
					
				if($color % 2 == 0) { 
				?>		
					<span class="color"> 
				<?php 
				}

				$data=explode('-',$date_formazioni[$i]);
				if($data[2]<=9){$data[2]=substr($data[2], 1);}
				?>

				<span class="giorni">
					<?php 
						echo $data[2]." ".$this->giorni_settimana[date("w",strtotime("$date_formazioni[$i]"))];
					?> 
				</span>
			
					<?php //Se esistono stampa le note
						if( isset($note_formazioni[$i]) &&  $note_formazioni[$i] != ''){ echo " - ".$note_formazioni[$i];}
					?>
			
				<br/ >
				
				<?php
					
				if($color % 2 == 0) {
				?>		
					</span>
				<?php 
				 
				} 
				
				$color++;
			}
			
			
		}

	//COMUNICAZIONI
	?> 
		<div class="titoloComunicazioni">Comunicazioni:</div> 
		<br />
		<div class="comunicazioni">
		<?php 
		if(isset($this->comunicazioni) ){
			echo $this->comunicazioni; 
		} else {
			echo "Nessuna comunicazione";
		}
		
		?>
		</div> 
		<br /> 
		<br />
		<br />
		<br />
		<br />
		<br />
