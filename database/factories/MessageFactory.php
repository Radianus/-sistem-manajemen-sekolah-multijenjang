<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Message;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get random sender and receiver from existing users
        $sender = User::inRandomOrder()->first();
        $receiver = User::where('id', '!=', $sender->id ?? 0)->inRandomOrder()->first();

        // Ensure we have both sender and receiver
        if (!$sender || !$receiver) {
            // Fallback or skip if not enough users
            return []; // Return empty array if cannot create valid message
        }

        return [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'subject' => fake()->optional()->sentence(rand(3, 8)),
            'content' => fake()->paragraphs(rand(1, 3), true),
            'read_at' => fake()->boolean(70) ? fake()->dateTimeBetween('-1 week', 'now') : null, // 70% chance to be read
            'parent_message_id' => null, // Default to no parent, will be handled in seeder for replies
        ];
    }
}
