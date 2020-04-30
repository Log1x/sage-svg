<?php

namespace Log1x\SageSvg;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

use function Roots\asset;

class SageSvg
{
    /**
     * Files
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Config
     *
     * @var array
     */
    protected $config = [
        'path' => '',
        'class' => '',
        'directives' => '',
        'attributes' => [],
    ];

    /**
     * Initialize SVG
     *
     * @param  array $config
     * @return void
     */
    public function __construct($config = [])
    {
        $this->config = collect($this->config)->merge($config);
        $this->cache = collect();
        $this->files = new Filesystem();
    }

    /**
     * Render the SVG as HTML
     *
     * @param  string       $image
     * @param  string|array $class
     * @param  array        $attrs
     * @return \Illuminate\Support\HtmlString
     */
    public function render($image, $class = '', $attrs = [])
    {
        if (is_array($class)) {
            $class = implode(' ', $class);
        }

        $attrs = collect($attrs)->merge([
            'class' => $this->buildClass($class)
        ])->filter()->all();

        return new HtmlString(
            str_replace(
                '<svg',
                sprintf('<svg%s', $this->buildAttributes($attrs)),
                $this->get(
                    $this->prepare($image)
                )
            )
        );
    }

    /**
     * Get SVG from Filesystem
     *
     * @param  string|array $image
     * @return mixed
     */
    protected function get($image)
    {
        if (asset($image)->exists()) {
            return trim(
                asset($image)->contents()
            );
        }

        if ($this->files->exists($image)) {
            return trim(
                $this->files->get($image)
            );
        }

        if ($this->files->exists($this->withPath($image))) {
            return trim(
                $this->files->get($this->withPath($image))
            );
        }

        return sprintf('<!-- %s not found. -->', $image);
    }

    /**
     * Return a prepared SVG image path.
     *
     * @param  string|array $image
     * @return string
     */
    protected function prepare($image)
    {
        if (is_array($image) && ! empty($image['id'])) {
            return get_attached_file($image['id']);
        }

        if (is_string($image)) {
            if (Str::startsWith($image, '/')) {
                $image = Str::replaceFirst('/', '', $image);
            }

            if (! Str::contains($image, '/')) {
                $image = str_replace('.', '/', $image);
            }

            return Str::finish(
                strtok($image, '?'),
                '.svg'
            );
        }

        return $image;
    }

    /**
     * Return the passed string alongside the config path.
     *
     * @param  string $image
     * @return string
     */
    protected function withPath($image)
    {
        return Str::finish($this->config->get('path'), '/') . $image;
    }

    /**
     * Build CSS Classes
     *
     * @param  string $class
     * @return string
     */
    protected function buildClass($class)
    {
        return trim(
            sprintf('%s %s', $this->config->get('class'), $class)
        );
    }

    /**
     * Build element attributes.
     *
     * @param  array $attrs
     * @return string
     */
    protected function buildAttributes($attrs = [])
    {
        $attrs = array_merge(
            $this->config->get('attributes', []),
            $attrs
        );

        if (empty($attrs)) {
            return '';
        }

        return ' ' . collect($attrs)->map(function ($value, $attr) {
            if (is_int($attr)) {
                return $value;
            }

            return sprintf('%s="%s"', $attr, $value);
        })->implode(' ');
    }
}
