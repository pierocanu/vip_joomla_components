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
?>

<h1 style="text-align:center; font-size:28px;"><?php echo $this->titolo;?> </h1>

<?php if(strcmp($this->messaggio , "clown-aggiunto") == '0'): //Messaggio di conferma clown aggiunto in servizio ?> 

<div align="center">
    <div class="titoloMessaggio">Clown aggiunto.</div>
    <p class="sottoTitoloMessaggio">Il clown <span class="datiMessaggio" ><?php echo $this->nomeClown ?></span> &eacute; stato inserito il giorno <span class="datiMessaggio" ><?php echo $this->giorno.' '.$this->mese ;?></span>. </p>
</div>

<?php endif; ?>
		
		
		
<?php if($this->messaggio  == 'clown-rimosso'): //Messaggio di conferma clown rimosso da un servizio ?>

<div>
	<div class="titoloMessaggio">Clown rimosso.</div>
    <p class="sottoTitoloMessaggio"> Il clown <span class="datiMessaggio" ><?php echo $this->nomeClown ?></span> &eacute; stato rimosso dal giorno <span class="datiMessaggio" ><?php echo $this->giorno.' '.$this->mese ;?></span>. </p>
</div>	

<?php endif; ?>



<?php if($this->messaggio  == 'servizio-etichettato'): //Messaggio di conferma etichetta servizio ?>

<div>
	<div class="titoloMessaggio">Fatto.</div>
    <p class="sottoTitoloMessaggio"> Il servizio del <span class="datiMessaggio" ><?php echo $this->giorno.' '.$this->mese ;?></span> viene ora indicato come  <span class="datiMessaggio" ><?php echo $this->etichetta ;?></span>. </p>
</div>	

<?php endif; ?>



<?php if($this->messaggio  == 'etichetta-rimossa'): //Messaggio di conferma etichetta rimossa?>

<div>
	<div class="titoloMessaggio">Fatto.</div>
    <p class="sottoTitoloMessaggio"> E' stata rimossa la dicitura per il servizio del <span class="datiMessaggio" ><?php echo $this->giorno.' '.$this->mese ;?></span> </span>.</p>
</div>	

<?php endif; ?>



<?php if($this->messaggio  == 'note-modificata'): //Messaggio di conferma note modificate ?>

<div>
	<div class="titoloMessaggio">Note modificate.</div>
    <p class="sottoTitoloMessaggio"> La note di <span class="datiMessaggio" ><?php echo $this->mese ?></span> sono state modificate.</p>
</div>	

<?php endif; ?>



<?php if($this->messaggio  == 'note-cancellate'): //Messaggio di conferma note cancellate ?>

<div>
	<div class="titoloMessaggio">Note cancellate.</div>
    <p class="sottoTitoloMessaggio"> La note di <span class="datiMessaggio" ><?php echo $this->mese ?></span> sono state cancellate.</p>
</div>	

<?php endif; ?>



<?php if($this->messaggio  == 'clown-già-in-servizio'): //Errore clown già in servizio  ?>

<div>
	<div class="titoloErrore">Errore: clown già in servizio!</div>
    <p class="sottoTitoloMessaggio"> Il clown <span class="datiMessaggio" ><?php echo $this->nomeClown ?></span> &eacute; già stato inserito nel servizio del <span class="datiMessaggio" ><?php echo $this->giorno.' '.$this->mese ;?></span>. </p>
</div>	

<?php endif; ?>



<?php if($this->messaggio  == 'clown-non-in-servizio'): //Errore clown non in servizio  ?>

<div>
	<div class="titoloErrore">Errore: il clown non c'è!</div>
    <p class="sottoTitoloMessaggio"> Il clown <span class="datiMessaggio" ><?php echo $this->nomeClown ?></span> non &eacute; inserito nel servizio del <span class="datiMessaggio" ><?php echo $this->giorno.' '.$this->mese ;?></span>! </p>
</div>	

<?php endif; ?>




<?php if($this->messaggio  == 'errore-clown-non-aggiunto' || $this->messaggio == 'errore-clow-non-rimosso'): ?>

<div>
	<div class="titoloErrore">Errore...</div>
    <p class="sottoTitoloMessaggio">Non è stato possibile <?php if($this->messaggio == 'errore-clown-non-aggiunto') {echo 'aggiungere' ;} else if($this->messaggio == 'errore-clow-non-rimosso') {echo 'rimuovere';}?> il clown da te richiesto.</p>
    <p class="sottoTitoloMessaggio">Se il problema persiste contatta l'amministratore </p>
    <p class="sottoTitoloMessaggio">Grazie!</p>
    <br />
</div>	
<?php endif; ?>



<?php if($this->messaggio  == 'errore'): //Messaggio di errore generico ?>

<div >
	<div class="titoloErrore"> Errore!! </div>
    <br />
    <p class="sottoTitoloMessaggio">Qualcosa non è andato per il verso giusto!! Per favore riprova, e se il problema persiste contatta l'amministratore. Grazie </p>
    <br />
</div>
		
<?php endif; ?>
	
<?php 
	//Visualizza servizi mese attuale e prossimo
	for($x=0; $x<=1 ; $x++){
		?>
			<div class="containerVisServizi">
			<div class="visualizzaMeseServizi">
				<h2 class="titoloTabellaServizi"><?php if($x==0){echo $this->mese_attuale; } else {echo $this->mese_prossimo;}?></h2>
				<br />
				
		<?php
		
		$html = '';
		
		$color=0;
		
		if($x == 0){
			$glimit = $this->giorni_mese_attuale;
			$servizi=$this->servizi_mese_attuale;
			$presenze_formattato=$this->presenze_mese_attuale_formattato;
		} else if($x == 1){
			$glimit = $this->giorni_mese_prossimo;
			$servizi=$this->servizi_mese_prossimo;
			$presenze_formattato=$this->presenze_mese_prossimo_formattato;
		}
		
		
		for($g=1; $g <= $glimit ; $g++){
			if($servizi[$g] != '0'){ 			//stampa la data del giorno
				
				//se il giorno è minore di 9 aggiungi uno zero
				if($g<=9){$n='0'.$g;}else{$n=$g;} 
				
				if($color % 2 == 0) {
					$html .= "\t\t<span class=color>";
				} else {
					$html .= "\t\t";
				}
				
					$html .= "<span class=\"giorni\">&nbsp;$n ".$servizi[$g]." </span>"; 
				
				
				if($presenze_formattato[$g] != '0'){					//stampa le presenze in servizio di quel giorno
								
					$html .=  $presenze_formattato[$g];
				}
				
				if($color % 2 == 0) {
					$html .= " </span>\n";
				} else {
					$html .= " \n";
				}
				
			$color++;
			}
			
		}
	
		echo $html ; ?>
	 	
			</div>
		</div> 
		<br />
		
		<?php //Visualizza note del mese ?>
			
		<div style="text-align:left; text-decoration:underline;" > NOTE per i servizi di <?php if($x==0){echo $this->mese_attuale; } else {echo $this->mese_prossimo;}?>: </div>
		<br />
		<div class="noteServizi">
			<?php if($x==0){echo $this->note_mese_attuale; } else {echo $this->note_mese_prossimo;}?>
		</div> 
		<br /> 
		<br />
	
		<?php
	}
		?>



 
	
<!-- Visualizza form di gestione -->
	<div class="containerGestione"> 
 
	<?php 
	
	$luogo = "";
	$data = $this->num_giorno_attuale."-".$this->num_mese_attuale."-".$this->anno_attuale;
	$ora = "";
	
	$uri = $_SERVER["REQUEST_URI"];
	$uriExploded = explode('/', $uri); 
	
	for($t=0; $t<count($uriExploded); $t++){ 
	
		if(strcmp($uriExploded[$t], "turni-cagliari")==0){
			
			$ora = "16:00:00";
			$luogo = "Brotzu+(ang.+via+Piero+della+Francesca),+Cagliari,+CA,+Italia";
			
		}else if(strcmp($uriExploded[$t], "turni-ss")==0){
			
			$ora = "15:00:00";
			$luogo = "Cliniche+Universitarie,+Sassari,+SS,+Italia";
			
		}
	 
	}
	 
	$src = "http://greenshare-beta.appspot.com/forms/widget?a=".$luogo."&data=".$data."+".$ora;
	?>
	
	
<iframe src="<?php echo($src) ?>" class="clacsoon" frameborder="0" scrolling="no"></iframe>	
	
	
		<!-- Aggiunta clown-->
	
		<div class="formGestione"> 
			<h4 class="titoloFormGestione">Iscrizione in un servizio </h4>
			<p class="sottoTitoloFormGestione">Indica nome e data e clicca su aggiungi. Se la data non è in elenco verrà aggiunta immediatamente. I nomi in elenco sono in ordine alfabetico.</p>
			<form name="FormAggiungiClown" method="post">

			<label for="nome">Iscrivi il clown:</label>  
			<select name="nome" style="font-weight:bold;">
			 	<?php for($n=0;$n<count($this->nomiClowns); $n++){
			 		echo "<option>".$this->nomiClowns[$n]."</option>";
			 	} ?> 
			</select>
			
			<label for="gg">nel servizio del</label> 
			<select name="gg" style="font-weight:bold;">
			 	<?php for($n=1;$n<=31; $n++){
			 		echo "<option ";
			 		if($n == $this->num_giorno_attuale){
						echo "selected";
					}
			 		echo ">$n</option>";
			 	} ?> 
			</select>
			<select name="mm" style="font-weight:bold;">
				<option><?php echo $this->mese_attuale; ?></option> 
				<option><?php echo $this->mese_prossimo; ?></option> 
			</select>
			
			<br /> 
	 		<br />  
			<input type="submit" name="" value="Aggiungi!" style="font-weight:bold;"/>
			<input type="hidden" name="option" value="com_turniservizi" style="font-weight:bold;"/>
			<input type="hidden" name="task" value="aggClown" style="font-weight:bold;"/>
	
			</form>
		</div>


		<!-- Rimozione clown-->	
		<div class="formGestione"> 
			<h4 class="titoloFormGestione">Cancellazione da un servizio </h4>
			<p class="sottoTitoloFormGestione">Indica il nome e la data del servizio e clicca su rimuovi. Se il clown è l'unico in servizio la data potrebbe essere rimossa dall'elenco.</p>
	
			<form name="FormRimuoviClown" method="post">
				<label for="nome">Cancella il clown:</label>  
				<select name="nome" style="font-weight:bold;">
				 	<?php for($n=0;$n<count($this->nomiClowns); $n++){
				 		echo "<option>".$this->nomiClowns[$n]."</option>";
				 	} ?> 
				</select>
				
				<label for="data">dal servizio di </label> 
				<select name="data" style="font-weight:bold;">
					
					<optgroup label="<?php echo $this->mese_attuale; ?>"></optgroup>
					<?php 
						for($n=1;$n<count($this->servizi_mese_attuale);$n++){
							if($this->servizi_mese_attuale[$n]!= '0'){
								echo "<option>".$this->servizi_mese_attuale[$n]." $n ".$this->mese_attuale."</option>";
							}
						}
					?>
					
					<optgroup label="<?php echo $this->mese_prossimo; ?>"></optgroup>
					<?php 
						for($n=1;$n<count($this->servizi_mese_prossimo);$n++){
							if($this->servizi_mese_prossimo[$n]!= '0'){
								echo "<option>".$this->servizi_mese_prossimo[$n]." $n ".$this->mese_prossimo."</option>";
							}
						}
					?>
					
				</select>
		
				<br /> 
		 		<br />  
				<input type="submit" name="" value="Rimuovi!" style="font-weight:bold;"/>
				<input type="hidden" name="option" value="com_turniservizi" style="font-weight:bold;"/>
				<input type="hidden" name="task" value="rimClown" style="font-weight:bold;"/>
			</form>
		</div>
	
		<!-- Etichetta servizio-->	
		<div class="formGestione"> 
			<h4 class="titoloFormGestione">Dai un etichetta a un servizio </h4>
			<p class="sottoTitoloFormGestione">Indica un servizio "particolare", come GNR, servizio di Natale ecc. Per cancellare una dicitura lasciare il campo in biano e cliccare su OK.</p>
					
			<form name="FormSpecificaServizio" method="post">
				<label for="data">Etichetta il servizio di</label>  
				<select name="data" style="font-weight:bold;">
					
					<optgroup label="<?php echo $this->mese_attuale; ?>"></optgroup>
					<?php 
						for($n=1;$n<count($this->servizi_mese_attuale);$n++){
							if($this->servizi_mese_attuale[$n]!= '0'){
								echo "<option>".$this->servizi_mese_attuale[$n]." $n ".$this->mese_attuale."</option>";
							}
						}
					?>
					
					<optgroup label="<?php echo $this->mese_prossimo; ?>"></optgroup>
					<?php 
						for($n=1;$n<count($this->servizi_mese_prossimo);$n++){
							if($this->servizi_mese_prossimo[$n]!= '0'){
								echo "<option>".$this->servizi_mese_prossimo[$n]." $n ".$this->mese_prossimo."</option>";
							}
						}
					?>
					
				</select>
		
				<br /> 
				<label for="etichetta">scrivendoci affianco:</label> 
				<input type="text" name="etichetta" value="specifica" style="font-weight:bold;"/>
				
				<br /> 
		 		<br />  
				<input type="submit" name="" value="Ok!" style="font-weight:bold;"/>
				<input type="hidden" name="option" value="com_turniservizi" style="font-weight:bold;"/>
				<input type="hidden" name="task" value="etichettaServizio" style="font-weight:bold;"/>
			</form>
		</div>

	
		<!-- Modifica note-->	
		<div class="formGestione"> 
			<h4 class="titoloFormGestione">Modifica le note di un mese </h4>
			<p class="sottoTitoloFormGestione">Modifica le note per i servizi del mese. Utile per indicare ad esempio eventuali ritardi o imprevisti per un servizio. 
				Il testo inserito sostituirà completamente quello visualizzato sotto l'elenco dei servizi. Per cancellare quello già
				visualizzato è sufficiente cancellare completamente il testo e premere Modifica note.	
			</p>
			
			<form name="FormModificaNote" method="post">
				
				<label for="note">Note:</label> 
				<br/> 
				<textarea name="note" cols="55" rows="4">Nuovo testo delle note. (Questo testo sostituirà quello del rispettivo mese.)</textarea>
				
				<br/> 
				<label for="mm">Mese:</label> 
				<br/> 
				<select name="mm" style="font-weight:bold;">
					<option><?php echo $this->mese_attuale; ?></option> 
					<option><?php echo $this->mese_prossimo; ?></option> 
				</select>
				
				<br /> 
		 		<br />  
				<input type="submit" name="" value="Modifica note" style="font-weight:bold;"/>
				<input type="hidden" name="option" value="com_turniservizi" style="font-weight:bold;"/>
				<input type="hidden" name="task" value="modificaNote" style="font-weight:bold;"/>
			</form>

		</div>
	</div> 

	<br />




	