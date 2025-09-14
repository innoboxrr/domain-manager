<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainPaymentMethodRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainPaymentMethodStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainPaymentMethodAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainPaymentMethodOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainPaymentMethodMutators;

class DomainPaymentMethod extends Model
{
    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainPaymentMethodRelations,
        DomainPaymentMethodStorage,
        DomainPaymentMethodAssignment,
        DomainPaymentMethodOperations,
        DomainPaymentMethodMutators;

    protected $fillable = [
        'processor',
        'payload',
        'user_id',
    ];

    protected $creatable = [
        'processor',
        'payload',
        'user_id',
    ];

    protected $updatable = [
        'processor',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    protected $protected_metas = [];

    protected $editable_metas = [
        // metas editables si aplican
    ];

    public static $export_cols = [
        'id',
        'processor',
        'user_id',
        'created_at',
    ];

    public static $loadable_relations = [
        'user',
        'subscriptions',
        'payments',
        // 'metas' si tienes modelo Meta
    ];

    public static $loadable_counts = [
        'subscriptions',
        'payments',
    ];

    /*
    protected static function newFactory()
    {
        return \Innoboxrr\DomainManager\Database\Factories\DomainPaymentMethodFactory::new();
    }
    */
}
