<?php

if (!function_exists('file_replace')) {

    /**
     * Open default file
     * @param string $file
     * @param array $replacements
     * @param string $destination
     * @param string $dir
     * @return string
     */
    function file_replace(string $file, array $replacements, ?string $destination = null, string $dir = __DIR__)
    {
        $contents = default_file($file, $dir);

        foreach ($replacements as $str => $replacement) {
            $contents = str_replace($str, $replacement, $contents);
        }

        if ($destination) {
            make_directories($destination);
        }

        file_put_contents($destination ?? $file, $contents);
    }
}

if (!function_exists('default_file')) {

    /**
     * Open default file
     * @param string $file
     * @param string $dir
     * @return string
     */
    function default_file(string $file, string $dir = __DIR__): string
    {
        return file_get_contents(sprintf('%s/../files/%s', $dir, $file));
    }
}


if (!function_exists('make_directories')) {

    /**
     * @param string $path
     */
    function make_directories(string $path)
    {
        $dirs = explode('/', $path);
        array_pop($dirs);
        if (!$dirs) {
            return;
        }

        try {
            mkdir(implode('/', $dirs), 0777, true);
        } catch (\Exception $exception) {
            //
        }
    }
}

if (!function_exists('get_namespace')) {
    function get_namespace() {
        if (config('app.env') === 'package') {
            return 'Tychovbh\\LaravelCrud\\Tests\\App\\';
        }
        return 'App\\';
    }
}
