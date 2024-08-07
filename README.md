# Sage SVG

![Latest Stable Version](https://img.shields.io/packagist/v/log1x/sage-svg.svg?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/log1x/sage-svg.svg?style=flat-square)
![Build Status](https://img.shields.io/github/actions/workflow/status/log1x/sage-svg/main.yml?branch=master&style=flat-square)

Sage SVG is a simple package for using inline SVGs in your Sage 10 projects.

## Requirements

- [Sage](https://github.com/roots/sage) >= 10.x
- [Acorn](https://github.com/roots/acorn) >= 4.x
- [PHP](https://secure.php.net/manual/en/install.php) >= 8.1
- [Composer](https://getcomposer.org/download/)

## Installation

Install via Composer:

```bash
$ composer require log1x/sage-svg
```

## Usage

By default, the following paths are checked for your SVG (in order):

- If passed an array containing `id`, it is assumed to be a WordPress attachment and is ran through [`get_attached_file()`](https://developer.wordpress.org/reference/functions/get_attached_file).
- Your default asset manifest (usually `mix-manifest.json`).
- Path relative to `config('svg.path')` which is `public_path()` by default.
- Absolute web root path.

### Blade Directive

Unless you require advance functionality from somewhere such as a Controller, the best way to use Sage SVG is with the Blade directive straight in your templates.

```php
# Relative path (with dot notation) – resolves to `app/themes/<your theme>/dist/images/logo.svg` by default
@svg('images.logo')

# Absolute path from webroot with `w-32 h-auto` CSS classes and an aria-label
@svg('app/uploads/2019/07/logo.svg', 'w-32 h-auto', ['aria-label' => 'Logo'])
```

### Helper

The easiest way to use SVG outside of a Blade template is the global `get_svg()` helper function. `get_svg()` will return `false` if no image is found.

```php
# Relative path
$image = get_svg('images.logo');

# Absolute path from webroot with `w-32 h-auto` CSS classes
$image = get_svg('app/uploads/2019/07/logo.svg', 'w-32 h-auto');

# WordPress attachment (e.g. ACF field) with `my-logo` CSS class
$image = get_svg(
    get_field('logo_svg'),
    'my-logo'
);
```

### Facade

Another option for rendering an SVG is using the `SageSvg` Facade:

```php
use Log1x\SageSvg\Facades\SageSvg;

$image = SageSvg::render('images.logo');
```

## Configuration

The configuration file, `svg.php`, can be published using Acorn:

```bash
$ wp acorn vendor:publish --provider='Log1x\SageSvg\SageSvgServiceProvider'
```

You can read the DocBlocks in `config/svg.php` for more details.

## Why another SVG Package?

> Didn't you author Blade SVG Sage? Why another SVG package?

While I do have my fork of [Blade SVG](https://github.com/adamwathan/blade-svg) called [Blade SVG Sage](https://github.com/log1x/blade-svg-sage), I find it rather underwhelming due to the following reasons:

- Unable to handle WordPress attachments
- Unable to inline SVGs that aren't set in a specific path
- Unable to properly use the asset manifest.
- I know QWp6t

## Bug Reports

If you discover a bug in Sage SVG, please [open an issue](https://github.com/log1x/sage-svg/issues).

## Contributing

Contributing whether it be through PRs, reporting an issue, or suggesting an idea is encouraged and appreciated.

## License

Sage SVG is provided under the [MIT License](https://github.com/log1x/sage-svg/blob/master/LICENSE.md).
