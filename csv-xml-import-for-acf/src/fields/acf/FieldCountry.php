<?php

namespace pmai_acf_add_on\fields\acf;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use pmai_acf_add_on\ACFService;
use pmai_acf_add_on\fields\Field;

/**
 * Class FieldCountry
 * @package pmai_acf_add_on\fields\acf
 */
class FieldCountry extends Field {

    /**
     *  Field type key
     */
    public $type = 'country';

    /**
     *
     * Parse field data
     *
     * @param $xpath
     * @param $parsingData
     * @param array $args
     */
    public function parse($xpath, $parsingData, $args = array()) {
        parent::parse($xpath, $parsingData, $args);
        $values = $this->getByXPath($xpath);
        $this->setOption('values', $values);
    }

    /**
     * @param $importData
     * @param array $args
     * @return mixed
     */
    public function import($importData, $args = array()) {
        $isUpdated = parent::import($importData, $args);
        if (!$isUpdated){
            return FALSE;
        }
        ACFService::update_post_meta($this, $this->getPostID(), $this->getFieldName(), $this->getFieldValue());
    }

    public function getFieldValue() {

        $value = parent::getFieldValue();

        $parsedData = $this->getParsedData();

        $field_post = get_post($parsedData['id']);
        if ($field_post){
            $field_post_options = unserialize($field_post->post_content);
            if (!empty($field_post_options['multiple'])) {
                $value = explode(",", $value);
            }
        }

        return $value;
    }
}