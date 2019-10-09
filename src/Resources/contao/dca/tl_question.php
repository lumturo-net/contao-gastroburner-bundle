<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Gastroburner
 * @author    rolf.staege@lumturo.net
 * @license   GPL
 * @copyright Rolf Staege 2019
 */

/**
 * Table tl_question
 */
$GLOBALS['TL_DCA']['tl_question'] = array
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
            'fields' => array('type', 'question'),
            'flag' => 1,
            'panelLayout' => 'sort,search,limit',
        ),
        'label' => array
        (
            'fields' => array('id', 'question', 'type'),
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
//             'applications' => array(
            //                 'label' => &$GLOBALS['TL_LANG']['tl_question']['edit'],
            //                 'href' => 'table=mhb_event_applications',
            // //                'href' => 'act=edit',
            //                 'icon' => 'member.gif'
            //             ),
            'delete' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_question']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            // 'toggle' => array
            // (
            //     'label' => &$GLOBALS['TL_LANG']['tl_article']['toggle'],
            //     'icon' => 'visible.gif',
            //     'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
            //     'button_callback' => array('tl_question', 'toggleIcon')
            // ),
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
        'default' => '{question_legend},question,type;{answer_legend},answer0,answer1,answer2,answer3',
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
        'type' => array(
            'sorting' => true,
            'default' => '',
            'label' => &$GLOBALS['TL_LANG']['tl_question']['type'],
            'exclude' => true,
            'search' => false,
            'inputType' => 'select',
            'options' => \Lumturo\ContaoGastroburnerBundle\Module\GastroburnerModule::$QUESTION_CATEGORIES,
            'eval' => array('multiple' => false, 'mandatory' => true),
            'sql' => "varchar(32) NOT NULL default ''",

        ),
        'question' => array
        (
            'sorting' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_question']['question'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'answer0' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_question']['answer0'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'answer1' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_question']['answer1'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'answer2' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_question']['answer2'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
        'answer3' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_question']['answer3'],
            'exclude' => false,
            'inputType' => 'text',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w100'),
            'sql' => "varchar(255) NOT NULL default ''",
        ),
    ),
);

class tl_question extends Backend
{
}
