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

<!-- messaggi di conferma / errore -->

<?php if(strcmp($this->messaggio , "formazione-aggiunta")== 0): ?> 		

<div align="center">
    <br />
    <div class="titoloMessaggio">Formazione aggiunta</div> 
	<p class="sottoTitoloMessaggio"> E' stata aggiunta una nuova data (<?php echo $this->data_formazione ?>).</p>
    <br />
</div>

<?php endif; ?>


	
<?php if($this->messaggio  == 'formazione-rimossa'): ?>

<div align=\"center\">
    <br />
	<div class="titoloMessaggio">Formazione rimossa</div>
    <p class="sottoTitoloMessaggio">E' stata cancellata una data (<?php echo $this->data_formazione ?>)</p>
	<br />
</div>	
<?php endif; ?>

<?php if($this->messaggio  == 'note-modificate'): ?>

<div align=\"center\">
    <br />
	<div class="titoloMessaggio">Note modificate</div>
    <p class="sottoTitoloMessaggio"> Sono state modificate le note per la formazione del <?php echo $this->data_formazione ?> .</p>
    <br />

</div>	
<?php endif; ?>


<?php if($this->messaggio  == 'note-cancellate'): ?>

<div align=\"center\">
    <br />
	<div class="titoloMessaggio">Note cancellate</div>
    <p class="sottoTitoloMessaggio"> Sono state cancellate le note per la formazione del <?php echo $this->data_formazione ?> .</p>
    <br />

</div>	
<?php endif; ?>

<?php if($this->messaggio  == 'comunicaz-modificate'): ?>

<div align=\"center\">
    <br />
	<div class="titoloMessaggio">Comunicazioni modificate</div>
    <p class="sottoTitoloMessaggio"> Il testo delle comunicazioni è stato modificato.</p>
    <br />

</div>	
<?php endif; ?>

<?php if($this->messaggio  == 'comunicaz-cancellate'): ?>

<div align=\"center\">
    <br />
	<div class="titoloMessaggio">Comunicazioni cancellate</div>
    <p class="sottoTitoloMessaggio"> Il testo delle comunicazioni è stato cancellato.</p>
    <br />

</div>	
<?php endif; ?>

<?php if($this->messaggio  == 'errore-formazione-gia-inserita'): //Messaggio di errore generico ?>

<div align=\"center\">
 	<br />
	<div class="titoloErrore"> La data esiste già! </div>
	<p class="sottoTitoloMessaggio"> La data che cerchi di inserire (<?php echo $this->data_formazione ?>) è già in elenco. </p>
    <br />
</div>				
<?php endif; ?>

<?php if($this->messaggio  == 'errore-formazione-non-trovata'): //Messaggio di errore generico ?>

<div align=\"center\">
 	<br />
	<div class="titoloErrore"> Errore... </div>
	<p class="sottoTitoloMessaggio"> Non è stato possibile trovare la data (<?php echo $this->data_formazione ?>). Potrebbe essere già stata cancellata.</p>
    <br />
</div>				
<?php endif; ?>



<?php if($this->messaggio == 'errore-formaz-non-rimossa' || $this->messaggio == 'errore-formaz-non-rimossa'): ?>

<div align=\"center\">

	<div class="titoloErrore">Errore...</div>
    <br />
    <p class="sottoTitoloMessaggio">Non è stato possibile <?php if($this->messaggio  == 'errore-formaz-non-aggiunta') {echo 'aggiungere' ;} else if($this->pag == 'errore-formaz-non-rimossa') {echo 'cancellare';}?> la data richiesta (<?php echo $this->data_formazione ?>).</p>
    <p class="sottoTitoloMessaggio">Per favore riprova, se il problema persiste contatta l'amministratore </p>
    <p class="sottoTitoloMessaggio">Grazie!</p>
    <br />
</div>	
<?php endif; ?>

<?php if($this->messaggio  == 'errore'): //Messaggio di errore generico ?>

<div align=\"center\">
    <br />
    <br />
    <br />
    <br />
	<div class="titoloErrore"> ERRORE!! </div>
    <br />
    <p class="sottoTitoloMessaggio"> Qualcosa non è andato per il verso giusto!! Per favore riprova, e se il problema persiste contatta l'amministratore. Grazie </p>
    <br />
</div>				
<?php endif; ?>

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
			
			// se c'è 0 chiudi la tabella, ma se prima non c'erano formazioni stampa un messaggio
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
		
	
	<?php 
	
	//Forms di gestione

	?>	
		
	<div class="containerGestione"> 
		<div class="formGestione"> 
			<h4 class="titoloFormGestione">Aggiungi una nuova formazione</h4>
			<p class="sottoTitoloFormGestione">Indica la data di una formazione da mostrare. Puoi anche inserire delle piccole note, ad esempio "Chi può porti un asciugamano" oppure "Formazione speciale a Monteclaro".</p>
			<form name="FormAggiungiFormaz" method="post">
			
			<label for="name">Data nuova formazione:</label>
			 <select name="gg" style="font-weight:bold;"> 
			 	<?php 
			 	
			 	$g=$this->num_giorno_attuale;
				if($g<=9){$g=substr($g, 1);}	
				echo $g;
			 	
			 	for($n=1;$n<=31; $n++){
			 		echo "<option ";
			 		if($n == $g){
						echo "selected";
					}
			 		echo ">$n</option>";
			 	} ?>
			</select>
	
			  
			<select name="mm" style="font-weight:bold;">
				<option><?php echo $this->mese_attuale; ?></option>
				<option><?php echo $this->mese_prossimo; ?></option>
				<option><?php echo $this->mese_successivo_al_prossimo; ?></option>
			</select>
			
			<br />
			<label for="note">Note sulla formazione:</label>
			<textarea name="note" cols="50'" rows="5"></textarea>		 
			<br /> 
	 		<br />  
			<input type="submit" name="" value="Aggiungi formazione" style="font-weight:bold;"/>
			<input type="hidden" name="option" value="com_formazioni" style="font-weight:bold;"/>
			<input type="hidden" name="task" value="aggFormaz" style="font-weight:bold;"/>
		</form> 
	</div>

	<div class="formGestione"> 
		<h4 class="titoloFormGestione">Cancella una formazione</h4>
		<p class="sottoTitoloFormGestione">Indica la data della formazione da cancellare e premi cancella formazione.</p>
		<form name="FormAggiungiFormaz" method="post">
			
		<form name="FormRimuoviFormaz" method="post">
			<label for="data">Cancella la formazione del</label> 
			<select name="data" style="font-weight:bold;">
			<?php
				$date_formazioni_formattate=$this->dati_formazioni[3];	 print_r($date_formazioni_formattate);
				for($n=0;$n<count($date_formazioni_formattate);$n++){
					echo "<option>$date_formazioni_formattate[$n]</option>";
				}
				
			?>
			</select>
			<br /> 
	 		<br />  
			<input type="submit" name="" value="Cancella formazione" style="font-weight:bold;"/>
			<input type="hidden" name="option" value="com_formazioni" style="font-weight:bold;"/>
			<input type="hidden" name="task" value="rimFormaz" style="font-weight:bold;"/>
			</form> 
		</div>
		
	<div class="formGestione"> 
		<h4 class="titoloFormGestione">Modifica le note di una formazione</h4>
		<p class="sottoTitoloFormGestione">Per modificare le note visualizzate affianco alla data inserisci il testo e premi il pulsante. Per cancellare delle note lascia il campo vuoto e clicca modifica.</p>
		
		<form name="FormModificaFormaz" method="post">
			<label for="data">Modifica le note di fianco alla formazione del</label> 
			<select name="data" style="font-weight:bold;">
			<?php
				$date_formazioni_formattate=$this->dati_formazioni[3];	 print_r($date_formazioni_formattate);
				for($n=0;$n<count($date_formazioni_formattate);$n++){
					echo "<option>$date_formazioni_formattate[$n]</option>";
				}
				
			?>
			</select>
			<br/> 
			<textarea name="note" cols="50'" rows="5"></textarea>
			<br /> 
	 		<br />  
			
			<input type="submit" name="" value="Modifica" style="font-weight:bold;"/>
			<input type="hidden" name="option" value="com_formazioni" style="font-weight:bold;"/>
			<input type="hidden" name="task" value="modNoteFormaz" style="font-weight:bold;"/>
		</form> 
	</div>
	
	<div class="formGestione"> 
		<h4 class="titoloFormGestione">Modifica le comunicazioni generali</h4>
		<p class="sottoTitoloFormGestione">Le comunicazioni generali possono essere utili per visualizzare informazioni più rilevanti o che non cambiano di formazione in formazione. Per modificarle inserisci il nuovo testo e premi il pulsante. Per cancellare completamente le comunicazioni lascia il campo vuoto e clicca modifica.</p>
		
		<form name="FormModificaComunicaz" method="post">
		<label for="comunicazioni">Testo comunicazioni:</label> 
		<br/> 
		<textarea name="comunicazioni" cols="50'" rows="5"></textarea>		 <br /> 
	 	<br />  
		
		<input type="submit" name="" value="Modifica" style="font-weight:bold;"/>
		<input type="hidden" name="option" value="com_formazioni" style="font-weight:bold;"/>
		<input type="hidden" name="task" value="modComunicaz" style="font-weight:bold;"/>
		</form> 
	</div>	
</div>	
<br />
<br />	

