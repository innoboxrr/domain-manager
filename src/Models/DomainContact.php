<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainContactRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainContactStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainContactAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainContactOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainContactMutators;

class DomainContact extends Model
{
    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainContactRelations,
        DomainContactStorage,
        DomainContactAssignment,
        DomainContactOperations,
        DomainContactMutators;

    protected $fillable = [
        'type',
        'name',
        'lastname',
        'organization',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'phone',
        'email',
        'domain_id',
    ];

    protected $creatable = [
        'type',
        'name',
        'lastname',
        'organization',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'phone',
        'email',
        'domain_id',
    ];

    protected $updatable = [
        'type',
        'name',
        'lastname',
        'organization',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'phone',
        'email',
    ];

    protected $casts = [
        // sin json/bool
    ];

    protected $protected_metas = [];

    protected $editable_metas = [
        // metas editables si aplican
    ];

    public static $export_cols = [
        'id',
        'type',
        'name',
        'lastname',
        'email',
        'phone',
        'country',
        'domain_id',
        'created_at',
    ];

    public static $loadable_relations = [
        'domain',
    ];

    public static $loadable_counts = [
        //
    ];

    /*
    protected static function newFactory()
    {
        return \Innoboxrr\DomainManager\Database\Factories\DomainContactFactory::new();
    }
    */
}
