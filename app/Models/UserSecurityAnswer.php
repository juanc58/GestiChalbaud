<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSecurityAnswer extends Model
{
    protected $fillable = ['user_id', 'security_question_id', 'answer'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SecurityQuestion::class, 'security_question_id');
    }
}
