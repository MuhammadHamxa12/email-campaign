<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'subject', 'body'];

    public function emailStatuses()
    {
        return $this->hasMany(EmailStatus::class);
    }
}
