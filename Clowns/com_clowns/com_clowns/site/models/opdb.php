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

class OpDBModelOpDB extends JModelItem
{     //principali funzioni sul db

    const TABLE_CLOWNS_NAME = "#__clowns";
    const TABLE_CLOWNS_COLUMNS = array('Nome', 'Cognome', 'Nome_Clown', 'Mail', 'Cell', 'Stato_Socio', 'Vip');
    const TABLE_CLOWNS_COLUMNS_FULL = array('id', 'Nome', 'Cognome', 'Nome_Clown', 'Mail', 'Cell', 'Stato_Socio', 'Vip');

    const TABLE_STATI_SOCIO_DISPONIBILI_NAME = "#__stati_socio_disponibili";
    const TABLE_STATI_SOCIO_DISPONIBILI_COLUMNS = array('id', 'Nome');

    const TABLE_VIP_DISPONIBILI_NAME = "#__vip_disponibili";
    const TABLE_VIP_DISPONIBILI_COLUMNS = array('id', 'Nome');

    const AGG_CLOWN_CONFIRMED = 0;
    const AGG_CLOWN_ALREADY_PRESENT = 1;

    const RIM_CLOWN_CONFIRMED = 2;
    const RIM_CLOWN_NOT_FOUND = 3;

    const MOD_CLOWN_CONFIRMED = 4;
    const MOD_CLOWN_NOT_FOUND = 5;

    function isVoid($param)
    {
        $isVoid = false;

        if (!isset($param) || !isset($param) || $param == '' || $param == '')
        {
            $isVoid = true;
        }

        return $isVoid;
    }

    /**
     * Aggiunge il clown nel database
     */
    function aggClown($nomeClown, $mailClown, $nome, $cognome, $cellClown, $statoSocio, $vip)
    {
        $array_params = array($nome, $cognome, $nomeClown, $statoSocio, $vip, $mailClown, $cellClown);
        for ($i = 0; $i < count($array_params); $i++)
        {
            $array_params[$i] = trim($array_params[$i]); //Elimina gli spazi vuoti da inizio e fine

            if ($i < 5)
            {
                $array_params[$i] = ucfirst($array_params[$i]);    //Prima lettera maiuscola
                $array_params[$i] = htmlspecialchars($array_params[$i], ENT_QUOTES);
                $array_params[$i] = str_replace(' ', '_', $array_params[$i]); //Sostituisce gli spazi in mezzo con '_'
            }
        }

        // Create the clown object to insert in db.
        $clownToInsert = new stdClass();
        $clownToInsert->Nome = $array_params[0];
        $clownToInsert->Cognome= $array_params[1];
        $clownToInsert->Nome_Clown = $array_params[2];
        $clownToInsert->Mail = $array_params[5];
        $clownToInsert->Cell = $array_params[6];
        $clownToInsert->Stato_Socio = $array_params[3];
        $clownToInsert->Vip = $array_params[4];

        // Prepare db
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Cerca se il clown esiste già
        $query
            ->select($db->quoteName(self::TABLE_CLOWNS_COLUMNS))
            ->from($db->quoteName(self::TABLE_CLOWNS_NAME))
            ->where("Nome_Clown='$nomeClown' OR Mail='$mailClown'");

        $db->setQuery($query);
        $db->execute();

        $rows = $db->loadObjectList();
        if ($rows == NULL)
        {
            // Insert the object into the user profile table.
            JFactory::getDbo()->insertObject(self::TABLE_CLOWNS_NAME, $clownToInsert);
            return self::AGG_CLOWN_CONFIRMED;

        } else
        {
            return self::AGG_CLOWN_ALREADY_PRESENT;
        }

    }

    function rimClown($idToRemove)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $conditions = array("id = '$idToRemove'");

        $query
            ->delete($db->quoteName(self::TABLE_CLOWNS_NAME))
            ->where($conditions);

        $db->setQuery($query);
        if ($db->execute() == 1)
        {
            return self::RIM_CLOWN_CONFIRMED;
        }
        return self::RIM_CLOWN_NOT_FOUND;

    }

    function modClown($idToUpdate, $nomeClownNew, $mailClownNew, $nomeNew, $cognomeNew, $cellClownNew, $statoSocioNew, $vipNew)
    {
        $array_params = array( $nomeNew, $cognomeNew, $nomeClownNew, $statoSocioNew, $vipNew, $mailClownNew, $cellClownNew);
        for ($i = 0; $i < count($array_params); $i++)
        {
            $array_params[$i] = trim($array_params[$i]); //Elimina gli spazi vuoti da inizio e fine
            if ($i < 5)
            {
                $array_params[$i] = ucfirst($array_params[$i]);    //Prima lettera maiuscola
                $array_params[$i] = htmlspecialchars($array_params[$i], ENT_QUOTES);
                $array_params[$i] = str_replace(' ', '_', $array_params[$i]); //Sostituisce gli spazi in mezzo con '_'
            }
        }

        // Clown with updated values
        $clownToUpdate = new stdClass();
        $clownToUpdate->id = $idToUpdate;
        $clownToUpdate->Nome = $array_params[0];
        $clownToUpdate->Cognome = $array_params[1];
        $clownToUpdate->Nome_Clown = $array_params[2];
        $clownToUpdate->Mail = $array_params[5];
        $clownToUpdate->Cell = $array_params[6];
        $clownToUpdate->Stato_Socio = $array_params[3];
        $clownToUpdate->Vip = $array_params[4];

        // Prepare DB
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Cerca se il clown esiste
        $query
            ->select($db->quoteName(self::TABLE_CLOWNS_COLUMNS_FULL))
            ->from($db->quoteName(self::TABLE_CLOWNS_NAME))
            ->where("id='$idToUpdate'");

        $db->setQuery($query);
        $db->execute();

        $rows = $db->loadAssocList();
        if ($rows != NULL)
        {
            // Update using the name as key
            JFactory::getDbo()->updateObject(self::TABLE_CLOWNS_NAME, $clownToUpdate, 'id');
            return self::MOD_CLOWN_CONFIRMED;

        } else
        {
            return self::MOD_CLOWN_NOT_FOUND;
        }


    }

    function leggiElencoClowns()
    {
        // Prepare DB
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //Recupera i nomi dei clown in elenco (nomi, cognomi e nomi clown)
        $query
            ->select($db->quoteName(self::TABLE_CLOWNS_COLUMNS_FULL))
            ->from($db->quoteName(self::TABLE_CLOWNS_NAME))
            ->order('Nome_Clown');

        $db->setQuery($query);
        $db->execute();

        $rows = $db->loadAssocList();
        if ($rows != NULL)
        {     //La tabella non è vuota
            $n = 0;
            foreach ($rows as $row)
            {
                $ids[$n] = $row['id'];

                $nomi[$n] = $row['Nome'];
                $nomi[$n] = str_replace('_', ' ', $nomi[$n]);

                $cognomi[$n] = $row['Cognome'];
                $cohnomi[$n] = str_replace('_', ' ', $cognomi[$n]);

                $nomiClowns[$n] = $row['Nome_Clown'];
                $nomiClowns[$n] = str_replace('_', ' ', $nomiClowns[$n]);

                $mails[$n] = $row['Mail'];
                $cell[$n] = $row['Cell'];

                $statiSoci[$n] = $row['Stato_Socio'];
                $vips[$n] = $row['Vip'];

                $n++;
            }

            $clowns = array('ids' => $ids, 'nomi' => $nomi, 'cognomi' => $cognomi, 'nomiClowns' => $nomiClowns,
                'mails' => $mails, 'cell' => $cell, 'statiSoci' => $statiSoci, 'vips' => $vips);
            return $clowns;

        } else
        {
            return NULL;
        }

    }


    function getStatiSocioDisponibili()
    {
        // Prepare DB
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //Recupera i nomi delle vip disponibili
        $query
            ->select($db->quoteName(self::TABLE_STATI_SOCIO_DISPONIBILI_COLUMNS))
            ->from($db->quoteName(self::TABLE_STATI_SOCIO_DISPONIBILI_NAME))
            ->order('Nome');

        $db->setQuery($query);
        $db->execute();

        $rows = $db->loadAssocList();
        if ($rows != NULL)
        {     //La tabella non è vuota
            $n = 0;
            foreach ($rows as $row)
            {
                $ids[$n] = $row['id'];
                $nomeStato[$n] = $row['Nome'];
                $n++;
            }

            $vipDisponibili = array('ids' => $ids, 'nomi' => $nomeStato);
            return $vipDisponibili;

        } else
        {
            return NULL;
        }

    }

    function getVipDisponibili()
    {
        // Prepare DB
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //Recupera i nomi delle vip disponibili
        $query
            ->select($db->quoteName(self::TABLE_VIP_DISPONIBILI_COLUMNS))
            ->from($db->quoteName(self::TABLE_VIP_DISPONIBILI_NAME))
            ->order('Nome');

        $db->setQuery($query);
        $db->execute();

        $rows = $db->loadAssocList();
        if ($rows != NULL)
        {
          //La tabella non è vuota
            $n = 0;
            foreach ($rows as $row)
            {
                $ids[$n] = $row['id'];
                $nomiVip[$n] = $row['Nome'];
                $n++;
            }

            $vipDisponibili = array('ids' => $ids, 'nomi' => $nomiVip);
            return $vipDisponibili;

        } else
        {
            return NULL;
        }
    }


}


?>