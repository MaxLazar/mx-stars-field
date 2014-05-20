<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

require_once PATH_THIRD . 'mx_stars_field/config.php';

/**
 *  MX Stars Field Class for ExpressionEngine2
 *
 * @package     ExpressionEngine
 * @subpackage  Fieldtypes
 * @category    Fieldtypes
 * @author    Max Lazar <max@eec.ms>
 * @copyright Copyright (c) 2013 Max Lazar
 * @license   Commercial - please see LICENSE file included with this distribution
 */

class Mx_stars_field_ft extends EE_Fieldtype
{
    /**
     * Fieldtype Info
     *
     * @var array
     */

    public $info = array( 'name' => MX_STARS_FIELD_NAME, 'version' => MX_STARS_FIELD_VER );

    // Parser Flag (preparse pairs?)
    var $has_array_data = true;

    /**
     * PHP5 construct
     */
    function __construct() {
        parent::__construct();
        ee()->lang->loadfile( MX_STARS_FIELD_KEY );
    }

    // --------------------------------------------------------------------

    function validate( $data ) {
        $valid = TRUE;

    }

    // --------------------------------------------------------------------

    public function display_field( $data ) {
        if ( !isset( $this->cache[__CLASS__]['header'] ) ) {
            ee()->cp->add_to_foot( '<script type="text/javascript" src="' . ee()->config->item( 'theme_folder_url' ) . 'third_party/mx_stars_field/js/jquery-ui-1.8.5.custom.min.js"></script>' );
            ee()->cp->add_to_foot( '<script type="text/javascript" src="' . ee()->config->item( 'theme_folder_url' ) . 'third_party/mx_stars_field/js/jquery.ui.stars.min.js"></script>' );
            ee()->cp->add_to_foot( '<link rel="stylesheet" type="text/css" href="' . ee()->config->item( 'theme_folder_url' ) . 'third_party/mx_stars_field/css/ui.stars.css" />' );
            $this->cache[__CLASS__]['header'] = true;
        };

        $prefix      = 'mx_stars_field';
        $split       = ( isset( $this->settings[$prefix . '_split'] ) ) ? $this->settings[$prefix . '_split'] : '1';
        $stars_count = ( isset( $this->settings[$prefix . '_field_stars'] ) ) ? $this->settings[$prefix . '_field_stars'] : '5';

        $is_grid = isset( $this->settings['grid_field_id'] );

        $name = str_replace( array(
                '[',
                ']'
            ), '_', $this->field_name );
        $r    = "";
        $css  = "";

        $r = "<p><div id=\"rs_$name\" style=\"padding-left:10px;\" data-split=\"" . $split . "\" class=\"mx_stars_field\"><select name=\"$this->field_name\" class=\"mx_star\">";
        for ( $i = 1; $i <= $stars_count * $split; $i++ ) {
            $selected = ( $i == $data ) ? " selected=\"true\"" : "";
            $r .= "<option value=\"$i\"$selected>$i</option>";
        }

        $r .= "</select>";

        if ( !$is_grid ) {
            ee()->javascript->output( '$("#rs_' . $name . '").stars({inputType: "select",   split: ' . $split . '});' );
        }

        $r .= '</div></p>';

        if ( !ee()->session->cache( __CLASS__, 'grid_js_loaded' ) && $is_grid ) {
            ee()->javascript->output( '

                Grid.bind("mx_stars_field", "display", function(cell)
                {
                        var cell_obj = cell.find(".mx_stars_field");
                        var split = cell_obj.data("split");
                        cell_obj.stars({inputType: "select",   split: split});


                });

            ' );

            ee()->session->set_cache( __CLASS__, 'grid_js_loaded', TRUE );
        }

        return $r;
    }

    // --------------------------------------------------------------------
    function replace_scale( $data ) {
        $r = $this->settings['mx_stars_field_field_stars'];

        return $r;
    }



    function replace_tag( $data, $params = '', $tagdata = '' ) {
        if ( !empty( $tagdata ) ) {
            $prefix = 'mx_stars_field';
            $split  = ( isset( $this->settings[$prefix . '_split'] ) ) ? $this->settings[$prefix . '_split'] : '1';
            $size   = ( (int) $tagdata != 0 ) ? (int) $tagdata : 1;
            $r      = ( $size / $split ) * $data;
        } else {
            $r = $data;
        }
        ;


        return $r;
    }

    /**
     * Displays the cell
     *
     * @access public
     * @param unknown $data The cell data
     */
    public function display_cell( $data ) {
        if ( !isset( ee()->session->cache[__CLASS__]['mx_stars_js'] ) ) {
            ee()->cp->add_to_head( '<script type="text/javascript" src="' . ee()->config->item( 'theme_folder_url' ) . 'third_party/mx_stars_field/js/jquery-ui-1.8.5.custom.min.js"></script>' );
            ee()->cp->add_to_head( '<script type="text/javascript">mx_stars_field = {}; </script>' );
            ee()->cp->add_to_head( '<script type="text/javascript" src="' . ee()->config->item( 'theme_folder_url' ) . 'third_party/mx_stars_field/js/jquery.ui.stars.min.js"></script>' );
            ee()->cp->add_to_head( '<link rel="stylesheet" type="text/css" href="' . ee()->config->item( 'theme_folder_url' ) . 'third_party/mx_stars_field/css/ui.stars.css" />' );
            ee()->cp->add_to_foot( '<script type="text/javascript" src="' . ee()->config->item( 'theme_folder_url' ) . 'third_party/mx_stars_field/js/mx_stars.js"></script>' );
            ee()->session->cache[__CLASS__]['mx_stars_js'] = true;
        }
        ;

        $prefix      = 'mx_stars_field';
        $split       = ( isset( $this->settings[$prefix . '_split'] ) ) ? $this->settings[$prefix . '_split'] : '1';
        $stars_count = ( isset( $this->settings[$prefix . '_field_stars'] ) ) ? $this->settings[$prefix . '_field_stars'] : '5';

        $name = str_replace( array(
                '[',
                ']'
            ), '_', $this->cell_name );
        $r    = "";
        $css  = "";

        $r = "<p><div id=\"rs_\" style='padding-left:10px;'><select name=\"$this->cell_name\" class=\"mx_star\">";
        for ( $i = 1; $i <= $stars_count * $split; $i++ ) {
            $selected = ( $i == $data ) ? " selected=\"true\"" : "";
            $r .= "<option value=\"$i\"$selected>$i</option>";
        }

        $r .= "</select>";

        ee()->cp->add_to_foot( '<script type="text/javascript">mx_stars_field["col_id_' . $this->col_id . '"] = "' . $split . '"</script>' );


        $r .= '</div></p>';

        return $r;
    }

    /**
     * Display Cell Settings
     *
     * @access public
     * @param unknown $cell_settings array The cell settings
     * @return array Label and form inputs
     */
    public function display_cell_settings( $cell_settings ) {
        $prefix = 'mx_stars_field';

        $field_stars = ( empty( $cell_settings[$prefix . '_field_stars'] ) or $cell_settings[$prefix . '_field_stars'] == '' ) ? 5 : $cell_settings[$prefix . '_field_stars'];
        $field_split = ( empty( $cell_settings[$prefix . '_split'] ) or $cell_settings[$prefix . '_split'] == '' ) ? 1 : $cell_settings[$prefix . '_split'];
        $out         = "";


        $out .= '<table class="matrix-col-settings" border="0" cellpadding="0" cellspacing="0"><tbody><tr class=" matrix-first">';
        $out .= '<th class="matrix-first">Stars</th><td class="matrix-last">' . $this->select_list( $prefix . '_field_stars', $field_stars ) . '</td></tr>';
        $out .= '<tr class=" matrix-last"><th class="matrix-first">Split</th><td class="matrix-last">' . $this->select_list( $prefix . '_split', $field_split ) . '</td></tr></tbody></table>     ';

        return $out;
    }
    // --------------------------------------------------------------------

    function display_settings( $data ) {
        ee()->lang->loadfile( 'mx_stars_field' );

        $prefix = 'mx_stars_field';

        $field_stars = ( empty( $data[$prefix . '_field_stars'] ) or $data[$prefix . '_field_stars'] == '' ) ? 5 : $data[$prefix . '_field_stars'];
        $field_split = ( empty( $data[$prefix . '_split'] ) or $data[$prefix . '_split'] == '' ) ? 1 : $data[$prefix . '_split'];

        ee()->table->add_row( lang( 'stars', 'stars' ), $this->select_list( $prefix . '_field_stars', $field_stars ) );
        ee()->table->add_row( lang( 'split', 'split' ), $this->select_list( $prefix . '_split', $field_split ) );
    }


    function select_list( $field_name, $data ) {
        $r = '<select name="' . $field_name . '" >';
        for ( $i = 1; $i <= 30; $i++ ) {
            $selected = ( $i == $data ) ? " selected=\"true\"" : "";
            $r .= "<option value=\"$i\"$selected>$i</option>";
        }

        $r .= "</select>";
        return $r;
    }
    // --------------------------------------------------------------------
    function install() {
        return array(
            'mx_stars_field_field_stars' => '5'
        );
        return array(
            'mx_stars_field_field_split' => '1'
        );
    }

    function save_settings( $data ) {
        return array(
            'mx_stars_field_field_stars' => ee()->input->post( 'mx_stars_field_field_stars' ),
            'mx_stars_field_split' => ee()->input->post( 'mx_stars_field_split' )
        );
    }
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    public function grid_display_field( $data ) {
        return $this->display_field( $data, FALSE );
    }
    // --------------------------------------------------------------------
    function grid_save( $data ) {

        return $data;
    }

    // --------------------------------------------------------------------

    function grid_display_settings( $data ) {
        ee()->lang->loadfile( 'mx_stars_field' );

        $data = array_merge( array(
                'mx_stars_field_field_stars' => '5',
                'mx_stars_field_split' => '1',
                'default' => 'off'
            ), $data );

        return array(
            $this->grid_settings_row( lang( 'stars', 'stars' ), form_input( 'mx_stars_field_field_stars', $data['mx_stars_field_field_stars'] ) ),
            $this->grid_settings_row( lang( 'split', 'split' ), form_input( 'mx_stars_field_split', $data['mx_stars_field_split'] ) )
        );
    }

    // --------------------------------------------------------------------

    function _get_field_options( $data ) {

        return;
    }
    // --------------------------------------------------------------------

    /**
     * Grid settings validation callback; makes sure there are file upload
     * directories available before allowing a new file field to be saved
     *
     * @param array   Grid settings
     * @return  mixed   Validation error or TRUE if passed
     */
    function grid_validate_settings( $data ) {

        return TRUE;
    }

    // --------------------------------------------------------------------
    function grid_save_settings( $data ) {

        return $data;
    }


    /**
     * Accept all content types.
     *
     * @param string  The name of the content type
     * @return bool   Accepts all content types
     */
    public function accepts_content_type( $name ) {
        return TRUE;
    }


}

// END mx_stars_field_ft class

/* End of file ft.mx_stars_field.php */
/* Location: ./expressionengine/third_party/mx_stars_field/ft.mx_stars_field.php */
