<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

// Add palette
$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('newsletter;', 'newsletter;{job_legend},companyLogo,restaurant,cook,hotelcleaner,hotelmanager,gastro;', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);

// Add load callback
// $GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'][] = array('Newsletter', 'updateAccount');

// Add save callback
// $GLOBALS['TL_DCA']['tl_member']['fields']['disable']['save_callback'][] = array('Newsletter', 'onToggleVisibility');

// Add field
$GLOBALS['TL_DCA']['tl_member']['fields']['companyLogo'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['companyLogo'],
    'exclude'                 => false,
    'inputType'               => 'fileTree',
    // 'foreignKey'              => 'tl_newsletter_channel.title',
    // 'options_callback'        => array('Newsletter', 'getNewsletters'),
    // 'eval'                    => array(
    //     'files' => true,
    //     'filesOnly' => true,
    // ),
    'save_callback' => array(
        array('tl_gb_member', 'uploadLogo')
    ),
    'eval'                    => array(
        'files' => true,
        'filesOnly' => true,
        'fieldType' => 'radio',
        'storeFile' => true,
        'readonly' => false,
        'uploadFolder' => \FilesModel::findByPath($GLOBALS['TL_CONFIG']['uploadPath'] . '/companyLogos')->uuid,
        'extensions' => Contao\Config::get('validImageTypes'),
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql'                     => "binary(16) NULL"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['restaurant'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_apply']['restaurant'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql'                     => "INT(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['cook'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_apply']['cook'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql'                     => "INT(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['hotelcleaner'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_apply']['hotelcleaner'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql'                     => "INT(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_member']['fields']['gastro'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_apply']['gastro'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql'                     => "INT(10) unsigned NOT NULL default '0'"
);


class tl_gb_member extends Contao\Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('Contao\BackendUser', 'User');
    }
    public function  uploadLogo($a, $b, $c)
    {
        $test = 0;
     }
}
