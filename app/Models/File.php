<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /** @use HasFactory<\Database\Factories\FileFactory> */
    use HasFactory;

    protected $fillable = ['name', 'path', 'disk'];

    public function __toString()
    {
        // ensure consistent output format
        $data = $this->toArray();
        ksort($data);
        return json_encode($data);
    }
}
