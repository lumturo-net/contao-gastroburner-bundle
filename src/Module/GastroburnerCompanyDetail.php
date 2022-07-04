<?php

namespace Lumturo\ContaoGastroburnerBundle\Module;

use Contao\Controller;
use Contao\BackendTemplate;
use Contao\Database;
use Contao\Email;
use Contao\File;
use Contao\Image;
use Contao\StringUtil;
use Contao\Validator;
use Contao\FilesModel;
use Contao\Config;
use Contao\Input;
use Contao\Module;

class GastroburnerCompanyDetail extends Module
{
    /**
     * @var string
     */
    // protected $strTemplate = 'mod_gastroburner_applyform';
    protected $strTemplate = 'mod_gastroburner_company_detail';

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

        if (!isset($_GET['item']) && Config::get('useAutoItem') && isset($_GET['auto_item'])) {
            Input::setGet('item', Input::get('auto_item'));
        }

        return parent::generate();
    }

    /**
     * Generates the module.
     */
    protected function compile()
    {
        global $objPage;

        $intId     = intval(Input::get('auto_item'));
        $arrFields = ['id', 'company', 'street', 'postal', 'city', 'lat', 'lon', 'phone', 'fax', 'email', 'description', 'shortname', 'shortdesc', 'restaurant', 'cook', 'kitchen', 'hotelcleaner', 'hotelmanager', 'gastro', 'companyLogo', 'website'];
        $arrDbCompany = Database::getInstance()->prepare('SELECT ' . implode(', ', $arrFields) . ' FROM tl_member WHERE disable=\'\' AND show_in_frontend=\'1\' AND id = \'' . $intId . '\';')->execute()->fetchAllAssoc();
        $arrDbCompany[0]['companyLogo'] = FilesModel::findByUuid($arrDbCompany[0]['companyLogo']);

        $this->Template->post = $arrPost;
        $this->Template->company = empty($arrDbCompany) ? null : array_pop($arrDbCompany);
        $this->Template->errors = $arrErrors;
        $this->Template->url = Controller::generateFrontendUrl($objPage->row());
    }
}
