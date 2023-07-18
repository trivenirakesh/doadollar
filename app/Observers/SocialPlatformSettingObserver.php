<?php

namespace App\Observers;

use App\Models\SocialPlatformSetting;
use Illuminate\Support\Facades\Cache;

class SocialPlatformSettingObserver
{
    /**
     * Handle the SocialPlatformSetting "created" event.
     *
     * @param  \App\Models\SocialPlatformSetting  $socialPlatformSetting
     * @return void
     */
    public function created(SocialPlatformSetting $socialPlatformSetting)
    {
        Cache::forget('socialPlatformSetting');
    }

    /**
     * Handle the SocialPlatformSetting "updated" event.
     *
     * @param  \App\Models\SocialPlatformSetting  $socialPlatformSetting
     * @return void
     */
    public function updated(SocialPlatformSetting $socialPlatformSetting)
    {
        Cache::forget('socialPlatformSetting');
    }

    /**
     * Handle the SocialPlatformSetting "deleted" event.
     *
     * @param  \App\Models\SocialPlatformSetting  $socialPlatformSetting
     * @return void
     */
    public function deleted(SocialPlatformSetting $socialPlatformSetting)
    {
        Cache::forget('socialPlatformSetting');
    }

    /**
     * Handle the SocialPlatformSetting "restored" event.
     *
     * @param  \App\Models\SocialPlatformSetting  $socialPlatformSetting
     * @return void
     */
    public function restored(SocialPlatformSetting $socialPlatformSetting)
    {
        //
    }

    /**
     * Handle the SocialPlatformSetting "force deleted" event.
     *
     * @param  \App\Models\SocialPlatformSetting  $socialPlatformSetting
     * @return void
     */
    public function forceDeleted(SocialPlatformSetting $socialPlatformSetting)
    {
        //
    }
}
