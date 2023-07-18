<?php

namespace App\Observers;

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Cache;

class EmailTemplateObserver
{
    /**
     * Handle the EmailTemplate "created" event.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return void
     */
    public function created(EmailTemplate $emailTemplate)
    {
        Cache::forget('emailTemplates');
    }

    /**
     * Handle the EmailTemplate "updated" event.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return void
     */
    public function updated(EmailTemplate $emailTemplate)
    {
        Cache::forget('emailTemplates');
    }

    /**
     * Handle the EmailTemplate "deleted" event.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return void
     */
    public function deleted(EmailTemplate $emailTemplate)
    {
        Cache::forget('emailTemplates');
    }

    /**
     * Handle the EmailTemplate "restored" event.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return void
     */
    public function restored(EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Handle the EmailTemplate "force deleted" event.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return void
     */
    public function forceDeleted(EmailTemplate $emailTemplate)
    {
        //
    }
}
