<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\TextContent;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportJsonFeatureTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that JSON import works without lesson_id error
     */
    public function test_json_import_creates_formation_with_lesson_id(): void
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        Auth::login($user);

        // Create test JSON content
        $jsonContent = [
            'title' => 'Test Formation',
            'description' => 'Test Description',
            'chapters' => [
                [
                    'title' => 'Chapter 1',
                    'lessons' => [
                        [
                            'title' => 'Lesson 1',
                            'type' => 'text',
                            'content' => 'Lesson content here'
                        ],
                        [
                            'title' => 'Video Lesson',
                            'type' => 'video',
                            'content' => 'https://example.com/video.mp4',
                            'duration_minutes' => 15
                        ]
                    ]
                ]
            ]
        ];

        // Create a temporary file
        $jsonFile = UploadedFile::fake()->createWithContent(
            'test.json',
            json_encode($jsonContent)
        );

        // Make the request
        $response = $this->actingAs($user)
            ->post(route('formateur.import.json'), [
                'json_file' => $jsonFile
            ]);

        // Assert successful redirect
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Assert formation was created
        $formation = Formation::where('title', 'Test Formation')->first();
        $this->assertNotNull($formation);
        $this->assertEquals($user->id, $formation->user_id);

        // Assert chapter was created
        $chapter = Chapter::where('formation_id', $formation->id)->first();
        $this->assertNotNull($chapter);
        $this->assertEquals('Chapter 1', $chapter->title);

        // Assert lessons were created
        $lessons = Lesson::where('chapter_id', $chapter->id)->get();
        $this->assertCount(2, $lessons);

        // Assert first lesson (text)
        $textLesson = $lessons->first();
        $this->assertEquals('Lesson 1', $textLesson->title);
        $this->assertEquals(TextContent::class, $textLesson->lessonable_type);

        // Assert text content was created with lesson_id
        $textContent = TextContent::find($textLesson->lessonable_id);
        $this->assertNotNull($textContent);
        $this->assertEquals($textLesson->id, $textContent->lesson_id);
        $this->assertEquals('Lesson 1', $textContent->title);
        $this->assertEquals('Lesson content here', $textContent->content);

        // Assert second lesson (video)
        $videoLesson = $lessons->last();
        $this->assertEquals('Video Lesson', $videoLesson->title);
        $this->assertEquals(\App\Models\VideoContent::class, $videoLesson->lessonable_type);
    }

    /**
     * Test that invalid JSON structure returns error
     */
    public function test_invalid_json_returns_error(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        // Create invalid JSON (missing chapters)
        $jsonFile = UploadedFile::fake()->createWithContent(
            'invalid.json',
            json_encode(['title' => 'Test'])
        );

        $response = $this->actingAs($user)
            ->post(route('formateur.import.json'), [
                'json_file' => $jsonFile
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
