<?php

namespace EmailCampaign\Manager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailCampaign extends Model
{
    protected $fillable = ['title', 'subject', 'body'];

    public function logs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }
}
