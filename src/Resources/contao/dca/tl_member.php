<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

// Add palette

use Contao\StringUtil;

// Listen-Anzeige
$GLOBALS['TL_DCA']['tl_member']['list']['label']['fields'] = array('companyLogo', 'shortname', 'username', 'dateAdded', 'show_in_frontend');

$GLOBALS['TL_DCA']['tl_member']['list']['label']['label_callback'] = array('tl_gb_member', 'addIcon');
// $GLOBALS['TL_DCA']['tl_member']['list']['sorting']['header_callback'] = array('tl_gb_member', 'header');

$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('company,', 'company,shortname,companyLogo,description,shortdesc,', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('country;', 'country,lat,lon;', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('language;', 'language;{job_legend},show_in_frontend,restaurant,cook,kitchen,systemg,hotelcleaner,hotelmanager,gastro;', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);

// Add load callback
// $GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'][] = array('Newsletter', 'updateAccount');

// Add save callback
// $GLOBALS['TL_DCA']['tl_member']['fields']['disable']['save_callback'][] = array('Newsletter', 'onToggleVisibility');

// $GLOBALS['TL_DCA']['tl_member']['fields']['company']['eval']['mandatory'] = true;
// Add field
$GLOBALS['TL_DCA']['tl_member']['fields']['companyLogo'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['companyLogo'],
    'exclude' => false,
    'inputType' => 'fileTree',
    'save_callback' => array(
        array('tl_gb_member', 'uploadLogo'),
    ),
    'eval' => array(
        'files' => true,
        'filesOnly' => true,
        'fieldType' => 'radio',
        'storeFile' => true,
        'readonly' => false,
        'uploadFolder' => \FilesModel::findByPath($GLOBALS['TL_CONFIG']['uploadPath'] . '/companyLogos')->uuid,
        'extensions' => Contao\Config::get('validImageTypes'),
        'mandatory' => false,
        'feEditable' => true,
    ),
    'sql' => "binary(16) NULL",
);

$GLOBALS['TL_DCA']['tl_member']['fields']['shortname'] = array(
    'sorting' => true,
    'label' => &$GLOBALS['TL_LANG']['tl_member']['shortname'],
    'exclude' => false,
    'inputType' => 'text',
    'eval' => array(
        'mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50',
        'feEditable' => true,
    ),
    'sql' => "varchar(255) NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_member']['fields']['street']['eval']['mandatory'] = true;
$GLOBALS['TL_DCA']['tl_member']['fields']['postal']['eval']['mandatory'] = true;
$GLOBALS['TL_DCA']['tl_member']['fields']['city']['eval']['mandatory'] = true;

$GLOBALS['TL_DCA']['tl_member']['fields']['restaurant'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['restaurant'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql' => "INT(10) unsigned NOT NULL default '0'",
);
$GLOBALS['TL_DCA']['tl_member']['fields']['cook'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['cook'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql' => "INT(10) unsigned NOT NULL default '0'",
);

$GLOBALS['TL_DCA']['tl_member']['fields']['kitchen'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['kitchen'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql' => "INT(10) unsigned NOT NULL default '0'",
);

$GLOBALS['TL_DCA']['tl_member']['fields']['systemg'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['systemg'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql' => "INT(10) unsigned NOT NULL default '0'",
);

$GLOBALS['TL_DCA']['tl_member']['fields']['hotelcleaner'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['hotelcleaner'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql' => "INT(10) unsigned NOT NULL default '0'",
);
$GLOBALS['TL_DCA']['tl_member']['fields']['gastro'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['gastro'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql' => "INT(10) unsigned NOT NULL default '0'",
);

$GLOBALS['TL_DCA']['tl_member']['fields']['hotelmanager'] = array(
    'filter' => true,
    'label' => &$GLOBALS['TL_LANG']['tl_member']['hotelmanager'],
    'inputType' => 'text',
    'eval' => array(
        'rgxp' => 'natural',
        'mandatory' => true,
        'feEditable' => true,
    ),
    'sql' => "int(10) unsigned NOT NULL default '0'",
);
$GLOBALS['TL_DCA']['tl_member']['fields']['shortdesc'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['shortdesc'],
    'exclude' => false,
    'inputType' => 'textarea',
    'eval' => array(
        'maxlength' => 60,
        'minlength' => 1,
        'feEditable' => true,
        // 'rte' => 'tinyMCE',
        'mandatory' => true,
        'class' => 'noresize',

    ),
    'sql' => "text NULL default NULL",
    // 'sql' => "text NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_member']['fields']['description'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['description'],
    'exclude' => false,
    'inputType' => 'textarea',
    'eval' => array(
        'feEditable' => true,
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
            'saveLat',
        ),
    ),
    'eval' => array(
        'mandatory' => false,
        'maxlength' => 255,
        'tl_class' => 'w50',
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
            'saveLon',
        ),
    ),
    'eval' => array(
        'mandatory' => false,
        'maxlength' => 255,
        'tl_class' => 'w50',
    ),
    'sql' => "varchar(255) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_member']['fields']['show_in_frontend'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_member']['show_in_frontend'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    // 'eval'                    => array('submitOnChange' => true),
    'eval' => [
        'feEditable' => true,
    ],
    'sql' => "char(1) NOT NULL default ''",
);

class tl_gb_member extends Contao\Backend
{

    protected $objNominatimResponse = null;

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('Contao\BackendUser', 'User');
    }

    public function header($a, $b = 1, $c = 1, $d = 1)
    {
        return $a;
    }

    /**
     * Listenanzeige im Backend anpassen
     *
     * @param array                $row
     * @param string               $label
     * @param Contao\DataContainer $dc
     * @param array                $args
     *
     * @return array
     */
    public function addIcon($row, $label, Contao\DataContainer $dc, $args)
    {
        // /*
        $image = 'ok';
        // $time = Contao\Date::floorToMinute();

        // $disabled = ($row['start'] !== '' && $row['start'] > $time) || ($row['stop'] !== '' && $row['stop'] < $time);

        // if ($row['useTwoFactor']) {
        //     $image .= '_two_factor';
        // }

        // if ($row['disable'] || $disabled) {
        //     $image .= '_';
        // }
        if ($row['show_in_frontend'] == '') {
            $image = 'delete';
        }

        $args[4] = sprintf('<div class="list_icon_new" style="background-image:url(\'%ssystem/themes/%s/icons/%s.svg\')" data-icon="%s.svg" data-icon-disabled="%s.svg">&nbsp;</div>', Contao\System::getContainer()->get('contao.assets.assets_context')->getStaticUrl(), Contao\Backend::getTheme(), $image, $disabled ? $image : rtrim($image, '_'), rtrim($image, '_') . '_');
        // */

        // companyLogo
        $objLogo = \Contao\FilesModel::findBy('uuid', $row['companyLogo']);
        if ($objLogo) {
            $args[0] = '<img style="width: 15px; height: 15px;" src="/' . $objLogo->path . '">';
        } else {
            $args[0] = '';
        }

        return $args;
    }

    /**
     * Warum auch immer: muss selbständig den Wert für das Upload-Feld korrekt holen und setzen :-(
     *
     *
     */
    public function uploadLogo($value, $null)
    {
        $arrSession = $_SESSION['FILES'];
        if ($arrSession && isset($arrSession['companyLogo'])) {
            return StringUtil::uuidToBin($arrSession['companyLogo']['uuid']);
        }
        return $value;
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
            str_replace(',', '', $this->Input->post('street')),
        ];
        $headers = [];
        // $headers = array('Accept' => 'application/json');
        $query = array(
            'q' => urldecode(implode(',', $arrQueryValues)),
            'format' => 'json',
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
