<?php

namespace EmailCampaign\Manager\Models;

use Illuminate\Database\Eloquent\Model;
use EmailCampaign\Manager\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $fillable = ['email_campaign_id', 'customer_id', 'status', 'error'];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(EmailCampaign::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
