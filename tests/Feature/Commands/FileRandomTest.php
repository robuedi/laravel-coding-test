<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\File;
use Log;

uses(RefreshDatabase::class);

test('file random return the right data', function () {
    $file = File::factory()->create();
    
    $this->artisan('file:random')
        ->assertExitCode(0)
        ->expectsOutput('File details: '.(string)$file);
});

test('file random return the right data for no record', function () {
    $this->artisan('file:random')
        ->assertExitCode(1)
        ->expectsOutput('No file found, please upload at least one!');
});
