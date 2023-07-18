<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\Role;
use App\Models\CampaignCategory;
use App\Models\PaymentGatewaySetting;
use App\Models\SocialPlatformSetting;
use App\Models\UploadType;
use App\Models\EmailTemplate;
use App\Models\StaticPage;
use App\Observers\CampaignCategoryObserver;
use App\Observers\EmailTemplateObserver;
use App\Observers\PaymentGatewaySettingObserver;
use App\Observers\RoleObserver;
use App\Observers\SocialPlatformSettingObserver;
use App\Observers\StaticPagesObserver;
use App\Observers\UploadTypeObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Role::observe(RoleObserver::class);
        CampaignCategory::observe(CampaignCategoryObserver::class);
        PaymentGatewaySetting::observe(PaymentGatewaySettingObserver::class);
        SocialPlatformSetting::observe(SocialPlatformSettingObserver::class);
        UploadType::observe(UploadTypeObserver::class);
        EmailTemplate::observe(EmailTemplateObserver::class);
        StaticPage::observe(StaticPagesObserver::class);
    }
}
