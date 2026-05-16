<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Campaign $campaign)
    {
    }

    public function handle(): void
    {
        // TODO: Implement actual email sending logic.
        // Steps to implement:
        // 1. Resolve subscribers based on $this->campaign->target_lang (null = all active)
        // 2. For each subscriber, send the appropriate language subject/body via Mail facade
        // 3. Create campaign_subscribers pivot records with sent_at timestamp
        // 4. Increment $this->campaign->sent_count
        // 5. Update $this->campaign->status to 'sent' and sent_at to now()
        // 6. Handle failures: catch exceptions, set status to 'failed'
    }
}
