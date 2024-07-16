<?php

/**
 * Sage SVG Helpers
 */

use Log1x\SageSvg\SageSvg;

use function Roots\app;

/**
 * Render the specified SVG image if it exists.
 */
if (! function_exists('get_svg')) {
    function get_svg(string $image, string|array $class = '', array $attrs = [], array $options = []): ?string
    {
        return app(SageSvg::class)->render($image, $class, $attrs, $options);
    }
}
