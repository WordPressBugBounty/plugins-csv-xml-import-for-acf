<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use pmai_acf_add_on\groups\Group;
use pmai_acf_add_on\groups\GroupFactory;

/**
 * Class PMAI_Import_Record
 */
class PMAI_Import_Record extends PMAI_Model_Record {

    /**
     * @var Group[]
     */
    public $groups = array();

    /**
     * Initialize model instance
     * @param array [optional] $data Array of record data to initialize object with
     */
    public function __construct($data = array()) {
        parent::__construct($data);
        $this->setTable(PMXI_Plugin::getInstance()
                ->getTablePrefix() . 'imports');
    }

    /**
     * @param array $parsingData [import, count, xml, logger, chunk, xpath_prefix]
     */
    public function parse($parsingData){

        add_filter('user_has_cap', array(
            $this,
            '_filter_has_cap_unfiltered_html'
        ));
        kses_init(); // do not perform special filtering for imported content

        $parsingData['chunk'] == 1 and $parsingData['logger'] and call_user_func($parsingData['logger'], __('Composing advanced custom fields...', 'csv-xml-import-for-acf'));

        if (!empty($parsingData['import']->options['acf'])){
            $acfGroups = $parsingData['import']->options['acf'];
            if (!empty($acfGroups)) {
                foreach ($acfGroups as $acfGroupID => $status) {
                    if (!$status) {
                        continue;
                    }
                    if ( ! is_numeric($acfGroupID) ) {
                    	$group = pmai_get_acf_group_by_slug($acfGroupID);
                    	if ( ! empty($group) ) {
		                    $this->groups[] = GroupFactory::create(array('ID' => $group->ID), $parsingData['import']->options);
	                    }
                    } else {
	                    $this->groups[] = GroupFactory::create(array('ID' => $acfGroupID), $parsingData['import']->options);
                    }
                }
            }
            foreach ($this->groups as $group){
                $group->parse($parsingData);
            }
        }

        remove_filter('user_has_cap', array(
            $this,
            '_filter_has_cap_unfiltered_html'
        ));
        kses_init(); // return any filtering rules back if they has been disabled for import procedure
    }

    /**
     * @param $importData [pid, i, import, articleData, xml, is_cron, xpath_prefix]
     */
    public function import($importData){
        $importData['logger'] and call_user_func($importData['logger'], '<strong>'.__('ACF ADD-ON:', 'csv-xml-import-for-acf').'</strong>');
        foreach ($this->groups as $group){
            $group->import($importData);
        }
    }

    /**
     * @param $importData [pid, import, logger, is_update]
     */
    public function saved_post($importData){
        foreach ($this->groups as $group){
            $group->saved_post($importData);
        }
    }

    /**
     * @param $caps
     * @return mixed
     */
    public function _filter_has_cap_unfiltered_html($caps) {
        $caps['unfiltered_html'] = TRUE;
        return $caps;
    }

    /**
     * @param $var
     * @return bool
     */
    public function filtering($var) {
        return ("" == $var) ? FALSE : TRUE;
    }
}
