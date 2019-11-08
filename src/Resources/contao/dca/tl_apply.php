<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Gastroburner - Bewerbungen
 * @author    rolf.staege@lumturo.net
 * @license   GPL
 * @copyright Rolf Staege 2019
 */

/**
 * Table tl_apply
 */
$GLOBALS['TL_DCA']['tl_apply'] = array
    (

    // Config
    'config' => array
    (
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
            ),
        ),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode' => 1,
            'fields' => array('name'),
            'flag' => 1,
            'panelLayout' => 'sort,filter;search,limit',
        ),
        'label' => array
        (
            'fields' => array('vorname', 'name', 'email','restaurant','cook','hotelcleaner','hotelmanager','gastro'),
//            'format' => '%s (%d - %s)',
            // 'label_callback' => array('tl_question', 'listValues'),
            'showColumns' => true,
        ),
        'global_operations' => array(),
        'operations' => array
        (
            'edit' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_question']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif',
            ),
            'delete' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_question']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_question']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ),
        ),
    ),

    // Select
    'select' => array
    (
        'buttons_callback' => array(),
    ),

    // Edit
    'edit' => array
    (
        'buttons_callback' => array(),
    ),

    // Palettes
    'palettes' => array
    (
        'default' => '{name_legend},vorname,name,email;{job_legend},restaurant,cook,hotelcleaner,hotelmanager,gastro;{beschreibung_legend},beschreibung',
    ),

    // Subpalettes
    'subpalettes' => array
    (
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'search' => true,
            'sorting' => true,
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ),
        'tstamp' => array
        (
            'label' => 'Anlage',
            'flag' => 5,
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'vorname' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_apply']['vorname'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'name' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_apply']['name'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'email' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_apply']['email'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100', 'rgxp' => 'email'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'restaurant' => array
        (
            'filter' => TRUE,
            'label' => &$GLOBALS['TL_LANG']['tl_apply']['restaurant'],
            'exclude' => false,
            'inputType' => 'checkbox',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'cook' => array
        (
            'filter' => TRUE,
            'label' => &$GLOBALS['TL_LANG']['tl_apply']['cook'],
            'exclude' => false,
            'inputType' => 'checkbox',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'hotelcleaner' => array
        (
            'filter' => TRUE,
            'label' => &$GLOBALS['TL_LANG']['tl_apply']['hotelcleaner'],
            'exclude' => false,
            'inputType' => 'checkbox',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'hotelmanager' => array
        (
            'filter' => TRUE,
            'label' => &$GLOBALS['TL_LANG']['tl_apply']['hotelmanager'],
            'exclude' => false,
            'inputType' => 'checkbox',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'gastro' => array
        (
            'filter' => TRUE,
            'label' => &$GLOBALS['TL_LANG']['tl_apply']['gastro'],
            'exclude' => false,
            'inputType' => 'checkbox',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'beschreibung' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['beschreibung'],
            'exclude' => false,
            'inputType' => 'textarea',
            'eval' => array(
                // 'rte' => 'tinyMCE',
                'mandatory' => false,
                'class' => 'noresize',
            ),
            'sql' => "text NULL default NULL",
            // 'sql' => "text NOT NULL default ''",
        ),

    ),
);

