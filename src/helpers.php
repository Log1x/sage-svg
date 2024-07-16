<?php

/**
 * Sage SVG Helpers
 */

use Illuminate\Support\HtmlString;
use Log1x\SageSvg\SageSvg;

/**
 * Render the specified SVG image if it exists.
 */
if (! function_exists('get_svg')) {
    function get_svg(string $image, string|array $class = '', array $attrs = [], array $options = []): HtmlString
    {
        return app(SageSvg::class)->render($image, $class, $attrs, $options);
    }
}
