<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shine;

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\Exceptions\IOException;
use MatthiasMullie\Minify\JS;

final class Shine
{
    private static string $charset = 'utf-8';

    /**
     * @var array<string, mixed>
     */
    private static array $data = [];

    /**
     * @var array<string, list<array{content: string, sequence: list<int>|null}>>
     */
    private static array $extendedSections = [];

    /**
     * @var list<string>
     */
    private static array $extendedViews = [];

    private static string $language = 'en-US';

    private static string $locale = 'en';

    private static string $path = '';

    /**
     * @var array<string, array{content: string, default: bool}>
     */
    private static array $sections = [];

    public static function captureSection(): void
    {
        ob_start();
    }

    public static function charset(): string
    {
        return self::convertToString(value: self::$charset);
    }

    public static function close(): void
    {
        foreach (self::$extendedViews as $view) {
            self::include(view: $view);
        }

        self::$charset          = 'utf-8';
        self::$data             = [];
        self::$extendedSections = [];
        self::$extendedViews    = [];
        self::$language         = 'en-US';
        self::$locale           = 'en';
        self::$path             = '';
        self::$sections         = [];
    }

    public static function data(string $key): string
    {
        return self::convertToString(value: self::rawData(key: $key));
    }

    public static function echo(mixed $value): void
    {
        echo self::convertToString(value: $value);
    }

    public static function echoCharset(): void
    {
        echo self::convertToString(value: self::$charset);
    }

    public static function echoData(string $key): void
    {
        self::echo(value: self::rawData(key: $key));
    }

    public static function echoLanguage(): void
    {
        echo self::convertToString(value: self::$language);
    }

    public static function echoLocale(): void
    {
        echo self::convertToString(value: self::$locale);
    }

    public static function extend(string $view): void
    {
        self::$extendedViews[] = $view;
    }

    /**
     * @param list<int>|null $sequence
     */
    public static function extendSection(string $name, string $content, ?array $sequence = null): void
    {
        $arr = self::$extendedSections[$name] ?? null;
        if ($arr === null) {
            self::$extendedSections[$name] = [['content' => $content, 'sequence' => $sequence]];

            return;
        }

        self::$extendedSections[$name][] = ['content' => $content, 'sequence' => $sequence];
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function init(array $data, string $charset = 'utf-8', string $language = 'en-US', string $locale = 'en', string $path = ''): void
    {
        self::$charset  = $charset;
        self::$data     = $data;
        self::$language = $language;
        self::$locale   = $locale;
        self::$path     = $path;
    }

    public static function language(): string
    {
        return self::convertToString(value: self::$language);
    }

    public static function locale(): string
    {
        return self::convertToString(value: self::$locale);
    }

    /**
     * @throws IOException
     */
    public static function minifyCssFile(string $filename): string
    {
        $filename = self::$path . $filename;
        $minified = (new CSS($filename))->minify();
        if ($minified !== $filename) {
            return $minified;
        }

        return sprintf(
            '<b style="color: red;">!!! Oops, cannot minify css file "%s" !!!</b>',
            htmlspecialchars(string: $filename),
        );
    }

    /**
     * @throws IOException
     */
    public static function minifyJsFile(string $filename): string
    {
        $filename = self::$path . $filename;
        $minified = (new JS($filename))->minify();
        if ($minified !== $filename) {
            return $minified;
        }

        return sprintf(
            '<b style="color: red;">!!! Oops, cannot minify javascript file "%s" !!!</b>',
            htmlspecialchars(string: $filename),
        );
    }

    public static function rawCharset(): string
    {
        return self::$charset;
    }

    public static function rawData(string $key): mixed
    {
        return self::$data[$key] ?? null;
    }

    public static function rawLanguage(): string
    {
        return self::$language;
    }

    public static function rawLocale(): string
    {
        return self::$locale;
    }

    public static function section(string $name): void
    {
        $arr = self::$sections[$name] ?? null;
        if ($arr !== null) {
            echo self::sortBySequence(name: $name, content: $arr['content']);

            return;
        }

        echo sprintf(
            '<b style="color: red;">!!! Oops, there is no section for "%s" !!!</b>',
            htmlspecialchars(string: $name),
        );
    }

    /**
     * Immutable
     */
    public static function setCapturedSection(string $name): void
    {
        self::setSection(
            name   : $name,
            content: ob_get_clean() ?: '<b style="color: red;">!!! Oops, ob_get_clean() failed !!!</b>',
        );
    }

    /**
     * Mutable
     */
    public static function setDefaultSection(string $name, string $content): void
    {
        $arr = self::$sections[$name] ?? null;
        if ($arr === null || $arr['default']) {
            self::$sections[$name] = ['content' => $content, 'default' => true];
        }
    }

    /**
     * Immutable
     */
    public static function setSection(string $name, string $content): void
    {
        $arr = self::$sections[$name] ?? null;
        if ($arr === null || $arr['default']) {
            self::$sections[$name] = ['content' => $content, 'default' => false];
        }
    }

    /**
     * Immutable
     */
    public static function setViewSection(string $name, string $view, mixed $data = null): void
    {
        self::captureSection();
        self::include(view: $view, data: $data);
        self::setCapturedSection(name: $name);
    }

    private static function convertToString(mixed $value): string
    {
        if (
            is_scalar(value: $value) || $value === null
            || (is_object(value: $value) && method_exists(object_or_class: $value, method: '__toString'))
        ) {
            $value = (string) $value;
        }

        if (is_string(value: $value)) {
            return htmlspecialchars(string: $value);
        }

        return '<b style="color: red;">!!! Oops, the value cannot be converted to string !!!</b>';
    }

    private static function include(string $view, mixed $data = null): void
    {
        $view = self::$path . $view;
        if (is_file(filename: $view)) {
            self::includeScoped(view: $view, data: $data);

            return;
        }

        echo sprintf(
            '<b style="color: red;">!!! Oops, there is no view for "%s" !!!</b>',
            htmlspecialchars(string: $view),
        );
    }

    private static function includeScoped(string $view, mixed $data): void
    {
        // Narrow down the variable scope
        include $view;
    }

    private static function sortBySequence(string $name, string $content): string
    {
        $extensions = self::$extendedSections[$name] ?? null;
        if ($extensions === null) {
            return $content;
        }

        $sequence = null;
        foreach ($extensions as $extension) {
            if ($extension['sequence'] !== null) {
                $sequence = $extension['sequence']; // first match is the last extension and it determines the order
                break;
            }
        }

        if ($sequence === null) {
            foreach ($extensions as $extension) {
                $content .= $extension['content'];
            }

            return $content;
        }

        $contents = [];
        foreach ($sequence as $s) {
            // The last extension is the first element with offset 0
            // The sequence is determined with 0,1,2 ... where 0 stands for the topmost section
            // Therefore, the sequence number must be reduced by 1 to correspond to the offset
            --$s;

            if ($s === -1) {
                $contents[] = $content;
                continue;
            }
            $contents[] = $extensions[$s]['content'] ?? null;
        }

        return implode(separator: '', array: $contents);
    }
}
