<?php

namespace EmailCampaign\Manager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = ['name', 'email', 'phone_number', 'status', 'plan_expiry_date'];

    public function logs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }
}
