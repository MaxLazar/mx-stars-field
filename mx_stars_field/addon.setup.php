<?php

$addonJson = json_decode(file_get_contents(__DIR__ . '/addon.json'));

if (!defined('MX_STAR_NAME')) {
    define('MX_STAR_NAME', $addonJson->name);
    define('MX_STAR_VERSION', $addonJson->version);
    define('MX_STAR_DOCS', '');
    define('MX_STAR_DESCRIPTION', $addonJson->description);
    define('MX_STAR_DEBUG', false);
}

return [
    'name'           => $addonJson->name,
    'description'    => $addonJson->description,
    'version'        => $addonJson->version,
    'namespace'      => $addonJson->namespace,
    'author'         => 'Max Lazar',
    'author_url'     => 'https://eecms.dev',
    'settings_exist' => true,
    // Advanced settings
    'fieldtypes'     => array(
        'StarsField'     => array(
        'name'           => 'MX Stars Field'
        )
    )
];
