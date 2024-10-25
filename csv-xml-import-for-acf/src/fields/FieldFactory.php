<?php

namespace pmai_acf_add_on\fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use pmai_acf_add_on\ACFService;
use pmai_acf_add_on\fields\acf\FieldEmpty;
use pmai_acf_add_on\fields\acf\FieldNotSupported;

/**
 * Class FieldFactory
 * @package pmai_acf_add_on\fields
 */
final class FieldFactory {

    /**
     *
     * An array of fields which are doesn't have any functionality
     *
     * @var array
     */
    public static $hiddenFields = array('accordion', 'tab', 'message');

    /**
     * @param $fieldData
     * @param $post
     * @param $fieldName
     * @param $fieldParent
     * @return bool|FieldEmpty
     */
    public static function create($fieldData, $post, $fieldName = "", $fieldParent = false) {
        $field = FALSE;
        $class_suffix = str_replace(" ", "", ucwords(str_replace(array("_","-"), " ", $fieldData['type'])));
        $class = '\\pmai_acf_add_on\\fields\\acf\\Field' . $class_suffix;
        if (!class_exists($class)) {
            $class = '\\pmai_acf_add_on\\fields\\acf\\' . $fieldData['type'] . '\\Field' . $class_suffix;

			// If class still doesn't exist check using alternate field name in namespace. Ensure the class isn't
	        // using a version specific name before using the alternate.
	        if(!class_exists($class) && !class_exists($class.'V5') && !class_exists($class.'V4')){
		        $class = '\\pmai_acf_add_on\\fields\\acf\\' . 'field_' . $fieldData['type'] . '\\Field' . $class_suffix;
	        }
        }
		// These both reference the WP All Import plugin.
	    $class = apply_filters( 'wp_all_import_acf_field_class', $class , $fieldData, $post, $fieldName, $fieldParent );
	    $field = apply_filters( 'wp_all_import_acf_field_field', $field,  $class, $fieldData, $post, $fieldName, $fieldParent );
	    if (!empty($field)){
		    return $field;
        }
        if (empty($fieldData['type']) || in_array($fieldData['type'], self::$hiddenFields)) {
            $field = new FieldEmpty($fieldData, $post, $fieldName);
        } elseif (ACFService::isACFNewerThan('5.0.0') && class_exists($class.'V5')){
            $class .= 'V5';
            $field = new $class($fieldData, $post, $fieldName, $fieldParent);
        } elseif (!ACFService::isACFNewerThan('5.0.0') && class_exists($class.'V4')){
            $class .= 'V4';
            $field = new $class($fieldData, $post, $fieldName, $fieldParent);
        } elseif (class_exists($class)) {
            $field = new $class($fieldData, $post, $fieldName, $fieldParent);
        }

        if (empty($field)){
            $field = new FieldNotSupported($fieldData, $post, $class_suffix);
        }
        return $field;
    }
}