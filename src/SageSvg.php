<?php

namespace Log1x\SageSvg;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Throwable;

use function Roots\asset;

class SageSvg
{
    /**
     * The asset manifests.
     */
    protected array $manifests;

    /**
     * Create a new Sage SVG instance.
     */
    public function __construct(protected Filesystem $files)
    {
        $this->manifests = config('assets.manifests');
    }

    /**
     * Render the SVG as HTML
     */
    public function render(string $image, string|array $class = '', array $attrs = [], array $options = []): HtmlString
    {
        $options = collect([
            'idPrefix' => null,
        ])->merge($options);

        $class = is_array($class)
            ? implode(' ', $class)
            : $class;

        $attrs = collect($attrs)->merge([
            'class' => $this->buildClass($class),
        ])->filter()->all();

        $svg = str_replace(
            '<svg',
            sprintf('<svg%s', $this->buildAttributes($attrs)),
            $this->getContents(
                $this->resolvePath($image)
            )
        );

        if ($idPrefix = $options->get('idPrefix')) {
            $svg = preg_replace(
                '/(id=[\'"]|url\([\'"]?#|href=["\']#)(.*?)([\'"])/m',
                "$1{$idPrefix}-$2$3",
                $svg
            );
        }

        return new HtmlString($svg);
    }

    /**
     * Retrieve the SVG contents from the filesystem.
     */
    protected function getContents(string $image): string
    {
        if ($svg = $this->asset($image)) {
            return trim($svg);
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
     * Retrieve an asset depending on the manifest type.
     */
    protected function asset(string $image): ?string
    {
        foreach ($this->manifests as $key => $value) {
            $bundle = $value['bundles'] ?? null;

            if ($bundle && Str::endsWith($bundle, 'build/manifest.json')) {
                try {
                    return Vite::content(Str::of($image)->ltrim('/')->start('resources/')->__toString());
                } catch (Throwable) {
                    //
                }
            }

            if (asset($image, $key)->exists()) {
                return asset($image, $key)->contents();
            }
        }

        return null;
    }

    /**
     * Resolve the specified image path.
     */
    protected function resolvePath(string|array $image): string
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
     * Retrieve the path.
     */
    protected function getPath(): string
    {
        return Str::finish(config('svg.path', public_path()), '/');
    }

    /**
     * Prepend the path to the image.
     */
    protected function withPath(string $image): string
    {
        return "{$this->getPath()}{$image}";
    }

    /**
     * Retrieve the default classes.
     */
    protected function getDefaultClasses(): string
    {
        return config('svg.class', '');
    }

    /**
     * Build the class attribute.
     */
    protected function buildClass(string $class): string
    {
        return trim(
            sprintf('%s %s', $this->getDefaultClasses(), $class)
        );
    }

    /**
     * Retrieve the attributes.
     */
    protected function getAttributes(): array
    {
        return config('svg.attributes', []);
    }

    /**
     * Build the attributes.
     */
    protected function buildAttributes(array $attrs = []): string
    {
        $attrs = array_merge(
            $this->getAttributes(),
            $attrs
        );

        if (! $attrs) {
            return '';
        }

        return ' '.collect($attrs)
            ->map(fn ($value, $attr) => is_int($attr) ? $value : sprintf('%s="%s"', $attr, $value))
            ->implode(' ');
    }
}
