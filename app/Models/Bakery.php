<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bakery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'address',
        'contact_person',
        'phone',
        'email',
        'status',
    ];

    protected $appends = ['last_payment'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'bakery_id', 'id');
    }

    public function transactions()
    {
        return $this->hasManyThrough(
            Transaction::class,
            Account::class,
            'bakery_id', // Foreign key on accounts table
            'account_id', // Foreign key on transactions table
            'id', // Local key on bakeries table
            'id'  // Local key on accounts table
        );
    }

    public function getLastPaymentAttribute()
    {
        $lastPayment = $this->transactions()
            ->where('type', 'credit')
            ->latest('transaction_date')
            ->first();

        return $lastPayment ? Carbon::parse($lastPayment->transaction_date)->format('d/m/Y') : 'No payments';
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('contact_person', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->orWhere('address', 'like', '%'.$search.'%')
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%');
                    });
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }
}