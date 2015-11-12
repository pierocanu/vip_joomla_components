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


<script type="text/javascript">

    window.addEvent('domready', function () {
        $$('.hasTip').each(function (el) {
            var title = el.get('title');
            if (title) {
                var parts = title.split('::', 2);
                el.store('tip:title', parts[0]);
                el.store('tip:text', parts[1]);
            }
        });
        var JTooltips = new Tips($$('.hasTip'), {maxTitleChars: 50, fixed: false});
    });
    function keepAlive() {
        var myAjax = new Request({method: "get", url: "index.php"}).send();
    }
    window.addEvent("domready", function () {
        keepAlive.periodical(840000);
    });
</script>

<h1 style="text-align:center; font-size:28px;">
    <?php

    echo $this->titolo;
    ?>
</h1>

<p style="margin-bottom: 4px; font-size: 18px;">
    <?php
    $clownListDesc = JFactory::getApplication()->input->get('clownListDesc', '', 'TEXT');
    echo $clownListDesc;
    ?>
</p>
<br/>


<div class="elencoClown">

    <?php


    //Visualizza elenco clowns

    $ids = $this->ids;
    $nomiClowns = $this->nomiClowns;
    $nomi = $this->nomi;
    $cognomi = $this->cognomi;
    $mails = $this->mails;
    $cell = $this->cell;
    $statiSocio = $this->statiSoci;
    $vips = $this->vips;

    $countNomi = count($nomiClowns);

    $statiSocioDispIds = $this->statiSocioDispIds;
    $statiSocioDispNomi = $this->statiSocioDispNomi;

    $vipDisponibiliIds = $this->vipDisponibiliIds;
    $vipDisponibiliNomi = $this->vipDisponibiliNomi;

    ?>

    <form>
        <label for="statoSocioToView">Visualizza ogni: </label>
        <select id="statoSocioToView" name="statoSocioToView" onchange="this.form.submit()"
                style="font-weight:bold;">
            <option value="-1">Socio qualsiasi</option>
            <?php
            for ($n = 0; $n < count($statiSocioDispNomi); $n++)
            {
                echo("<option ");
                echo("value=\"".$statiSocioDispIds[$n]."\" ");
                if($statiSocioDispIds[$n] == $this->statoSocioToView)
                {
                    echo("selected ");
                }
                echo(">".$statiSocioDispNomi[$n]." </option>");
            }
            ?>
        </select>

        <select id="vipToView" name="vipToView" onchange="this.form.submit()"
                style="font-weight:bold;">
            <option value="-1">Di ogni Vip disponibile</option>
            <?php
            for ($n = 0; $n < count($vipDisponibiliNomi); $n++)
            {
                echo("<option ");
                echo("value=\"".$vipDisponibiliIds[$n]."\" ");
                if($vipDisponibiliIds[$n] == $this->vipToView)
                {
                    echo("selected ");
                }
                echo(">".$vipDisponibiliNomi[$n]." </option>");
            }
            ?>
        </select>

        <input type="hidden" name="option" value="com_clowns"/>
        <input type="hidden" name="task" value="visualizzaelencoclowns.display"/>
    </form>
    <br/>

    <?php


    if ($nomiClowns == NULL)
    {
        ?>
        <p">Nessun clown trovato con le opzioni selezionate<p/>
        <?php
    } else
    {
        ?>


        <p> Totale clowns: <?php echo(sizeOf($nomiClowns)); ?> </p>

        <table >
            <tbody>
            <tr>
                <th>Clown</th>
                <th>Nome e cognome</th>
                <th>Indirizzo e-mail</th>
                <th>Numero di telefono</th>
            </tr>

            <?php
            for ($n = 0; $n < sizeof($nomiClowns); $n++)
            {

                ?>
                <tr <?php if ($n % 2 == 0)
                {
                    echo "class=\"\"";
                } ?>>

                    <td><span class="nomeclown"><?php echo "$nomiClowns[$n]"; ?></span></td>
                    <td><?php echo $nomi[$n] . " " . $cognomi[$n]; ?></td>
                    <td><a class="mail" href="mailto:<?php echo $mails[$n]; ?>"><?php echo $mails[$n]; ?></a></td>
                    <td><span class="cell"> <?php if ($cell[$n] != '0')
                            {
                                echo $cell[$n];
                            } ?></span></td>

                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

        <br/>
        <br/>

        <?php
    }
    ?>

</div>

<br/>
<br/>
<br/>



