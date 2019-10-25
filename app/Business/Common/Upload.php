<?php
namespace App\Business\Commmon;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Upload
{
    /**
     * @param $file
     * @param $path
     * @param string $name
     * @param array $options
     * @return mixed
     */
    public function store($file, $path, $name = '', $options = [])
    {
        $name = !empty($name) ?: Str::random(16);
        return Storage::putFileAs($path, $file, $name, $options);
    }
}
