<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Budget
 *
 * @property int $id
 * @property int $fiscal_year
 * @property int $bas_account_id
 * @property int $hospital_unit_id
 * @property string $type
 * @property string $amount
 * @property string|null $description
 * @property string $status
 * @property int $created_by
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BasAccount $basAccount
 * @property-read \App\Models\HospitalUnit $hospitalUnit
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User|null $approver
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Budget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Budget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Budget query()
 * @method static \Illuminate\Database\Eloquent\Builder|Budget whereFiscalYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Budget whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Budget whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Budget approved()

 * 
 * @mixin \Eloquent
 */
class Budget extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fiscal_year',
        'bas_account_id',
        'hospital_unit_id',
        'type',
        'amount',
        'description',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fiscal_year' => 'integer',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the BAS account for this budget.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function basAccount(): BelongsTo
    {
        return $this->belongsTo(BasAccount::class);
    }

    /**
     * Get the hospital unit for this budget.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hospitalUnit(): BelongsTo
    {
        return $this->belongsTo(HospitalUnit::class);
    }

    /**
     * Get the user who created this budget.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved this budget.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope a query to only include approved budgets.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}