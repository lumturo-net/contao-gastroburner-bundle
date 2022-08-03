<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Gastroburner - Hotels/Gastro-Unternehmen
 * @author    rolf.staege@lumturo.net
 * @license   GPL
 * @copyright Rolf Staege 2019
 */

/**
 * Table tl_company
 */
$GLOBALS['TL_DCA']['tl_company'] = array
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
            'fields' => array('shortname'),
            'flag' => 1,
            'panelLayout' => 'sort,filter;search,limit',
        ),
        'label' => array
        (
            'fields' => array('shortname', 'restaurant', 'cook', 'kitchen', 'systemg', 'hotelcleaner', 'hotelmanager', 'gastro'),
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
        'default' => '{name_legend},shortname,name;{address_legend},street,number,zip,city,lat,lon;{contact_legend},contact,email;{job_legend},restaurant,cook,kitchen,systemg,hotelcleaner,hotelmanager,gastro;{description_legend},shortdesc,description',
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
        'shortname' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['shortname'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'name' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['name'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'street' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['street'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'number' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['number'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'zip' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['zip'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'city' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['city'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'lat' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['lat'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'lon' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['lon'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),

        // logo / datei Haus
        'contact' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['contact'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'email' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_company']['email'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100', 'rgxp' => 'email'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'phone' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_company']['phone'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100', 'rgxp' => 'phone'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'shortdesc' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['shortdesc'],
            'exclude' => false,
            'inputType' => 'textarea',
            // 'maxlength' => 300,
            // 'minlength' => 1,
            'eval' => array(
                'maxlength' => 60,
                'minlength' => 1,
                // 'rte' => 'tinyMCE',
                'mandatory' => true,
                'class' => 'noresize',
            ),
            'sql' => "text NULL default NULL",
            // 'sql' => "text NOT NULL default ''",
        ),
        'description' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_company']['description'],
            'exclude' => false,
            'inputType' => 'textarea',
            'eval' => array(
                // 'rte' => 'tinyMCE',
                'mandatory' => true,
                'class' => 'noresize',
            ),
            'sql' => "text NULL default NULL",
            // 'sql' => "text NOT NULL default ''",
        ),

        'restaurant' => array
        (
            'filter' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['restaurant'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'rgxp' => 'digit', 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'cook' => array
        (
            'filter' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['cook'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'rgxp' => 'digit', 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'kitchen' => array
        (
            'filter' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['kitchen'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'rgxp' => 'digit', 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'systemg' => array
        (
            'filter' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['systemg'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'rgxp' => 'digit', 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'hotelcleaner' => array
        (
            'filter' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['hotelcleaner'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'rgxp' => 'digit', 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'hotelmanager' => array
        (
            'filter' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['hotelmanager'],
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'rgxp' => 'digit', 'maxlength' => 255, 'tl_class' => 'w50'),
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'gastro' => array
        (
            'filter' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_company']['gastro'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'rgxp' => 'digit', 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
    ),
);
