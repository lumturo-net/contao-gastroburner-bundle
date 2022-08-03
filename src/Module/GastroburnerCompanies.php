<?php

namespace Lumturo\ContaoGastroburnerBundle\Module;

use Contao\Controller;
use Contao\BackendTemplate;
use Contao\Database;
use Contao\Email;
use Contao\Validator;
use Contao\FilesModel;

class GastroburnerCompanies extends \Contao\Module
{
    /**
     * @var string
     */
    // protected $strTemplate = 'mod_gastroburner_applyform';
    protected $strTemplate = 'mod_gastroburner_companies';

    /**
     * Displays a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $template = new BackendTemplate('be_wildcard');

            $template->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['gastroburnercompanies'][0]) . ' ###';
            $template->title = $this->headline;
            $template->id = $this->id;
            $template->link = $this->name;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $template->parse();
        } else {
            $GLOBALS['TL_CSS'][] = 'bundles/contaogastroburner/css/companies.min.css';
        }

        return parent::generate();
    }

    /**
     * Generates the module.
     */
    protected function compile()
    {
        global $objPage;

        $arrFields = ['id', 'company', 'street', 'postal', 'city', 'lat', 'lon', 'shortname', 'shortdesc', 'restaurant', 'cook', 'kitchen', 'systemg', 'hotelcleaner', 'hotelmanager', 'gastro', 'companyLogo', 'website'];
        $arrDbCompanies= Database::getInstance()->prepare('SELECT ' . implode(', ', $arrFields) . ' FROM tl_member WHERE disable=\'\' AND show_in_frontend=\'1\' ORDER BY shortname;')->execute()->fetchAllAssoc();
        $arrCompanies = [];
        foreach ($arrDbCompanies as $arrCompany) {
            $objLogo = FilesModel::findOneBy('uuid', $arrCompany['companyLogo']);
            $arrCompany['shortdesc'] = preg_replace("!([\b\t\n\r\f\"\\'])!", "", $arrCompany['shortdesc']);
            $arrCompany['shortname'] = preg_replace("!([\b\t\n\r\f\"\\'])!", "", $arrCompany['shortname']);
            $arrCompany['company'] = preg_replace("!([\b\t\n\r\f\"\\'])!", "", $arrCompany['company']);
            $arrCompany['companyLogo'] = $objLogo->path ?? 'https://via.placeholder.com/170x100.png&text=Hotel-Logo';
            $arrCompanies[$arrCompany['id']] = $arrCompany;
        }

        $this->Template->post = $arrPost;
        $this->Template->companies = $arrCompanies;
        $this->Template->errors = $arrErrors;
        $this->Template->url = Controller::generateFrontendUrl($objPage->row());
    }
}
