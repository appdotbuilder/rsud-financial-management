<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\HospitalUnit
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Budget> $budgets
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|HospitalUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HospitalUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HospitalUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|HospitalUnit whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HospitalUnit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HospitalUnit whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HospitalUnit active()

 * 
 * @mixin \Eloquent
 */
class HospitalUnit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the transactions for this unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the budgets for this unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Scope a query to only include active units.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}