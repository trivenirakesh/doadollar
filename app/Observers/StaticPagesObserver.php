<?php

namespace App\Observers;

use App\Models\StaticPage;
use Illuminate\Support\Facades\Cache;

class StaticPagesObserver
{
    /**
     * Handle the StaticPage "created" event.
     *
     * @param  \App\Models\StaticPage  $staticPage
     * @return void
     */
    public function created(StaticPage $staticPage)
    {
        Cache::forget('staticPages');
    }

    /**
     * Handle the StaticPage "updated" event.
     *
     * @param  \App\Models\StaticPage  $staticPage
     * @return void
     */
    public function updated(StaticPage $staticPage)
    {
        Cache::forget('staticPages');
    }

    /**
     * Handle the StaticPage "deleted" event.
     *
     * @param  \App\Models\StaticPage  $staticPage
     * @return void
     */
    public function deleted(StaticPage $staticPage)
    {
        Cache::forget('staticPages');
    }

    /**
     * Handle the StaticPage "restored" event.
     *
     * @param  \App\Models\StaticPage  $staticPage
     * @return void
     */
    public function restored(StaticPage $staticPage)
    {
        //
    }

    /**
     * Handle the StaticPage "force deleted" event.
     *
     * @param  \App\Models\StaticPage  $staticPage
     * @return void
     */
    public function forceDeleted(StaticPage $staticPage)
    {
        //
    }
}
