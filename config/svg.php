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
];
