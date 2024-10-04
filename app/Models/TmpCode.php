<?php

namespace App\Models;

use App\Http\Traits\HasUuidTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TmpCode extends Model
{
    use HasUuidTrait;

    protected $guarded = ['id'];

    public static function createCode(User $user)
    {
        $tmpCode = self::create([
            'user_id' => $user->id,
            'code' => random_int(1000, 9999)
        ]);

        return $tmpCode->code;
    }

    /**
     * @return void
     */
    public function useCode(): void
    {
        $this->setAttribute('used_at', now());
        $this->save();
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopeByCreatedAt(Builder $query): void
    {
        $query->whereNull('used_at')
            ->where('created_at', '>=', now()->subMinutes(config('auth.code_timeout', 60)));
    }
}
