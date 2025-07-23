<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MessageAttachment;
use App\Models\Message;
use Database\Factories\MessageAttachmentFactory;

class MessageAttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = Message::all();
        if ($messages->isEmpty()) {
            $this->command->info('Tidak ada pesan untuk seed message attachments.');
            return;
        }

        // Buat beberapa lampiran untuk pesan-pesan acak
        foreach ($messages->take(10) as $message) { // Lampirkan ke 10 pesan pertama
            // Buat 1-2 lampiran per pesan
            MessageAttachment::factory()->count(rand(1, 2))->create([
                'message_id' => $message->id,
            ]);
        }
        $this->command->info('Message attachments seeded: ' . MessageAttachment::count());
    }
}
