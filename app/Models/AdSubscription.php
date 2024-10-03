<?php

namespace App\Models;

use App\Http\Traits\HasUuidTrait;
use Illuminate\Database\Eloquent\Model;

class AdSubscription extends Model
{
    use HasUuidTrait;

    protected $guarded = ['id'];
}
