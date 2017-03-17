<?php

/**
 * This file is part of the Lumineer role & 
 * permission management solution for Lumen.
 *
 * @author Vince Kronlein <vince@19peaches.com>
 * @license https://github.com/19peaches/lumineer/blob/master/LICENSE
 * @copyright 19 Peaches, LLC. All Rights Reserved.
 */

if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if ( ! function_exists('app_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function app_path($path = '')
    {
        return app()->basePath() . '/app' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('route_parameter')) {
    /**
     * Get a given parameter from the route.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return string|object
     */
    function route_parameter($name, $default = null)
    {
        $routeInfo = app('request')->route();

        return array_get($routeInfo[2], $name, $default);
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param string $path
     *
     * @return string
     */
    function public_path($path = '')
    {
        return env('PUBLIC_PATH', base_path('public')).($path ? '/'.$path : $path);
    }
}

if (!function_exists('elixir')) {
    /**
     * Get the path to a versioned Elixir file.
     *
     * @param string $file
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    function elixir($file)
    {
        static $manifest = null;

        $buildPath = trim(env('ELIXIR_BUILD_PATH', 'build'), '/'); // Trim start & end slashes.

        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path($buildPath.'/rev-manifest.json')), true);
        }

        if (isset($manifest[$file])) {
            return '/'.$buildPath.'/'.$manifest[$file];
        }

        throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
    }
}

if (! function_exists('lumineer')) {

    /**
     * Get the Lumineer instance from the container.
     * 
     * @return \Peaches\Lumineer\Lumineer
     */
    function lumineer() {
        return app()->make('lumineer');
    }
}