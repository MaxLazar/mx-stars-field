<?php

use MX\StarsField\Helper;

/**
 * MX Stars Field field type.
 *
 * @author  Max Lazar <max@eecms.dev>
 *
 * @see    https://eecms.dev/add-ons/mx-stars-field
 * @based https://snebold.dk/raty-fa/
 *
 * @copyright Copyright (c) 2020, EEC.MS
 */

/**
 * Class Mx_stars_field_ft.
 */
class Mx_stars_field_ft extends EE_Fieldtype
{

    public $info = array(
        'name'     => MX_STAR_NAME,
        'version'  => MX_STAR_VERSION
    );

    public $field2ee =  array('boolean' => 'toggle', 'number' => 'text', 'select'=>'select', 'function'=>'textarea', 'string' => 'text', 'array' => 'textarea', 'object' => 'textarea');

    private static $js_added         = false;
    private static $cell_bind        = true;
    private static $grid_bind        = true;

    private $fallback_content        = '';
    public $cell_name;
    public $has_array_data           = false;
    public $entry_manager_compatible = true;

    /**
     * Package name.
     *
     * @var string
     */
    protected $package;

    /**
     * [$_themeUrl description].
     *
     * @var [type]
     */
    private static $themeUrl;


    /**
     * Field_limits_ft constructor.
     */
    public function __construct()
    {
        $this->package = basename(__DIR__);

        parent::__construct();

        if (!isset(static::$themeUrl)) {
            $themeFolderUrl = defined('URL_THIRD_THEMES') ? URL_THIRD_THEMES : ee()->config->slash_item('theme_folder_url').'third_party/';
            static::$themeUrl = $themeFolderUrl.'mx_stars_field/';
        }
    }

    /**
     * Specify compatibility.
     *
     * @param string $name
     *
     * @return bool
     */
    public function accepts_content_type($name)
    {
        $compatibility = array(
        'low_variables',
        'channel',
        'fluid_field',
        'grid',
        'bloqs/1',
        'blocks/1"'
        );

        return in_array($name, $compatibility, false);
    }

    /**
     * Settings.
     *
     * @param array $data Existing setting data
     *
     * @return array
     */
    public function display_settings($data)
    {
        return $this->_build_settings($data);
    }

    /**
     * build_settings function.
     *
     * @param mixed $data
     */
    private function _build_settings($data, $type = false)
    {
        ee()->lang->loadfile($this->package);

        $settings = array();

        $config = self::getConfigFromFile('mx_stars_field/Settings/StarsField');

        foreach ($config as $field => $type) {

            $value = (isset($data[$field]) && '' != $data[$field]) ? $data[$field] : (false != ee()->config->item('mx_stars_field_'.$field) ?
            ee()->config->item('mx_stars_field_'.$field) : $config[$field]['defaults']);

            $settings[] = array(
                'title' => $field,
                'desc' => $field.'_description',
                'fields' => array(
                    'mx_stars_field_'.$field => array(
                        'type' => $this->field2ee[$config[$field]['type']],
                        'choices' => isset($config[$field]['values']) ? $config[$field]['values'] : '',
                        'value' => $value,
                    )
                ),
            );

        }

        return array('field_options_mx_stars_field' => array(
            'label' => 'field_options',
            'group' => 'mx_stars_field',
            'settings' => $settings,
        ));
    }

    /**
     * Apply Config overrides to $this->settings.
     */
    private function _config_overrides()
    {
        // Check custom config values
        foreach ($this->_cfg as $key) {
            // Check the config for the value
            $val = ee()->config->item('mx_stars_field_'.$key);

            // If not FALSE, override the settings
            if (false !== $val) {
                $this->_settings[$key] = $val;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Check if given setting is present in the config file.
     *
     * @return bool
     */
    public function is_config($item)
    {
        return in_array($item, $this->_cfg) && (false !== ee()->config->item('mx_rangeslider_'.$item));
    }

    /**
     * Display grid settings.
     *
     * @param array $data Existing setting data
     *
     * @return array
     */
    public function grid_display_settings($data)
    {
        return $this->_build_settings($data);
    }

    /**
     * Display Low Variables settings.
     *
     * @param array $data Existing setting data
     *
     * @return array
     */
    public function var_display_settings($data)
    {
        return $this->_build_settings($data, 'lv');
    }

    /**
     * Save settings.
     *
     * @param array $data
     *
     * @return array
     */
    public function save_settings($data)
    {
       //     var_dump($data);
     //   die();

        return $this->get($data, 'mx_stars_field');
    }

    /**
     * Save Low Variables settings.
     *
     * @param array $data
     *
     * @return array
     */
    public function var_save_settings($data)
    {
        //    var_dump(ee('Request')->post());
      //  die();

        return $this->get(ee('Request')->post(), 'mx_stars_field');
    }

    /**
     * Displays the field in the CP.
     * @param       string      $field_name             The field name.
     * @param       array       $field_data             The previously-saved field data.
     * @param       arrray      $field_settings         The field settings.
     * @return      string      The HTML to output.
     */
    public function display_field($data, $view_type = 'field', $settings = array(), $cp = true, $passed_init = array())
    {

        $js       = "";
        $css      = "";
        $class    = "";
        $subClass ="";
        $r        = "";

        if (!empty($settings)) {
            $cp = false;
        }

        $cell = ($view_type != 'field') ? true : false;

        if (empty($settings)) {
            $settings = $this->settings;
        } else {
            $settings = array_merge($this->settings, $settings);
        }

        $is_grid = isset($this->settings['grid_field_id']);

        $field_name = $this->field_name;

        $pos = strpos($field_name, "[fields]");
        $fluid_field_data_id = (isset($this->settings['fluid_field_data_id'])) ? $this->settings['fluid_field_data_id'] : 0;

        if ($view_type == 'cell') {
            $field_name = $this->cell_name;
            $class      .= 'mx-star-matrix';
        }

        if ($view_type == 'grid') {
            $field_name = $this->field_name;
            $class      .= 'mx-star-grid';
            $subClass   =  $field_name . '_c';
        }

        $css = ' ';

        if ($view_type == 'field' &&  ( $fluid_field_data_id !=0 || $pos === false )) {
            $class  .= 'mx-star-field';
        }

        if ($view_type == 'field') {

        }

        $data = array(
            'name'  => $field_name,
            'id'    => str_replace(array( "[", "]" ), "_", $field_name),
            'value' => $data
        );

        if ($view_type == 'field') {
            $id = explode("_", $data['id']);
            $data['id'] = 'field_id_' . $id[2];
        }

        $js_block_start = '<script type="text/javascript">';
        $js_block_end   = '</script>';

        $config = self::getConfigFromFile('mx_stars_field/Settings/StarsField');

        if (self::$grid_bind and $view_type == 'grid') {
            $js = " var newGridRowCountSf = 0;
                    Grid.bind('mx_stars_field', 'display', function(cell)
            {
                    var cell_obj = cell.find('.mx-rate');

                    if (cell.data('row-id')) {
                        rowId = cell.data('row-id');
                    } else {
                        rowId = 'new_row_' + ++newGridRowCountSf;
                    }

                    id = cell.parents('.grid-field').attr('id') + '[rows][row_id_' + rowId + '][col_id_' + cell.data('column-id') + ']';

                    $(cell_obj).raty(window[$(cell_obj).attr('data-config')]);
                    $(cell_obj).raty('set', { scoreName: id });
                    $(cell_obj).raty('set', { score: $(cell_obj).attr('data-score') });
            });";

            self::$grid_bind = false;
        }

        $this->insertGlobalResources($cell);

        if (!self::$js_added and $cp) {
            $js_ini = " $('.mx-star-field').each(function(e) {
                let field = $(this);
                field.raty(window[field.attr('data-config')]);
                field.raty('set', { score: field.attr('data-score') });
            }); " . "\r\n";

            ee()->cp->add_to_foot($js_block_start . $js_ini . $js_block_end);

            self::$js_added = true;
        }

        if (!$cell) {
            $js .='';
        }

        $js .= 'FluidField.on("mx_stars_field", "add", function (element) {
           var obj = element.find(".mx-rate");
           var config = obj.attr(\'data-config\');
           obj.raty(window[config]);
           obj.raty(\'set\', { score: obj.attr(\'data-score\') });
           console.log(config);
        });';

        if ($cp) {
            ee()->javascript->output($js);
        } else {
            $r .= $js_block_start . $js . $js_block_end;
        }

        if (!isset(ee()->session->cache['mx_stars_field'][$field_name])) {
            $r .= "\r\n" . $js_block_start . " var rateConfig_" . $data['id'] . " = {
                cancel     : " . ($settings['no_rating_icon'] ? 'true' : 'false') .",
                starHalf : '" . $settings['half_star_icon'] . " " . $subClass ." ',
                starOff   : '" . $settings['empty_star_icon'] . " " . $subClass ." ',
                starOn      : '" . $settings['full_star_icon'] . " " . $subClass ." ',
                cancelOff   : '" . $settings['cancel_off_icon'] . " " . $subClass ." ',
                cancelOn    : '" . $settings['cancel_on_icon'] . " " . $subClass ." ',
                number   : " . $settings['star_icon'] .",
                scoreName: '" . $data['name'] ."',
                half   : " . ($settings['split_star_icon'] ? 'true' : 'false') ."
            };" . $js_block_end . "\r\n";

            if ($view_type == 'grid') {
                $r .= '<style> i.' . $subClass .' {color:' . $settings['color_icon'] . '!important;font-size:' . $settings['font_size'] . 'px!important}; </style>';
            }

            ee()->session->cache['mx_stars_field'][$field_name] = true;
        }


            $r .= '<div class="mx_stars_field" ><div style="color:' . $settings['color_icon'] . '!important;font-size:' . $settings['font_size'] . 'px!important" class="mx-rate ' . $class . '" data-score="' . $data['value'] . '" data-config="rateConfig_' . $data['id'] . '"></div></div>';


        $this->insertGlobalResources($cell);

        return $r;
    }
    /**
     * [renderTableCell description]
     * @param  [type] $data     [description]
     * @param  [type] $field_id [description]
     * @param  [type] $entry    [description]
     * @return [type]           [description]
     */
    function renderTableCell($data, $field_id, $entry)
    {
        return $data;
    }

    //http://s3.amazonaws.com/scr.eecms.dev/1604691685.png


    /**
     * Display the field in a Grid cell.
     *
     * @param string $data field data
     *
     * @return string $field
     */
    public function grid_display_field($data)
    {
        return $this->display_field($data, 'grid');
    }

    /**
     * Display Low Variables field.
     *
     * @param mixed $data
     *
     * @return string
     */
    public function var_display_field($data)
    {
        return $this->display_field($data);
    }

    /**
     * Validate field data.
     *
     * @param mixed $data Submitted field data
     *
     * @return mixed
     */
    public function validate($data)
    {
        if (!$data) {
            return true;
        }

        $errors = '';

        if ($errors) {
            return $errors;
        }

        return true;
    }

    /**
     * Validate Low Variables field.
     *
     * @param string $data
     *
     * @return mixed
     */
    public function var_save($data)
    {
        ee()->lang->loadfile('mx_stars_field');

        $validation = $this->validate($data);

        if (true !== $validation) {
            $this->error_msg = $validation;

            return false;
        }

        return $data;
    }

    /**
     * Replace tag.
     *
     * @param string $fieldData
     * @param array  $tagParams
     *
     * @return string
     */
    public function replace_tag($data, $params = array(), $tagdata = false)
    {

        return $data;
    }


  /**
     * replace_value function.
     *
     * @access public
     * @param mixed   $data
     * @param array   $params (default: array())
     * @return void
     */
    public function replace_value($data, $params = array())
    {
        return $data;
    }

    /**
     * Display Low Variables tag.
     *
     * @param string $fieldData
     * @param array  $tagParams
     *
     * @return string
     */
    public function var_replace_tag(
        $fieldData,
        $tagParams = array(),
        $tagData = false
    ) {
        return $this->replace_tag($fieldData, $tagParams);
    }

    /*

    HELPERS
    @needs to move to helpers file


     */

    /**
     * Insert JS in the page foot.
     *
     * @param string $js
     */
    public function insertGlobalResources($cell = false)
    {
        if (!isset(ee()->session->cache['mx_stars_field']['header'])) {
            $this->includeJs('js/jquery.raty-fa.js');
            ee()->cp->add_to_head('<link href="//use.fontawesome.com/releases/v5.15.1/css/all.css" rel="stylesheet">');
            ee()->session->cache['mx_stars_field']['header'] = true;
        }
    }

    /**
 * Insert JS in the page foot.
 *
 * @param string $js
 */
    public static function insertJsCode($js)
    {
        ee()->cp->add_to_foot('<script type="text/javascript">'.$js.'</script>');
    }

    /**
     * [includeJs description].
     *
     * @param [type] $file [description]
     *
     * @return [type] [description]
     */
    public static function includeJs($file)
    {
        ee()->cp->add_to_foot('<script type="text/javascript" src="'.static::$themeUrl.$file.'"></script>');
    }

    /**
     * [includeThemeCss description].
     *
     * @param [type] $file [description]
     *
     * @return [type] [description]
     */
    public static function includeCss($file)
    {
        ee()->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.static::$themeUrl.$file.'" />');
    }

    /**
     * Settings helper.
     *
     * @param array  $data   Setting data
     * @param string $prefix
     *
     * @return array
     */
    public function get($data, $prefix)
    {
        $saveData = array();

        $prefix .= '_';

        $offset = strlen($prefix);

        foreach ($data as $saveKey => $save) {
            if (0 === strncmp($prefix, $saveKey, $offset)) {
                $saveData[substr($saveKey, $offset)] = $save;
            }
        }

        return $saveData;
    }

    /** @TODO move to helper:: */

    /**
     *
     */

    public static function getConfigFromFile(string $filePath): array
    {

        $path = PATH_THIRD  . $filePath . '.php';

        if (!file_exists($path)) {
                return [];
        }

        if (!\is_array($config = @include $path)) {
            return [];
        }

        return $config;

    }
}
