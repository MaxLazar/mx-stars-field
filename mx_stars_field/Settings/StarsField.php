<?php

/**
 * MX Stars Field plugin.
 *
 *
 * @see      https://www.wiseupstudio.com
 *
 * @copyright Copyright (c) 2020 maxlazar
 */

/**
 * @author    maxlazar
 *
 * @since     3.0.0
 */

return [
 'star_icon' => [
    'option' => 'star_icon',
    'data-attr' => 'data-skin',
    'defaults' => '4',
    'type' => 'select',
    'values' => [1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11,12=>12,13=>13,14=>14,15=>15,16=>16,17=>17,18=>18,19=>19,20=>20],
    'description' => 'Full Star Icon',
  ],
  'empty_star_icon' => [
    'option' => 'empty_star_icon',
    'data-attr' => 'data-skin',
    'defaults' => 'far fa-star',
    'type' => 'string',
    'values' => [
    ],
    'description' => 'Empty Star Icon',
  ],
  'half_star_icon' => [
    'option' => 'half_star_icon',
    'data-attr' => 'data-skin',
    'defaults' => 'fas fa-star-half',
    'type' => 'string',
    'values' => [
    ],
    'description' => 'Half Star Icon',
  ],
  'full_star_icon' => [
    'option' => 'full_star_icon',
    'data-attr' => 'data-skin',
    'defaults' => 'fas fa-star',
    'type' => 'string',
    'values' => [
    ],
    'description' => 'Full Star Icon',
  ],
  'split_star_icon' => [
    'option' => 'split_star_icon',
    'data-attr' => 'data-skin',
    'defaults' => '',
    'type' => 'boolean',
    'values' => [],
    'description' => 'Full Star Icon',
  ],
  'default_rate' => [
    'option' => 'default_rate',
    'data-attr' => 'data-skin',
    'defaults' => '5',
    'type' => 'string',
    'values' => '',
    'description' => 'Default Rate',
  ],
  'no_rating_icon' => [
    'option' => 'no_rating_icon',
    'data-attr' => 'data-skin',
    'defaults' => '',
    'type' => 'boolean',
    'values' => [],
    'description' => '\'No rating\' option',
  ],
  'cancel_off_icon' => [
    'option' => 'cancel_off_icon',
    'data-attr' => 'data-skin',
    'defaults' => 'far fa-minus-square',
    'type' => 'string',
    'values' => [],
    'description' => '',
  ],
  'cancel_on_icon' => [
    'option' => 'cancel_on_icon',
    'data-attr' => 'data-skin',
    'defaults' => 'fas fa-plus-square',
    'type' => 'string',
    'values' => [],
    'description' => '',
  ]
  ,
  'color_icon' => [
    'option' => 'color_icon',
    'data-attr' => 'data-skin',
    'defaults' => '#FFE01D',
    'type' => 'string',
    'values' => [],
    'description' => '',
  ]  ,
  'font_size' => [
    'option' => 'font_size',
    'data-attr' => 'data-skin',
    'defaults' => '24',
    'type' => 'string',
    'values' => [],
    'description' => '',
  ]

];
