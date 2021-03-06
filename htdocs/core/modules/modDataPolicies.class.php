<?php

/* Copyright (C) 2004-2018 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2018      Nicolas ZABOURI      <info@inovea-conseil.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 	\defgroup   datapolicies     Module datapolicies
 *  \brief      datapolicies module descriptor.
 *
 *  \file       htdocs/datapolicies/core/modules/modGdpr.class.php
 *  \ingroup    datapolicies
 *  \brief      Description and activation file for module DATAPOLICIES
 */
include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';



// The class name should start with a lower case mod for Dolibarr to pick it up
// so we ignore the Squiz.Classes.ValidClassName.NotCamelCaps rule.
// @codingStandardsIgnoreStart
/**
 *  Description and activation class for module datapolicies
 */
class modDataPolicies extends DolibarrModules {

    // @codingStandardsIgnoreEnd
    /**
     * Constructor. Define names, constants, directories, boxes, permissions
     *
     * @param DoliDB $db Database handler
     */
    public function __construct($db)
    {
        global $langs, $conf;

        $this->db = $db;

        // Id for module (must be unique).
        // Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
        $this->numero = 4100;
        // Key text used to identify module (for permissions, menus, etc...)
        $this->rights_class = 'datapolicies';

        // Family can be 'base' (core modules),'crm','financial','hr','projects','products','ecm','technic' (transverse modules),'interface' (link with external tools),'other','...'
        // It is used to group modules by family in module setup page
        $this->family = "hr";
        // Module position in the family on 2 digits ('01', '10', '20', ...)
        $this->module_position = '70';
        // Gives the possibility to the module, to provide his own family info and position of this family (Overwrite $this->family and $this->module_position. Avoid this)
        //$this->familyinfo = array('myownfamily' => array('position' => '01', 'label' => $langs->trans("MyOwnFamily")));
        // Module label (no space allowed), used if translation string 'ModuledatapoliciesName' not found (MyModue is name of module).
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        // Module description, used if translation string 'ModuledatapoliciesDesc' not found (MyModue is name of module).
        $this->description = "Module to manage Data policies (for compliance with GDPR in Europe or other Data policies rules)";
        // Used only if file README.md and README-LL.md not found.
        $this->descriptionlong = "";

        // Possible values for version are: 'development', 'experimental', 'dolibarr', 'dolibarr_deprecated' or a version string like 'x.y.z'
        $this->version = 'development';
        // Key used in llx_const table to save module status enabled/disabled (where datapolicies is value of property name of module in uppercase)
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        // Name of image file used for this module.
        // If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
        // If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
        $this->picto = 'generic';

        // Defined all module parts (triggers, login, substitutions, menus, css, etc...)
        // for default path (eg: /datapolicies/core/xxxxx) (0=disable, 1=enable)
        // for specific path of parts (eg: /datapolicies/core/modules/barcode)
        // for specific css file (eg: /datapolicies/css/datapolicies.css.php)
        $this->module_parts = array(
            'triggers' => 0, // Set this to 1 if module has its own trigger directory (core/triggers)
            'login' => 0, // Set this to 1 if module has its own login method file (core/login)
            'substitutions' => 0, // Set this to 1 if module has its own substitution function file (core/substitutions)
            'menus' => 0, // Set this to 1 if module has its own menus handler directory (core/menus)
            'theme' => 0, // Set this to 1 if module has its own theme directory (theme)
            'tpl' => 0, // Set this to 1 if module overwrite template dir (core/tpl)
            'barcode' => 0, // Set this to 1 if module has its own barcode directory (core/modules/barcode)
            'models' => 0, // Set this to 1 if module has its own models directory (core/modules/xxx)
            'css' => array('/datapolicies/css/datapolicies.css.php'), // Set this to relative path of css file if module has its own css file
            'js' => array('/datapolicies/js/datapolicies.js.php'), // Set this to relative path of js file if module must load a js on all pages
            'hooks' => array('data' => array('membercard', 'contactcard', 'thirdpartycard'), 'entity' => $conf->entity)  // Set here all hooks context managed by module. To find available hook context, make a "grep -r '>initHooks(' *" on source code. You can also set hook context 'all'
        );

        // Data directories to create when module is enabled.
        // Example: this->dirs = array("/datapolicies/temp","/datapolicies/subdir");
        $this->dirs = array("/datapolicies/temp");

        // Config pages. Put here list of php page, stored into datapolicies/admin directory, to use to setup module.
        $this->config_page_url = array("setup.php@datapolicies");

        // Dependencies
        $this->hidden = false;   // A condition to hide module
        $this->depends = array();  // List of module class names as string that must be enabled if this module is enabled
        $this->requiredby = array(); // List of module ids to disable if this one is disabled
        $this->conflictwith = array(); // List of module class names as string this module is in conflict with
        $this->langfiles = array("datapolicies@datapolicies");
        $this->phpmin = array(5, 3);     // Minimum version of PHP required by module
        $this->need_dolibarr_version = array(4, 0); // Minimum version of Dolibarr required by module
        $this->warnings_activation = array();                     // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
        $this->warnings_activation_ext = array();                 // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
        //$this->automatic_activation = array('FR'=>'datapoliciesWasAutomaticallyActivatedBecauseOfYourCountryChoice');
        //$this->always_enabled = true;								// If true, can't be disabled
        // Constants
        // List of particular constants to add when module is enabled (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
        // Example: $this->const=array(0=>array('datapolicies_MYNEWCONST1','chaine','myvalue','This is a constant to add',1),
        //                             1=>array('datapolicies_MYNEWCONST2','chaine','myvalue','This is another constant to add',0, 'current', 1)
        // );
        $this->const = array(
            array('DATAPOLICIES_TIERS_CLIENT', 'chaine', '', 'Nombre de mois avant suppression des données', 0),
            array('DATAPOLICIES_TIERS_PROSPECT', 'chaine', '', 'Nombre de mois avant suppression des données', 0),
            array('DATAPOLICIES_TIERS_PROSPECT_CLIENT', 'chaine', '', 'Nombre de mois avant suppression des données', 0),
            array('DATAPOLICIES_TIERS_NIPROSPECT_NICLIENT', 'chaine', '', 'Nombre de mois avant suppression des données', 0),
            array('DATAPOLICIES_TIERS_FOURNISSEUR', 'chaine', '', 'Nombre de mois avant suppression des données', 0),
            array('DATAPOLICIES_CONTACT_CLIENT', 'chaine', '', 'Nombre de mois avant suppression des données', 0),
            array('DATAPOLICIES_CONTACT_PROSPECT', 'chaine', '', 'Nombre de mois avant suppression des données', 0),
            array('DATAPOLICIES_CONTACT_PROSPECT_CLIENT', 'chaine', '', 'Nombre de mois avant suppression des données', 0),
            array('DATAPOLICIES_CONTACT_NIPROSPECT_NICLIENT', 'chaine', '', 'Nombre de mois avant suppression des données', 0),
            array('DATAPOLICIES_CONTACT_FOURNISSEUR', 'chaine', '', 'Nombre de mois avant suppression des données', 0),
            array('DATAPOLICIES_ADHERENT', 'chaine', '', 'Nombre de mois avant suppression des données', 0),
        );

        $country = explode(":", $conf->global->MAIN_INFO_SOCIETE_COUNTRY);

        // Some keys to add into the overwriting translation tables
        /* $this->overwrite_translation = array(
          'en_US:ParentCompany'=>'Parent company or reseller',
          'fr_FR:ParentCompany'=>'Maison mère ou revendeur'
          ) */

        if (!isset($conf->datapolicies) || !isset($conf->datapolicies->enabled)) {
            $conf->datapolicies = new stdClass();
            $conf->datapolicies->enabled = 0;
        }


        // Array to add new pages in new tabs
        $this->tabs = array();
        // Example:
        // $this->tabs[] = array('data'=>'objecttype:+tabname1:Title1:mylangfile@datapolicies:$user->rights->datapolicies->read:/datapolicies/mynewtab1.php?id=__ID__');  					// To add a new tab identified by code tabname1
        // $this->tabs[] = array('data'=>'objecttype:+tabname2:SUBSTITUTION_Title2:mylangfile@datapolicies:$user->rights->othermodule->read:/datapolicies/mynewtab2.php?id=__ID__',  	// To add another new tab identified by code tabname2. Label will be result of calling all substitution functions on 'Title2' key.
        // $this->tabs[] = array('data'=>'objecttype:-tabname:NU:conditiontoremove');                                                     										// To remove an existing tab identified by code tabname
        //
        // Where objecttype can be
        // 'categories_x'	  to add a tab in category view (replace 'x' by type of category (0=product, 1=supplier, 2=customer, 3=member)
        // 'contact'          to add a tab in contact view
        // 'contract'         to add a tab in contract view
        // 'group'            to add a tab in group view
        // 'intervention'     to add a tab in intervention view
        // 'invoice'          to add a tab in customer invoice view
        // 'invoice_supplier' to add a tab in supplier invoice view
        // 'member'           to add a tab in fundation member view
        // 'opensurveypoll'	  to add a tab in opensurvey poll view
        // 'order'            to add a tab in customer order view
        // 'order_supplier'   to add a tab in supplier order view
        // 'payment'		  to add a tab in payment view
        // 'payment_supplier' to add a tab in supplier payment view
        // 'product'          to add a tab in product view
        // 'propal'           to add a tab in propal view
        // 'project'          to add a tab in project view
        // 'stock'            to add a tab in stock view
        // 'thirdparty'       to add a tab in third party view
        // 'user'             to add a tab in user view
        // Dictionaries
        $this->dictionaries = array();
        /* Example:
          $this->dictionaries=array(
          'langs'=>'mylangfile@datapolicies',
          'tabname'=>array(MAIN_DB_PREFIX."table1",MAIN_DB_PREFIX."table2",MAIN_DB_PREFIX."table3"),		// List of tables we want to see into dictonnary editor
          'tablib'=>array("Table1","Table2","Table3"),													// Label of tables
          'tabsql'=>array('SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table1 as f','SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table2 as f','SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table3 as f'),	// Request to select fields
          'tabsqlsort'=>array("label ASC","label ASC","label ASC"),																					// Sort order
          'tabfield'=>array("code,label","code,label","code,label"),																					// List of fields (result of select to show dictionary)
          'tabfieldvalue'=>array("code,label","code,label","code,label"),																				// List of fields (list of fields to edit a record)
          'tabfieldinsert'=>array("code,label","code,label","code,label"),																			// List of fields (list of fields for insert)
          'tabrowid'=>array("rowid","rowid","rowid"),																									// Name of columns with primary key (try to always name it 'rowid')
          'tabcond'=>array($conf->datapolicies->enabled,$conf->datapolicies->enabled,$conf->datapolicies->enabled)												// Condition to show each dictionary
          );
         */


        // Boxes/Widgets
        // Add here list of php file(s) stored in datapolicies/core/boxes that contains class to show a widget.
        $this->boxes = array();


        // Cronjobs (List of cron jobs entries to add when module is enabled)
        // unit_frequency must be 60 for minute, 3600 for hour, 86400 for day, 604800 for week
        $this->cronjobs = array(
            0 => array('label' => 'DATAPOLICIES Cron', 'jobtype' => 'method', 'class' => '/datapolicies/class/datapoliciesCron.class.php', 'objectname' => 'RgpdCron', 'method' => 'exec', 'parameters' => '', 'comment' => 'Comment', 'frequency' => 1, 'unitfrequency' => 86400, 'status' => 1, 'test' => true),
            1 => array('label' => 'DATAPOLICIES Mailing', 'jobtype' => 'method', 'class' => '/datapolicies/class/datapoliciesCron.class.php', 'objectname' => 'RgpdCron', 'method' => 'sendMailing', 'parameters' => '', 'comment' => 'Comment', 'frequency' => 1, 'unitfrequency' => 86400, 'status' => 0, 'test' => true)
        );
        // Example: $this->cronjobs=array(0=>array('label'=>'My label', 'jobtype'=>'method', 'class'=>'/dir/class/file.class.php', 'objectname'=>'MyClass', 'method'=>'myMethod', 'parameters'=>'param1, param2', 'comment'=>'Comment', 'frequency'=>2, 'unitfrequency'=>3600, 'status'=>0, 'test'=>true),
        //                                1=>array('label'=>'My label', 'jobtype'=>'command', 'command'=>'', 'parameters'=>'param1, param2', 'comment'=>'Comment', 'frequency'=>1, 'unitfrequency'=>3600*24, 'status'=>0, 'test'=>true)
        // );
        // Permissions
        $this->rights = array();  // Permission array used by this module
        // Main menu entries
        $this->menu = array();   // List of menus to add
        $r = 0;
    }

    /**
     * 	Function called when module is enabled.
     * 	The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     * 	It also creates data directories
     *
     * 	@param      string	$options    Options when enabling module ('', 'noboxes')
     * 	@return     int             	1 if OK, 0 if KO
     */
    public function init($options = '')
    {
    	global $langs;

    	$this->_load_tables('/datapolicies/sql/');

        // Create extrafields
        include_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';
        $extrafields = new ExtraFields($this->db);


        // Extrafield contact
        //$result1=$extrafields->addExtraField('datapolicies_separate', "DATAPOLICIES_BLOCKCHECKBOX", 'separate', 100,  1, 'thirdparty',   0, 0, '', '', 1, '', '1', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_consentement', "DATAPOLICIES_consentement", 'boolean', 101, 3, 'thirdparty', 0, 0, '', '', 1, '', '3', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_opposition_traitement', "DATAPOLICIES_opposition_traitement", 'boolean', 102, 3, 'thirdparty', 0, 0, '', '', 1, '', '3', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_opposition_prospection', "DATAPOLICIES_opposition_prospection", 'boolean', 103, 3, 'thirdparty', 0, 0, '', '', 1, '', '3', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_date', $langs->trans("DATAPOLICIES_date"), 'date', 104, 3, 'thirdparty', 0, 0, '', '', 1, '', '3', 0);
        $result1 = $extrafields->addExtraField('datapolicies_send', $langs->trans("DATAPOLICIES_send"), 'date', 105, 3, 'thirdparty', 0, 0, '', '', 0, '', '0', 0);

        // Extrafield Tiers
        //$result1=$extrafields->addExtraField('datapolicies_separate', "DATAPOLICIES_BLOCKCHECKBOX", 'separate', 100,  1, 'contact',   0, 0, '', '', 1, '', '1', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_consentement', "DATAPOLICIES_consentement", 'boolean', 101, 3, 'contact', 0, 0, '', '', 1, '', '3', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_opposition_traitement', "DATAPOLICIES_opposition_traitement", 'boolean', 102, 3, 'contact', 0, 0, '', '', 1, '', '3', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_opposition_prospection', "DATAPOLICIES_opposition_prospection", 'boolean', 103, 3, 'contact', 0, 0, '', '', 1, '', '3', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_date', $langs->trans("DATAPOLICIES_date"), 'date', 104, 3, 'contact', 0, 0, '', '', 1, '', '3', 0);
        $result1 = $extrafields->addExtraField('datapolicies_send', $langs->trans("DATAPOLICIES_send"), 'date', 105, 3, 'contact', 0, 0, '', '', 0, '', '0', 0);

        // Extrafield Adherent
        //$result1=$extrafields->addExtraField('datapolicies_separate', "DATAPOLICIES_BLOCKCHECKBOX", 'separate', 100,  1, 'adherent',   0, 0, '', '', 1, '', '1', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_consentement', "DATAPOLICIES_consentement", 'boolean', 101, 3, 'adherent', 0, 0, '', '', 1, '', '3', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_opposition_traitement', "DATAPOLICIES_opposition_traitement", 'boolean', 102, 3, 'adherent', 0, 0, '', '', 1, '', '3', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_opposition_prospection', "DATAPOLICIES_opposition_prospection", 'boolean', 103, 3, 'adherent', 0, 0, '', '', 1, '', '3', 0, '', '', 'datapolicies@datapolicies', '$conf->datapolicies->enabled');
        $result1 = $extrafields->addExtraField('datapolicies_date', $langs->trans("DATAPOLICIES_date"), 'date', 104, 3, 'adherent', 0, 0, '', '', 1, '', '3', 0);
        $result1 = $extrafields->addExtraField('datapolicies_send', $langs->trans("DATAPOLICIES_send"), 'date', 105, 3, 'adherent', 0, 0, '', '', 0, '', '0', 0);

        $sql = array();

        return $this->_init($sql, $options);
    }

    /**
     * 	Function called when module is disabled.
     * 	Remove from database constants, boxes and permissions from Dolibarr database.
     * 	Data directories are not deleted
     *
     * 	@param      string	$options    Options when enabling module ('', 'noboxes')
     * 	@return     int             	1 if OK, 0 if KO
     */
    public function remove($options = '')
    {
        $sql = array();

        return $this->_remove($sql, $options);
    }
}
