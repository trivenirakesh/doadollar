<?php

namespace App\Observers;

use App\Models\UploadType;
use Illuminate\Support\Facades\Cache;

class UploadTypeObserver
{
    /**
     * Handle the UploadType "created" event.
     *
     * @param  \App\Models\UploadType  $uploadType
     * @return void
     */
    public function created(UploadType $uploadType)
    {
        Cache::forget('uploadTypes');
    }

    /**
     * Handle the UploadType "updated" event.
     *
     * @param  \App\Models\UploadType  $uploadType
     * @return void
     */
    public function updated(UploadType $uploadType)
    {
        Cache::forget('uploadTypes');
    }

    /**
     * Handle the UploadType "deleted" event.
     *
     * @param  \App\Models\UploadType  $uploadType
     * @return void
     */
    public function deleted(UploadType $uploadType)
    {
        Cache::forget('uploadTypes');
    }

    /**
     * Handle the UploadType "restored" event.
     *
     * @param  \App\Models\UploadType  $uploadType
     * @return void
     */
    public function restored(UploadType $uploadType)
    {
        //
    }

    /**
     * Handle the UploadType "force deleted" event.
     *
     * @param  \App\Models\UploadType  $uploadType
     * @return void
     */
    public function forceDeleted(UploadType $uploadType)
    {
        //
    }
}
