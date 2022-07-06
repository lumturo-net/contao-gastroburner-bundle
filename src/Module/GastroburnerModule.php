<?php

namespace Lumturo\ContaoGastroburnerBundle\Module;

class GastroburnerModule extends \Module
{
    public static $QUESTION_CATEGORIES = [
        // Key => label
        'STRESS' => 'STRESSRESISTENZ', //$GLOBALS['TL_LANG']['gastroburner']['category']['STRESS'][0]
        'TEAM' => 'TEAMPLAYER',
        'LEADERSHIP' => 'FÜHRUNGSQUALITÄT',
        'DETAIL' => 'DETAILORIENTIERTHEIT',
        'COMMUNICATION' => 'KOMMUNIKATIONSFÄHIGKEIT',
        'CRAFT' => 'HANDWERKLICH ORIENTIERT',
        'POLITE' => 'HÖFLICHKEIT',
        'CREATIVE' => 'KREATIVITÄT',
        'MANAGE' => 'ORGANISATIONSTALENT',
        'OPENMINDED' => 'WELTOFFENHEIT',
        'NUMBERORIENTED' => 'ZAHLUNGSVERSTÄNDNIS',
        'OFFICE' => 'BÜROMENSCH',
        'FLEXIBLE' => 'FLEXIBILITÄT',
    ];

    public static $JOBS = [
        'RESTAURANT' => 'Fachmann/-frau für Restaurants und Veranstaltungsgastronomie',
        'COOK' => 'Koch/Köchin',
        'KITCHEN' => 'Fachkraft Küche',
        'SYSTEMG' => 'Fachmann/-frau für Systemgastronomie',
        'HOTELCLEANER' => 'Hotelfachmann/-frau',
        'HOTELMANAGER' => 'Kaufmann/-frau für Hotelmanagement',
        'GASTRO' => 'Fachkraft für Gastronomie',
    ];

    public static $POINTS = [
        'RESTAURANT'   => [
            'STRESS'         => 3,
            'TEAM'           => 3,
            'LEADERSHIP'     => 0,
            'DETAIL'         => 3,
            'COMMUNICATION'  => 3,
            'CRAFT'          => 1,
            'POLITE'         => 3,
            'CREATIVE'       => 0,
            'MANAGE'         => 1,
            'OPENMINDED'     => 2,
            'NUMBERORIENTED' => 2,
            'OFFICE'         => 0,
            'FLEXIBLE'       => 3,
        ],
        'COOK'         => [
            'STRESS'         => 3,
            'TEAM'           => 1,
            'LEADERSHIP'     => 2,
            'DETAIL'         => 3,
            'COMMUNICATION'  => 0,
            'CRAFT'          => 3,
            'POLITE'         => 0,
            'CREATIVE'       => 3,
            'MANAGE'         => 3,
            'OPENMINDED'     => 3,
            'NUMBERORIENTED' => 1,
            'OFFICE'         => 0,
            'FLEXIBLE'       => 2,
        ],
        'KITCHEN'         => [
            'STRESS'         => 3,
            'TEAM'           => 3,
            'LEADERSHIP'     => 0,
            'DETAIL'         => 2,
            'COMMUNICATION'  => 1,
            'CRAFT'          => 2,
            'POLITE'         => 1,
            'CREATIVE'       => 1,
            'MANAGE'         => 2,
            'OPENMINDED'     => 1,
            'NUMBERORIENTED' => 3,
            'OFFICE'         => 2,
            'FLEXIBLE'       => 3,
        ],
        'SYSTEMG'         => [
            'STRESS'         => 2,
            'TEAM'           => 2,
            'LEADERSHIP'     => 1,
            'DETAIL'         => 1,
            'COMMUNICATION'  => 3,
            'CRAFT'          => 1,
            'POLITE'         => 2,
            'CREATIVE'       => 1,
            'MANAGE'         => 1,
            'OPENMINDED'     => 1,
            'NUMBERORIENTED' => 2,
            'OFFICE'         => 1,
            'FLEXIBLE'       => 3,
        ],
        'HOTELCLEANER' => [
            'STRESS'         => 2,
            'TEAM'           => 2,
            'LEADERSHIP'     => 0,
            'DETAIL'         => 3,
            'COMMUNICATION'  => 2,
            'CRAFT'          => 0,
            'POLITE'         => 3,
            'CREATIVE'       => 1,
            'MANAGE'         => 3,
            'OPENMINDED'     => 3,
            'NUMBERORIENTED' => 1,
            'OFFICE'         => 2,
            'FLEXIBLE'       => 2,
        ],
        'HOTELMANAGER' => [
            'STRESS'         => 1,
            'TEAM'           => 1,
            'LEADERSHIP'     => 2,
            'DETAIL'         => 1,
            'COMMUNICATION'  => 1,
            'CRAFT'          => 0,
            'POLITE'         => 2,
            'CREATIVE'       => 1,
            'MANAGE'         => 2,
            'OPENMINDED'     => 3,
            'NUMBERORIENTED' => 3,
            'OFFICE'         => 3,
            'FLEXIBLE'       => 1,
        ],
        'GASTRO'       => [
            'STRESS'         => 2,
            'TEAM'           => 2,
            'LEADERSHIP'     => 0,
            'DETAIL'         => 2,
            'COMMUNICATION'  => 1,
            'CRAFT'          => 1,
            'POLITE'         => 1,
            'CREATIVE'       => 0,
            'MANAGE'         => 1,
            'OPENMINDED'     => 1,
            'NUMBERORIENTED' => 1,
            'OFFICE'         => 1,
            'FLEXIBLE'       => 1,
        ],
    ];

    /**
     * @var string
     */
    protected $strTemplate = 'mod_gastroburner';

    /**
     * Displays a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $template = new \BackendTemplate('be_wildcard');

            $template->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['gastroburner'][0]) . ' ###';
            $template->title = $this->headline;
            $template->id = $this->id;
            $template->link = $this->name;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $template->parse();
        }

        return parent::generate();
    }

    /**
     * Generates the module.
     */
    protected function compile()
    {
        // Suche 7 zufällige Kategorien aus:
        $arrCategories = array();
        $intCountCategories = count(self::$QUESTION_CATEGORIES);
        $arrCategoryKeys = array_keys(self::$QUESTION_CATEGORIES);
        while (count($arrCategories) != 7) {
            $intNewCat = rand(0, $intCountCategories - 1);
            $arrCategories[$arrCategoryKeys[$intNewCat]] = 1;
        }

        $arrCategories = array_keys($arrCategories);
        $arrQuestions = [];

        // Hole pro Category jetzt 1 Frage
        foreach ($arrCategories as $strCategory) {
            $objQuestions = $this->Database->prepare("SELECT * from tl_question where type=? ;")->execute($strCategory);
            $intRand = rand(0, $objQuestions->count() - 1);
            for ($intI = 0; $intI <= $intRand; $intI++) {
                $objRow = $objQuestions->fetchAssoc();
                $arrQuestions[$strCategory] = $objRow;
            }

        }

        // bereite jetzt schon die Antworten für das Template auf: 'A' => ..., 'B' =>
        $arrLetters = array('A', 'B', 'C', 'D');
        foreach ($arrQuestions as $intIndex => $arrQuestion) {
            $intLetterIndex = 0;
            // $arrQuestion['formattedAnswers'] = array();
            $arrAnswers = array();

            for ($intI = 0; $intI < 4; $intI++) {
                if (strlen($arrQuestion['answer' . $intI])) {
                    // $arrQuestion['formattedAnswers'][$arrLetters[$intLetterIndex++]] = array(
                    $arrAnswers[] = array(
                        'points' => $intI,
                        'answer' => $arrQuestion['answer' . $intI],
                    );
                }
            }

            // Reihenfolge zufällig verändern
            shuffle($arrAnswers);
            foreach ($arrAnswers as $arrAnswer) {
                $arrQuestion['formattedAnswers'][$arrLetters[$intLetterIndex++]] = $arrAnswer;
            }

            $arrQuestions[$intIndex] = $arrQuestion;
        }

        $this->Template->apply_form_url = $this->getApplyFormPageUrl();
        $this->Template->categories = $arrCategories;
        $this->Template->questions = $arrQuestions;
        $this->Template->points = self::$POINTS;
    }

    protected function getApplyFormPageUrl()
    {
        $intPageId = $GLOBALS['TL_CONFIG']['gastroburner_applyform_page'];

        if ($intPageId) {
            $objApplyPage = \PageModel::findById($intPageId);
            if ($objApplyPage) {
                $strApplyUrl = \Controller::generateFrontendUrl($objApplyPage->row());
                return $strApplyUrl;
            }
        } else {
            throw new \Exception('Bitte erst in den Settings die Bewerbungsseite setzen');
        }
    }
}
