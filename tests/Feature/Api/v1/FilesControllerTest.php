<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

uses(RefreshDatabase::class);

test('uploads a file', function () {
    Storage::fake('public');

    //make file
    $file = UploadedFile::fake()->image('photo.jpg');

    //upload file
    $response = $this->withHeaders(['Authorization' => 'Bearer '.env('TOKEN_CHECK', 'artificially-token')])
                ->post(route('api.v1.files.store'), ['file' => $file]);

    $response->assertStatus(201);

    // assert photo was uploaded
    Storage::disk('public')->assertExists('uploads/' . $file->hashName());
});

test('uploads a file requires token, returns 403', function () {
    Storage::fake('public');

    //make file
    $file = UploadedFile::fake()->image('photo.jpg');

    //upload file
    $response = $this->post(route('api.v1.files.store'), ['file' => $file]);

    $response->assertStatus(403);

    // assert photo was uploaded
    Storage::disk('public')->assertMissing('uploads/' . $file->hashName());
});

test('uploads a file required', function () {
    //upload file
    $response = $this->withHeaders(['Authorization' => 'Bearer '.env('TOKEN_CHECK', 'artificially-token')])
                ->postJson(route('api.v1.files.store'), ['file' => '']);

    $response->assertStatus(422)
    ->assertJson([
        'message'=>'The file field is required.',
        'errors' => [
            'file' => [
                'The file field is required.'
            ],
        ],
    ]);
});

test('file index', function () {
    File::factory()->count(4)->create();
    
    $response = $this->get(route('api.v1.files.index'));
    $response->assertStatus(200)
    ->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'name',
                'path',
                'created_at',
                'updated_at',
            ],
        ],
    ]);

    $this->assertCount(4, $response->json('data'));
});

test('file deletes requires auth(token middleware)', function () {
    $file = File::factory()->create();

    //upload file
    $response = $this->deleteJson(route('api.v1.files.destroy', $file->id));

    $response->assertStatus(403);
});

test('file deletes works with auth(token middleware)', function () {
    $file = File::factory()->create();

    //upload file
    $response = $this->withHeaders(['Authorization' => 'Bearer '.env('TOKEN_CHECK', 'artificially-token')])
                        ->deleteJson(route('api.v1.files.destroy', $file->id));

    $response->assertStatus(204);
});

