<?php

namespace Tests\Unit;

use App\Http\Controllers\Clean\Formateur\FormateurPageController;
use App\Models\TextContent;
use PHPUnit\Framework\TestCase;

class ImportJsonTest extends TestCase
{
    /**
     * Test that getLessonableType returns correct class names
     */
    public function test_get_lessonable_type_returns_correct_classes(): void
    {
        $controller = new FormateurPageController();

        // Use reflection to access private method
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('getLessonableType');
        $method->setAccessible(true);

        // Test different types
        $this->assertEquals(TextContent::class, $method->invoke($controller, 'text'));
        $this->assertEquals(\App\Models\VideoContent::class, $method->invoke($controller, 'video'));
        $this->assertEquals(\App\Models\Quiz::class, $method->invoke($controller, 'quiz'));
        $this->assertEquals(TextContent::class, $method->invoke($controller, 'unknown'));
    }

    /**
     * Test that createLessonContent accepts lesson_id parameter
     * This test verifies the method signature is correct
     */
    public function test_create_lesson_content_method_signature(): void
    {
        $controller = new FormateurPageController();

        // Use reflection to access private method
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('createLessonContent');
        $method->setAccessible(true);

        // Check method parameters
        $parameters = $method->getParameters();
        $this->assertCount(2, $parameters);

        // Check first parameter (lessonData)
        $this->assertEquals('lessonData', $parameters[0]->getName());
        $this->assertFalse($parameters[0]->isOptional());

        // Check second parameter (lessonId)
        $this->assertEquals('lessonId', $parameters[1]->getName());
        $this->assertFalse($parameters[1]->isOptional());
        $this->assertEquals('int', $parameters[1]->getType()->getName());
    }
}
