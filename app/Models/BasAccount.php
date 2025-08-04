<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\BasAccount
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property int|null $parent_id
 * @property string $type
 * @property int $level
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BasAccount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BasAccount> $children
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Budget> $budgets
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|BasAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BasAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BasAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|BasAccount whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasAccount whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasAccount whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasAccount whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasAccount active()

 * 
 * @mixin \Eloquent
 */
class BasAccount extends Model
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
        'parent_id',
        'type',
        'level',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parent account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BasAccount::class, 'parent_id');
    }

    /**
     * Get the child accounts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(BasAccount::class, 'parent_id');
    }

    /**
     * Get the transactions for this account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the budgets for this account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Scope a query to only include active accounts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}