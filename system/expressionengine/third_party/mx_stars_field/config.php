<?php
if (! defined('MX_STARS_FIELD_KEY'))
{
	define('MX_STARS_FIELD_NAME', 'MX Stars Field');
	define('MX_STARS_FIELD_VER',  '2.7.2');
	define('MX_STARS_FIELD_KEY', 'mx_stars_field');
	define('MX_STARS_FIELD_AUTHOR',  'Max Lazar');
	define('MX_STARS_FIELD_DOCS',  'http://www.eec.ms/add-on/mx-stars-field');
	define('MX_STARS_FIELD_DESC',  '');

}

/**
 * < EE 2.6.0 backward compat
 */
 
if ( ! function_exists('ee'))
{
    function ee()
    {
        static $EE;
        if ( ! $EE) $EE = get_instance();
        return $EE;
    }
}