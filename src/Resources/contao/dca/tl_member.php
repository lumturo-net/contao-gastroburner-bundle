<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

// Add palette
$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('company,', 'company,shortname,companyLogo,description,', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('country;', 'country,lat,lon;', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('newsletter;', 'newsletter;{job_legend},restaurant,cook,hotelcleaner,hotelmanager,gastro;', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);

// Add load callback
// $GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'][] = array('Newsletter', 'updateAccount');

// Add save callback
// $GLOBALS['TL_DCA']['tl_member']['fields']['disable']['save_callback'][] = array('Newsletter', 'onToggleVisibility');


$GLOBALS['TL_DCA']['tl_member']['fields']['company']['eval']['mandatory'] = true;
// Add field
$GLOBALS['TL_DCA']['tl_member']['fields']['companyLogo'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['companyLogo'],
    'exclude'                 => false,
    'inputType'               => 'fileTree',
    // 'save_callback' => array(
    //     array('tl_gb_member', 'uploadLogo')
    // ),
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

$GLOBALS['TL_DCA']['tl_member']['fields']['shortname'] = array(
    'sorting' => true,
    'label' => &$GLOBALS['TL_LANG']['tl_member']['shortname'],
    'exclude' => false,
    'inputType' => 'text',
    'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
    'sql' => "varchar(255) NOT NULL default ''",
);


$GLOBALS['TL_DCA']['tl_member']['fields']['restaurant'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['restaurant'],
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
    'label' => &$GLOBALS['TL_LANG']['tl_member']['cook'],
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
    'label' => &$GLOBALS['TL_LANG']['tl_member']['hotelcleaner'],
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
    'label' => &$GLOBALS['TL_LANG']['tl_member']['gastro'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql'                     => "INT(10) unsigned NOT NULL default '0'"
);


$GLOBALS['TL_DCA']['tl_member']['fields']['hotelmanager'] = array(
    'filter' => true,
    'label' => &$GLOBALS['TL_LANG']['tl_member']['hotelmanager'],
    'inputType' => 'text',
    'eval'                    => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql' => "int(10) unsigned NOT NULL default '0'",
);
$GLOBALS['TL_DCA']['tl_member']['fields']['description'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['description'],
    'exclude' => false,
    'inputType' => 'textarea',
    'eval' => array(
        // 'rte' => 'tinyMCE',
        'mandatory' => true,
        'class' => 'noresize',
    ),
    'sql' => "text NULL default NULL",
    // 'sql' => "text NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_member']['fields']['lat'] = array(
    'sorting' => true,
    'label' => &$GLOBALS['TL_LANG']['tl_member']['lat'],
    'exclude' => false,
    'inputType' => 'text',
    'save_callback' => array(
        array(
            'tl_gb_member',
            'saveLat'
        )
    ),
    'eval' => array(
        'mandatory' => false,
        'maxlength' => 255,
        'tl_class' => 'w50'
    ),
    'sql' => "varchar(255) NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_member']['fields']['lon'] = array(
    'sorting' => true,
    'label' => &$GLOBALS['TL_LANG']['tl_member']['lon'],
    'exclude' => false,
    'inputType' => 'text',
    'save_callback' => array(
        array(
            'tl_gb_member',
            'saveLon'
        )
    ),
    'eval' => array(
        'mandatory' => false,
        'maxlength' => 255,
        'tl_class' => 'w50',
    ),
    'sql' => "varchar(255) NOT NULL default ''",
);





class tl_gb_member extends Contao\Backend
{

    protected $objNominatimResponse = NULL;

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('Contao\BackendUser', 'User');
    }

    public function saveLon($value, $objDca)
    {
        return $this->savePos('lon', $value, $objDca);
    }

    public function saveLat($value, $objDca)
    {
        return $this->savePos('lat', $value, $objDca);
    }

    private function savePos($strField, $value, $objDca)
    {
        if (floatval($value)) {
            return $value;
        }
        if ($this->objNominatimResponse) {
            return $this->objNominatimResponse->{$strField};
        }

        $arrQueryValues = [
            trim($this->Input->post('postal') . ' ' . $this->Input->post('city')),
            str_replace(',', '', $this->Input->post('street'))
        ];
        $headers = [];
        // $headers = array('Accept' => 'application/json');
        $query = array(
            'q' => urldecode(implode(',', $arrQueryValues)),
            'format' => 'json'
        );

        $response = Unirest\Request::get('https://nominatim.openstreetmap.org/search', $headers, $query);
        if ($response->code == 200) {
            if (isset($response->body[0])) {
                $this->objNominatimResponse = $response->body[0];
            }
        }
        if ($this->objNominatimResponse && isset($this->objNominatimResponse->{$strField})) {
            return $this->objNominatimResponse->{$strField};
        }
        return $value;
    }
}
