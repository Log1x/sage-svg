<?php

use function Roots\base_path;

return [

    /*
    |--------------------------------------------------------------------------
    | Path
    |--------------------------------------------------------------------------
    |
    | This value is the default path used by SageSVG for finding SVG files.
    | This path is then resolved internally if an absolute path is not being
    | used.
    |
    */

    'path' => base_path('dist'),

    /*
    |--------------------------------------------------------------------------
    | Class
    |--------------------------------------------------------------------------
    |
    | Here you can specify a default class to be added on all inlined SVGs.
    | Much like how you would define multiple classes in an HTML attribute,
    | you may separate each class using a space.
    |
    */

    'class' => '',

    /*
	|--------------------------------------------------------------------------
	| Attributes
	|--------------------------------------------------------------------------
	|
	| This array defines the default SVG attributes.
	| You can overwrite them via third parameter and can be used to set
	| attributes such as ['stroke-width' => '1.5'] to all SVGs.
	|
	*/

    'attributes' => array(
    ),

    /*
    |--------------------------------------------------------------------------
    | Directives
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom directives and the directories they resolve
    | to. For example, you could add font-awesome library to your dist folder
    | and then do things like:
    |
    | ['fa' => 'fa.solid']
    |   ↪ gives you access to `@fa('download')` in your templates.
    |
    | ['brands' => 'fa.brands']
    |   ↪ gives you access to `@brands('twitter')` in your templates.
    |
    | Use the key to set the directive and the value to set the path to the
    | SVG set.
    |
    */

    'directives' => [
        // 'fa'     => 'fa.solid',
        // 'brands' => 'fa.brands',
    ],
];
