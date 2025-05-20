<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PremiumService extends Model {
    use GlobalStatus,BelongsToTenant;
}
