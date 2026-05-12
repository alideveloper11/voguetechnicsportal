<?php

if (!function_exists('uploadFile')) {
    function uploadFile($file, $folder)
    {
        $folder = trim($folder, '/\\');
        $destinationPath = public_path($folder);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }

        $filename = $file->hashName();
        $file->move($destinationPath, $filename);

        return $folder . '/' . $filename;
    }
}
