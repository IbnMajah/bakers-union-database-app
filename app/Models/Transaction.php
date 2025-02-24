<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'description',
        'reference_number',
        'transaction_date',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('description', 'like', '%'.$search.'%')
                    ->orWhere('amount', 'like', '%'.$search.'%')
                    ->orWhereHas('account', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%')
                            ->orWhereHas('bakery', function ($query) use ($search) {
                                $query->where('name', 'like', '%'.$search.'%');
                            });
                    });
            });
        })->when($filters['bakery_id'] ?? null, function ($query, $bakery_id) {
            $query->whereHas('account', function ($query) use ($bakery_id) {
                $query->where('bakery_id', $bakery_id);
            });
        })->when($filters['account_id'] ?? null, function ($query, $account_id) {
            $query->where('account_id', $account_id);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}