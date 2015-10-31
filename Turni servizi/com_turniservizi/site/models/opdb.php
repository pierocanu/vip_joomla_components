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

class OpDBModelOpDB extends JModelItem {//principali funzioni sul db

	function serviziInDb($m, $a) {
		//restituisce i servizi in quel mese già inseriti nel db che hanno almeno un clown o sono etichettati

		$model = new TurniServiziModelTurniServizi();
		$modelData = new DataModelData();

		$suff_db = $model -> getSuffissoDB();
		$tabella = "#__servizi_" . $suff_db;

		$giorni_settimana = $modelData -> getInfo('GiorniSettimana');
		$giorni_nel_mese = $modelData -> days_in_month($m, $a);
		$servizi = array_fill(1, $giorni_nel_mese, 0);

		//Se il mese è minore di 9 aggiungi uno zero

		if ($m <= 9) {$m = "0" . $m;
		}

		$db = JFactory::getDBO();
		$query = $db -> getQuery(true);
		$query = "SELECT * FROM `$tabella` WHERE  YEAR(Data) = $a AND MONTH(Data) = $m ORDER BY DAY(Data)";
		$db -> setQuery($query);
		$db -> query();
		$rows = $db -> loadObjectList();

		if ($rows != NULL) {

			foreach ($rows as $row) {

				$tmp = explode('-', $row -> Data);
				$giorno = $tmp[2];

				if ($giorno <= 9) {
					$giorno = substr($giorno, 1);
				}
				$servizi[$giorno] = $giorni_settimana[date("w", strtotime("$a-$m-$giorno"))];
				$servizi[$giorno] = $servizi[$giorno] . " " . $row -> Etichetta;

			}

			return $servizi;
		} else {

			return NULL;

		}

	}

	function presenzeServizi($m, $a) {
		//restituisce le presenze nei servizi del mese $m

		$model = new TurniServiziModelTurniServizi();
		$modelData = new DataModelData();

		$suff_db = $model -> getSuffissoDB();
		$tabella = "#__servizi_" . $suff_db;

		$giorni_settimana = $modelData -> getInfo('GiorniSettimana');
		$giorni_nel_mese = $modelData -> days_in_month($m, $a);
		$presenze = array_fill(1, $giorni_nel_mese, 0);

		//Se il mese (e il giorno) è minore di 9 aggiungi uno zero
		if ($g <= 9 && strlen($g) < 2) {$g = "0" . $g;
		}
		if ($m <= 9 && strlen($m) < 2) {$m = "0" . $m;
		}

		$db = JFactory::getDBO();
		$query = $db -> getQuery(true);
		$query = "SELECT * FROM `$tabella` WHERE  YEAR(Data) = $a AND MONTH(Data) = $m ORDER BY DAY(Data)";
		$db -> setQuery($query);
		$db -> query();
		$rows = $db -> loadObjectList();

		if ($rows != NULL) {

			foreach ($rows as $row) {
				$tmp = explode('-', $row -> Data);
				$giorno = $tmp[2];
				if ($giorno <= 9) {
					$giorno = substr($giorno, 1);
				}

				$nomi = $row -> Nomi;

				if (strcasecmp($nomi, '-') == '0') {
					$presenze[$giorno] = 0;
				} else {
					$presenze[$giorno] = $row -> Nomi;
				}
			}

			return $presenze;
		} else {

			return NULL;

		}

	}

	function aggClown($nome, $giorno, $mese, $anno) {
		//aggiunge il clown nel database

		$modelData = new DataModelData();
		$limit = $modelData -> days_in_month($mese, $anno);
		if ($giorno > $limit) {
			return 1;
		}

		$nome = trim($nome);
		//Elimina gli spazi vuoti da inizio e fine
		$nome = str_replace(' ', '_', $nome);
		//Sostituisce gli spazi in mezzo con '_'

		$model = new TurniServiziModelTurniServizi();

		$suff_db = $model -> getSuffissoDB();
		$tabella = "#__servizi_" . $suff_db;
		$db = JFactory::getDBO();
		$query = $db -> getQuery(true);

		//Se il mese (e il giorno) è minore di 9 aggiungi uno zero
		if ($giorno <= 9 && strlen($giorno) < 2) {$giorno = "0" . $giorno;
		}
		if ($mese <= 9 && strlen($mese) < 2) {$mese = "0" . $mese;
		}

		//Verifica se esiste già la tabella con il campo di quel mese e giorno o, se esiste, se è vuota
		$query = "SELECT * FROM `$tabella` WHERE  YEAR(Data) = $anno AND MONTH(Data)= $mese AND DAY(Data) = $giorno";
		$db -> setQuery($query);
		$db -> query();
		$rows = $db -> loadObjectList();

		//recupera la mail del clown:
		$elenco = $this -> leggiElencoClowns();
		$nomiClown = $elenco['nomiClowns'];

		$mail = $elenco['mail'];
		for ($i = 0; $i < count($nomiClown); $i++) {
			if (strcasecmp($nome, $nomiClown[$i]) == '0') {
				$mail_clown = $mail[$i];
			}
		}

		//Richiama la mail per poter inviare una notifica allo staff e prepara il contenuto
		$mail_staff = $model -> getMailStaff();
		$mail_bcc = $model -> getMailBcc();

		// Headers, subject e message staff 
		$headers_staff .= "From: Gestione Servizi Online - vipsardegna.it <gestioneservizionline@vipsardegna.it>\r\n";
		$headers_staff .= "Reply-To: " . $mail_clown . "\r\n";
		$headers_staff .= "Bcc: " . $mail_bcc . "\r\n";

		$subject_staff = "Conferma inserimento clown " . $nome . " in un servizio";

		$message_staff .= "Ciao staff Ospedale!\n\n" . $nome . " e' stato aggiunto/a nel servizio del giorno " . $giorno . " " . $modelData -> meseFromNumToText($mese) . ".\n\n";
		$message_staff .= "Un messaggio di notifica e' gia' stato inviato al suo indirizzo " . $mail_clown . " al quale potete scrivere rispondendo a questa mail. \n\n";
		$message_staff .= "Grazie, \nil servizio online di gestione turni automatico di Split.\n\n";

		// Headers, subject e message clown 
		$headers_clown .= "From: Gestione Servizi Online - vipsardegna.it <gestioneservizionline@vipsardegna.it>\r\n";
		$headers_clown .= "Reply-To: " . $mail_staff . "\r\n";

		$subject_clown = "Clown " . $nome . ", sei stato aggiunto/a in un servizio";

		$message_clown .= "Ciao " . $nome . ", il tuo nome e' stato aggiunto nel servizio del giorno " . $giorno . " " . $modelData -> meseFromNumToText($mese) . ".\n\n";
		$message_clown .= "Per comunicare con lo staff ospedale puoi scrivere all'indirizzo " . $mail_staff . " oppure rispondere a quesa mail. \n\n";
		$message_clown .= "Grazie, \nil servizio online di gestione turni automatico di Split.\n\n";

		if ($rows == NULL) {

			$query = 'CREATE TABLE IF NOT EXISTS ' . $tabella . '( `Data` DATE NOT NULL, `Nomi` VARCHAR(250), `Etichetta` VARCHAR(50) )';
			$db -> setQuery($query);
			$db -> query();

			//inserisce i dati (per la prima volta)
			$query = "INSERT INTO `$tabella` (Data, Nomi, Etichetta) VALUES (\"$anno-$mese-$giorno\", \"$nome\", NULL )";
			$db -> setQuery($query);
			$db -> query();

			mail($mail_staff, $subject_staff, $message_staff, $headers_staff);
			//mail allo staff
			mail($mail_clown, $subject_clown, $message_clown, $headers_clown);
			//mail al clown

			return 0;

		} else {

			foreach ($rows as $row) {
				$nomi = $row -> Nomi;
				$etichetta = $row -> Etichetta;
			}
			$nomi_esplosi = explode(' ', $nomi);

			//se c'è "-" vuol dire che il servizio è vuoto ma etichettato. Inserisci il nome del clown al posto del "-"
			if (strcasecmp($nomi_esplosi[0], '-') == '0') {

				//fa l'update del campo
				$query = "UPDATE $tabella SET Nomi='$nome' WHERE YEAR(Data) = $anno AND MONTH(Data)= $mese AND DAY(Data) = $giorno";
				$db -> setQuery($query);
				$db -> query();

				mail($mail_staff, $subject_staff, $message_staff, $headers_staff);
				//mail allo staff
				mail($mail_clown, $subject_clown, $message_clown, $headers_clown);
				//mail al clown

				return 0;
			} else {

				//controlla che il clown non sia già in quel servizio.

				for ($n = 0; $n < count($nomi_esplosi); $n++) {
					if (strcasecmp($nomi_esplosi[$n], $nome) == '0') {
						return '1';
					}
				}

				$nomiNuovo = $nomi . " " . $nome;
				//fa l'update del campo
				$query = "UPDATE $tabella SET Nomi='$nomiNuovo' WHERE YEAR(Data) = $anno AND MONTH(Data)= $mese AND DAY(Data) = $giorno";
				$db -> setQuery($query);
				$db -> query();

				mail($mail_staff, $subject_staff, $message_staff, $headers_staff);
				//mail allo staff
				mail($mail_clown, $subject_clown, $message_clown, $headers_clown);
				//mail al clown

				return 0;
			}
		}

	}

	function rimClown($nome, $giorno, $mese, $anno) {

		$nome = trim($nome);
		//Elimina gli spazi vuoti da inizio e fine
		$nome = str_replace(' ', '_', $nome);
		//Sostituisce gli spazi in mezzo con '_'

		$model = new TurniServiziModelTurniServizi();
		$modelData = new DataModelData();

		$suff_db = $model -> getSuffissoDB();
		$tabella = "#__servizi_" . $suff_db;
		$db = JFactory::getDBO();
		$query = $db -> getQuery(true);

		//Se il mese (e il giorno) è minore di 9 aggiungi uno zero
		if ($giorno <= 9 && strlen($giorno) < 2) {$giorno = "0" . $giorno;
		}
		if ($mese <= 9 && strlen($mese) < 2) {$mese = "0" . $mese;
		}

		//Recupera i nomi dei clown presenti in servizio
		$query = "SELECT * FROM `$tabella` WHERE YEAR(Data) = $anno AND MONTH(Data) = $mese AND DAY(Data) = $giorno ";
		$db -> setQuery($query);
		$db -> query();
		$rows = $db -> loadObjectList();

		//Richiama la mail per poter inviare una notifica allo staff e prepara il contenuto
		$mail_staff = $model -> getMailStaff();
		$mail_bcc = $model -> getMailBcc();

		//recupera la mail del clown:
		$elenco = $this -> leggiElencoClowns();
		$nomiClown = $elenco['nomiClowns'];
		$elenco_mail = $elenco['mail'];

		for ($i = 0; $i < count($nomiClown); $i++) {
			if (strcasecmp($nome, $nomiClown[$i]) == '0') {
				$mail_clown = $elenco_mail[$i];
			}
		}
 
		// Headers, subject e message staff 
		$headers_staff .= "From: Gestione Servizi Online <gestioneservizionline@vipsardegna.it>\r\n";
		$headers_staff .= "Reply-To: " . $mail_clown . "\r\n";
		$headers_staff .= "Bcc: " . $mail_bcc . "\r\n";

		$subject_staff = "Conferma cancellazione clown " . $nome . "dal servizio";

		$message_staff = "Ciao staff Ospedale!\n\n" .$nome. " e' stato rimosso/a dal servizio del giorno " . $giorno . " " . $modelData -> meseFromNumToText($mese) . ".\n\n";
		$message_staff .= "Un messaggio di notifica e' gia' stato inviato al suo indirizzo " . $mail_clown . " al quale potete scrivere rispondendo a questa mail. \n\n";
		$message_staff .= "Grazie, \nil servizio online di gestione turni automatico di Split.\n\n";

		// Headers, subject e message clow 
		$headers_clown .= "From: Gestione Servizi Online <gestioneservizionline@vipsardegna.it>\r\n";
		$headers_clown .= "Reply-To: " . $mail_staff . "\r\n";

		$subject_clown = "Il tuo nome e' stato rimosso da un servizio";

		$message_clown = "Ciao " . $nome . ",  il tuo nome e' stato cancellato dal servizio del giorno " . $giorno . " " . $modelData -> meseFromNumToText($mese) . ".\n\n";
		$message_clown .= "Per comunicare con lo staff ospedale puoi scrivere all'indirizzo " . $mail_staff . " oppure rispondere a quesa mail. \n\n";
		$message_clown .= "Grazie, \nil servizio online di gestione turni automatico di Split.\n\n";

		if ($rows == NULL) {

			return 1;
			//se non c'è il mese nel db

		} else {
			foreach ($rows as $row) {
				$nomi = $row -> Nomi;
				$etichetta = $row -> Etichetta;
			}

			if ($nomi == '-') {

				return 1;
				//Il servizio anche se ha un etichetta è vuoto.

			} else {

				//Converti il campo in array di stringhe
				$nomiVecchi = explode(' ', $nomi);

				//verifica che il nome sia nel db
				$check = 0;
				for ($i = 0; $i < count($nomiVecchi); $i++) {
					if ($nomiVecchi[$i] == $nome) {//Il clown è nel servizio. continua oltre.
						$check = 1;
						$i = count($nomiVecchi) + 1;
					}

				}

				if ($check == 0) {

					return 1;

				} else {

					//elimino nome dall'array

					//se c'è un solo nome elimina la riga nel db ma solo se non ci sono etichette
					if ($nomiVecchi[1] == NULL && $etichetta == NULL) {//un solo nome e nessuna etichetta
						$query = "DELETE FROM `$tabella` WHERE Nomi='$nomi' AND YEAR(Data) = $anno AND MONTH(Data) = $mese AND DAY(Data) = $giorno ";
						$db -> setQuery($query);
						$db -> query();
						mail($mail_staff, $subject_staff, $message_staff, $headers_staff);
						//Mail allo staff
						mail($mail_clown, $subject_clown, $message_clown, $headers_clown);
						//Mail al clown

						return 0;

					} else if ($nomiVecchi[1] == NULL && $etichetta != NULL) {//se c'è un solo nome e nessuna etichetta
						$query = "UPDATE `$tabella` SET Nomi='-' WHERE YEAR(Data) = $anno AND MONTH(Data) = $mese AND DAY(Data) = $giorno ";
						$db -> setQuery($query);
						$db -> query() == 1;

						mail($mail_staff, $subject_staff, $message_staff, $headers_staff);
						//Mail allo staff
						mail($mail_clown, $subject_clown, $message_clown, $headers_clown);
						//Mail al clown
						return 0;
					} else {

						for ($i = 0; $nomiVecchi[$i] != NULL; $i++) {
							if ($nomiVecchi[$i] == $nome) {
								while ($nomiVecchi[$i] != NULL) {
									$nomiVecchi[$i] = $nomiVecchi[$i + 1];
									$i++;
								}
							}
						}
					}

					// Ricostruisci stringa unica per il db

					for ($n = 1; $nomiVecchi[$n] != NULL; $n++) {
						$nomiVecchi[0] = $nomiVecchi[0] . " " . $nomiVecchi[$n];
					}

					$nomiNuovi = $nomiVecchi[0];

					//Modifica nomi in campo
					$query = "UPDATE `$tabella` SET Nomi=\"$nomiNuovi\" WHERE YEAR(Data) = $anno AND MONTH(Data) = $mese AND DAY(Data) = $giorno ";
					$db -> setQuery($query);
					$db -> query();

					mail($mail_staff, $subject_staff, $message_staff, $headers_staff);
					//Mail allo staff
					mail($mail_clown, $subject_clown, $message_clown, $headers_clown);
					//Mail al clown

					return 0;

				}
			}
		}

	}

	function etichettaServizio($etichetta, $giorno, $mese, $anno) {

		$etichetta = trim($etichetta);
		//Elimina gli spazi vuoti da inizio e fine

		$model = new TurniServiziModelTurniServizi();

		$suff_db = $model -> getSuffissoDB();
		$tabella = "#__servizi_" . $suff_db;
		$db = JFactory::getDBO();
		$query = $db -> getQuery(true);

		//Se il mese (e il giorno) è minore di 9 aggiungi uno zero
		if ($giorno <= 9 && strlen($giorno) < 2) {$giorno = "0" . $giorno;
		}
		if ($mese <= 9 && strlen($mese) < 2) {$mese = "0" . $mese;
		}

		//verifica se esiste il servizio (se non c'è nel db crealo.)
		$query = "SELECT * FROM `$tabella` WHERE YEAR(Data) = $anno AND MONTH(Data) = $mese AND DAY(Data) = $giorno ";
		$db -> setQuery($query);
		$db -> query();
		$rows = $db -> loadObjectList();

		if ($rows == NULL) {//non esiste la formazione nel DB
			if (strlen($etichetta) > 1) {
				$etichetta = "(" . $etichetta . ")";
				$query = "INSERT INTO `$tabella` (`Data`, `Nomi`, `Etichetta`) VALUES ('$anno-$mese-$giorno', '-', '$etichetta')";
				$db -> setQuery($query);
				$db -> query();
				return 0;
			}
		} else {
			if (strlen($etichetta) < 1) {//cancella l'etichetta

				//Se non ci sono clown segnati per questo servizo cancellalo dal db...
				foreach ($rows as $row) {
					$Nomi = $row -> Nomi;
				}
				if ($Nomi == NULL || $Nomi == '') {
					$query = "DELETE FROM $tabella WHERE YEAR(Data) = $anno AND MONTH(Data)= $mese AND DAY(Data) = $giorno";
					$db -> setQuery($query);
					$db -> query();
					return 1;
				} else {//...altrimenti cancella solo l'etichetta
					$query = "UPDATE $tabella SET Etichetta=NULL WHERE YEAR(Data) = $anno AND MONTH(Data)= $mese AND DAY(Data) = $giorno";
					$db -> setQuery($query);
					$db -> query();
					return 1;
				}

			} else {//modifica l'etichetta
				$etichetta = "(" . $etichetta . ")";
				$query = "UPDATE $tabella SET Etichetta=\"$etichetta\" WHERE YEAR(Data) = $anno AND MONTH(Data)= $mese AND DAY(Data) = $giorno";
				$db -> setQuery($query);
				$db -> query();
				return 0;
			}
		}
		return NULL;

	}

	function modificaNote($note, $mese, $anno) {
		$model = new TurniServiziModelTurniServizi();

		$suff_db = $model -> getSuffissoDB();
		$tabella = "#__noteServizi_" . $suff_db;
		$db = JFactory::getDBO();
		$query = $db -> getQuery(true);

		$ret = 0;
		if ($note == NULL || $note == '' || $note == ' ') {
			$note = 'Non ci sono note';
			$ret = 1;
		}

		$note = str_replace("\n", "<br />", $note);

		//Se il mese (e il giorno) è minore di 9 aggiungi uno zero
		if ($giorno <= 9 && strlen($giorno) < 2) {$giorno = "0" . $giorno;
		}
		if ($mese <= 9 && strlen($mese) < 2) {$mese = "0" . $mese;
		}

		//Verifica se esiste già la tabella con il campo di quel mese o, se esiste, se è vuota
		$query = "SELECT * FROM `$tabella` WHERE  Mese = $mese AND Anno = $anno";
		$db -> setQuery($query);
		$db -> query();
		$rows = $db -> loadObjectList();

		if ($rows == NULL) {

			$query = 'CREATE TABLE ' . $tabella . ' ( Mese INT(12), Anno VARCHAR(4), Note TEXT )';
			$db -> setQuery($query);
			$db -> query();

			//Continua inserendo i dati
			$query = "INSERT INTO `$tabella` (Mese, Anno, Note) VALUES (\"$mese\", \"$anno\", \"$note\")";
			$db -> setQuery($query);
			$db -> query();
			return $ret;

		} else {//altrimenti fai direttamente l'upgrade della tabella

			$query = "UPDATE $tabella SET Note='$note' WHERE Mese = $mese AND Anno = $anno";
			$db -> setQuery($query);
			$db -> query();
			return $ret;
		}

	}

	function leggiNote($num_mese, $anno) {
		$model = new TurniServiziModelTurniServizi();

		$suff_db = $model -> getSuffissoDB();
		$tabella = "#__noteServizi_" . $suff_db;
		$db = JFactory::getDBO();
		$query = $db -> getQuery(true);

		//Se il mese è minore di 9 aggiungi uno zero
		if ($num_mese <= 9 && strlen($num_mese)) {$num_mese = "0" . $num_mese;
		}

		//Verifica se esiste già la tabella con il campo di quel mese o, se esiste, se è vuota
		$query = "SELECT * FROM `$tabella` WHERE  Mese = $num_mese AND Anno = $anno";
		$db -> setQuery($query);
		$db -> query();
		$rows = $db -> loadObjectList();

		if ($rows == NULL) {
			//NESSUNA NOTA
			return 'Nessuna nota';

		} else {
			foreach ($rows as $row) {
				$note = $row -> Note;
			}
			return $note;
		}
	}

	function leggiElencoClowns() {

		$tabella = "#__clowns";
		$db = JFactory::getDBO();
		$query = $db -> getQuery(true);

		//Recupera i nomi dei clown in elenco (nomi, cognomi e nomi clown)
		$query = "SELECT * FROM `$tabella` ORDER BY Nome_Clown";
		$db -> setQuery($query);
		$db -> query();

		$rows = $db -> loadObjectList();

		if ($rows != NULL) {//La tabella non è vuota
			$n = 0;
			foreach ($rows as $row) {

				$nomi[$n] = $row -> Nome;
				$nomi[$n] = str_replace('_', ' ', $nomi[$n]);

				$cognomi[$n] = $row -> Cognome;
				$cohnomi[$n] = str_replace('_', ' ', $cognomi[$n]);

				$nomiClowns[$n] = $row -> Nome_Clown;
				$nomiClowns[$n] = str_replace('_', ' ', $nomiClowns[$n]);

				$mail[$n] = $row -> Mail;

				$n++;
			}

			$clowns = array('nomi' => $nomi, 'cognomi' => $cognomi, 'nomiClowns' => $nomiClowns, 'mail' => $mail);
			return $clowns;

		} else {
			return NULL;
		}

	}

}
?>