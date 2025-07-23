<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MessageAttachment;
use App\Models\Message;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MessageAttachment>
 */
class MessageAttachmentFactory extends Factory
{
    protected $model = MessageAttachment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $message = Message::inRandomOrder()->first();
        if (!$message) {
            return []; // Skip if no messages exist
        }

        // Dummy file details
        $fileName = fake()->word() . '.' . fake()->randomElement(['pdf', 'docx', 'jpg', 'zip']);
        $fileSize = fake()->numberBetween(102400, 5120000); // 100KB to 5MB

        return [
            'message_id' => $message->id,
            'file_path' => 'message_attachments/' . fake()->uuid() . '/' . $fileName, // Dummy path
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_mime_type' => fake()->mimeType(), // Generates a random mime type
        ];
    }
}
