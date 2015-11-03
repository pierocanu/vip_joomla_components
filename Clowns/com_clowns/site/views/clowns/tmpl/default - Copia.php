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
?>


<h1 style="text-align:center; font-size:28px;"><?php echo $this->titolo; ?> </h1>

<?php if (strcmp($this->messaggio, "clown-aggiunto") == '0'): //Messaggio di conferma clown aggiunto in servizio ?>

    <div align="center">
        <div class="titoloMessaggio">Clown aggiunto.</div>
        <p> Il clown <span class="nomeClownInMessaggio"><?php echo $this->nomeClown; ?> (<?php echo $this->nome;
                echo " ";
                echo $this->cognome; ?>)</span> &eacute; stato inserito nell'elenco. </p>
    </div>

<?php endif; ?>



<?php if ($this->messaggio == 'clown-rimosso'): //Messaggio di conferma clown rimosso da un servizio ?>

    <div>
        <div class="titoloMessaggio">Clown rimosso.</div>
        <p> Il clown <span class="nomeClownInMessaggio"><?php echo $this->nomeClown ?></span> &eacute; stato rimosso
            dall'elenco. </p>
    </div>

<?php endif; ?>



<?php if ($this->messaggio == 'clown-già-in-elenco'): //Errore clown già in elenco  ?>

    <div>
        <div class="titoloErrore">Errore: clown già in elenco</div>
        <p> Il clown <span class="nomeClownInMessaggio"><?php echo $this->nomeClown ?></span> &eacute; già stato
            inserito nell'elenco. </p>
    </div>

<?php endif; ?>



<?php if ($this->messaggio == 'clown-non-in-elenco'): //Errore clown non in servizio  ?>

    <div>
        <div class="titoloErrore">Errore: clown non trovato!</div>
        <p> Il clown <span class="nomeClownInMessaggio"><?php echo $this->nomeClown ?></span> non sembra inserito
            nell'elenco!! </p>
    </div>

<?php endif; ?>




<?php if ($this->messaggio == 'errore-clown-non-aggiunto' || $this->messaggio == 'errore-clow-non-rimosso'): ?>

    <div>

        <div class="titoloErrore">Errore...</div>
        <p>Non è stato possibile <?php if ($this->messaggio == 'errore-clown-non-aggiunto') {
                echo 'aggiungere';
            } else if ($this->messaggio == 'errore-clow-non-rimosso') {
                echo 'rimuovere';
            } ?> il clown da te richiesto.</p>

        <p>Forse hai sbagliato qualcosa, ad esempio il nome del clown o la data, per favore controlla e riprova, se il
            problema persiste contatta l'amministratore </p>

        <p>Grazie!</p>
        <br/>
    </div>
<?php endif; ?>



<?php if ($this->messaggio == 'errore'): //Messaggio di errore generico ?>

    <div>

        <div class="titoloErrore"> ERRORE!!</div>
        <br/>

        <p> Qualcosa non è andato per il verso giusto!! Per favore riprova, e se il problema persiste contatta
            l'amministratore. Grazie </p>
        <br/>
    </div>
<?php endif; ?>


<div class="elencoClown">

    <?php

    //Visualizza elenco clowns
    $nomiClowns = $this->nomiClowns;
    sort($nomiClowns);
    for ($n = 0; $nomiClowns[$n]; $n++) {
        echo $nomiClowns[$n];
        echo ", ";
    }
    echo "<br /> ";
    echo "<br /> ";

    ?>

</div>


<!-- VISUALIZZA FORM DI GESTIONE-->
<div id="containerGestione">

    <!-- Aggiunta clown-->

    <div class="formGestione">
        <h4 class="titoloFormGestione">Aggiungi un clown nell'elenco</h4>

        <p class="sottoTitoloFormGestione">Inserisci i dati e clicca Aggiungi clown</p>

        <form name="FormAggiungiClown" method="post">

            <label for="nome"> Nome </label>
            <input type="text" name="nome" value="" style="font-weight:bold;"/>

            <br/>
            <label for="cognome"> Cognome </label>
            <input type="text" name="cognome" value="" style="font-weight:bold;"/>

            <br/>
            <label for="nome clown"> Nome Clown</label>
            <input type="text" name="nomeClown" value="" style="font-weight:bold;"/>

            <br/>
            <br/>
            <input type="submit" name="" value="Aggiungi clown!" style="font-weight:bold;"/>
            <input type="hidden" name="option" value="com_clowns" style="font-weight:bold;"/>
            <input type="hidden" name="task" value="aggClown" style="font-weight:bold;"/>


        </form>
    </div>


    <!-- Rimozione clown-->

    <div class="formGestione">
        <h4 class="titoloFormGestione">Rimuovi clown dall'elenco</h4>

        <p class="sottoTitoloFormGestione">Seleziona il clown da rimuovere.</p>

        <form name="FormRimuoviClown" method="post">

            <br/>
            <label for="nomeClown"> Rimuovi il clown:</label>
            <select name="nomeClown" style="font-weight:bold;">
                <?php
                for ($n = 0; $n < count($nomiClowns); $n++) {
                    echo "<option>$nomiClowns[$n]</option>";
                }
                ?>
            </select>

            <br/>
            <br/>
            <input type="submit" name="" value="Rimuovi clown!" style="font-weight:bold;"/>
            <input type="hidden" name="option" value="com_clowns" style="font-weight:bold;"/>
            <input type="hidden" name="task" value="rimClown" style="font-weight:bold;"/>

        </form>
    </div>

</div>

<br/>
<br/>
<br/>



