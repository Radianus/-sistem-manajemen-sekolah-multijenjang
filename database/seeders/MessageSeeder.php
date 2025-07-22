<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;
use Database\Factories\MessageFactory;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        if ($users->count() < 2) {
            $this->command->info('Tidak cukup user untuk seed messages. Minimal 2 user diperlukan.');
            return;
        }

        // Buat 15 pesan utama (tanpa balasan)
        Message::factory()->count(15)->create();

        // Buat beberapa balasan
        $mainMessages = Message::all()->shuffle(); // Ambil semua pesan utama
        $seededRepliesCount = 0;

        foreach ($mainMessages->take(5) as $mainMessage) {
            // Buat 1-3 balasan untuk setiap pesan utama
            for ($i = 0; $i < rand(1, 3); $i++) {
                $sender = $mainMessage->receiver;
                $receiver = $mainMessage->sender;

                if ($sender && $receiver) {
                    Message::factory()->create([
                        'sender_id' => $sender->id,
                        'receiver_id' => $receiver->id,
                        'subject' => 'Re: ' . $mainMessage->subject,
                        'content' => fake()->sentence(rand(5, 10)),
                        'parent_message_id' => $mainMessage->id,
                        'read_at' => fake()->boolean(80) ? fake()->dateTimeBetween($mainMessage->created_at, 'now') : null,
                    ]);
                    $seededRepliesCount++;
                }
            }
        }
        $this->command->info('Messages seeded: ' . Message::count() . ' (including ' . $seededRepliesCount . ' replies).');
    }
}
