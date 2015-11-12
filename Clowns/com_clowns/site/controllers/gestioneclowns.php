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

// import Joomla controller library
jimport('joomla.application.component.controller');


class  ClownsControllergestioneclowns extends JController
{
    const ACTION_CLOWN_INSUFFICIENT_DATA = -1;
    const ACTION_RESULT_ERROR = -2;


    function aggClown()
    {
        require_once(JPATH_COMPONENT . DS . 'models' . DS . 'opdb.php');
        $modelOpDB = new OpDbModelOpDB;

        $vName = JRequest::getCmd('view', 'categories');
        JRequest::setVar('view', $vName);
        $view = $this->getView($vName, 'html');

        $nomeClown = JRequest::getVar('nomeClownToAdd');
        $mailClown = JRequest::getVar('mailClownToAdd');
        $nome = JRequest::getVar('nomeToAdd');
        $cognome = JRequest::getVar('cognomeToAdd');
        $cellClown = JRequest::getVar('cellClownToAdd');
        $statoSocio = JRequest::getVar('statoSocioToAdd');
        $vip = JRequest::getVar('vipToAdd');

        if (isset($nomeClown) && isset($mailClown) )
        {
            $view->set('nomeClown', ucfirst($nomeClown));
            $view->set('nome', ucfirst($nome));
            $view->set('mail', ucfirst($mailClown));
            $view->set('cognome', ucfirst($cognome));

            $addResult = $modelOpDB->aggClown($nomeClown, $mailClown, $nome, $cognome, $cellClown, $statoSocio, $vip);
            $view->set('actionResult', $addResult);

        }else
        {
            // Dati insufficienti
            $view->set('actionResult', self::ACTION_CLOWN_INSUFFICIENT_DATA);
        }

        $view->set('modelOpDB', $modelOpDB);

        parent::display();
        //$view->display();
    }

    function rimClown()
    {
        require_once(JPATH_COMPONENT . DS . 'models' . DS . 'opdb.php');
        $modelOpDB = new OpDbModelOpDB;

        $vName = JRequest::getCmd('view', 'categories');
        $view = $this->getView($vName, 'html');

        $idToRemove = JRequest::getVar('idToRemove');
        if (isset($idToRemove))
        {
            $nomeClown = JRequest::getVar('nomeClownToDel');
            $view->set('nomeClown', ucfirst($nomeClown));

            $rimResult = $modelOpDB->rimClown($idToRemove);
            $view->set('actionResult', $rimResult);
        }

        $view->set('modelOpDB', $modelOpDB);

        parent::display();
        //	$view->display();
    }

    function modClown()
    {
        require_once(JPATH_COMPONENT . DS . 'models' . DS . 'opdb.php');
        $modelOpDB = new OpDbModelOpDB;

        $vName = JRequest::getCmd('view', 'categories');
        $view = $this->getView($vName, 'html');

        $idToUpdate = JRequest::getVar('idToUpdate');
        $nomeClownCurrent = JRequest::getVar('nomeClownCurrent');

        $nomeClownNew = JRequest::getVar('nomeClownNew');
        $mailClownNew = JRequest::getVar('mailClownNew');
        $nomeNew = JRequest::getVar('nomeNew');
        $cognomeNew = JRequest::getVar('cognomeNew');
        $cellClownNew = JRequest::getVar('cellClownNew');
        $statoSocioNew = JRequest::getVar('statoSocioNew');
        $vipNew = JRequest::getVar('vipNew');

        if (isset($nomeClownCurrent) )
        {
            if (isset($nomeClownNew) && isset($mailClownNew))
            {
                $view->set('nomeClown', ucfirst($nomeClownCurrent));

                $modResult = $modelOpDB->modClown($idToUpdate, $nomeClownNew, $mailClownNew, $nomeNew, $cognomeNew, $cellClownNew, $statoSocioNew, $vipNew);
                $view->set('actionResult', $modResult);

            } else
            {
                // Dati insufficienti
                $view->set('actionResult', self::ACTION_CLOWN_INSUFFICIENT_DATA);
            }


        }else
        {
            // Dati insufficienti
            $view->set('actionResult', self::ACTION_CLOWN_INSUFFICIENT_DATA);
        }

        $view->set('modelOpDB', $modelOpDB);

        parent::display();
        //	$view->display();

    }

}

?> 