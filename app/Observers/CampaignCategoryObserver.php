<?php

namespace App\Observers;

use App\Models\CampaignCategory;
use Illuminate\Support\Facades\Cache;

class CampaignCategoryObserver
{
    /**
     * Handle the CampaignCategory "created" event.
     *
     * @param  \App\Models\CampaignCategory  $campaignCategory
     * @return void
     */
    public function created(CampaignCategory $campaignCategory)
    {
        Cache::forget('campaignCategory');
    }

    /**
     * Handle the CampaignCategory "updated" event.
     *
     * @param  \App\Models\CampaignCategory  $campaignCategory
     * @return void
     */
    public function updated(CampaignCategory $campaignCategory)
    {
        Cache::forget('campaignCategory');
    }

    /**
     * Handle the CampaignCategory "deleted" event.
     *
     * @param  \App\Models\CampaignCategory  $campaignCategory
     * @return void
     */
    public function deleted(CampaignCategory $campaignCategory)
    {
        Cache::forget('campaignCategory');
    }

    /**
     * Handle the CampaignCategory "restored" event.
     *
     * @param  \App\Models\CampaignCategory  $campaignCategory
     * @return void
     */
    public function restored(CampaignCategory $campaignCategory)
    {
        //
    }

    /**
     * Handle the CampaignCategory "force deleted" event.
     *
     * @param  \App\Models\CampaignCategory  $campaignCategory
     * @return void
     */
    public function forceDeleted(CampaignCategory $campaignCategory)
    {
        //
    }
}
