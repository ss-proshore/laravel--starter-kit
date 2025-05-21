<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    protected $table= 'activity_log';
    
    public function causer():MorphTo
    {
        return $this->morphTo();
    }
}
