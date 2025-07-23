<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'file_path',
        'file_name',
        'file_size',
        'file_mime_type',
    ];

    /**
     * Get the message that the attachment belongs to.
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
