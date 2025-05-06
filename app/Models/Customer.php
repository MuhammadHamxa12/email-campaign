<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'status', 'plan_expiry_date'];

    public function emailStatuses()
    {
        return $this->hasMany(EmailStatus::class);
    }
}
