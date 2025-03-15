<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainDnsRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainDnsStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainDnsAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainDnsOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainDnsMutators;

class DomainDns extends Model
{

    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainDnsRelations,
        DomainDnsStorage,
        DomainDnsAssignment,
        DomainDnsOperations,
        DomainDnsMutators;
        
    protected $fillable = [
        //FILLABLE//
    ];

    protected $creatable = [
        //CREATABLE//
    ];

    protected $updatable = [
        //UPDATABLE//
    ];

    protected $casts = [
        //CASTS//
    ];

    protected $protected_metas = [];

    protected $editable_metas = [
        //EDITABLEMETAS//
    ];

    public static $export_cols = [
        //EXPORTCOLS//
    ];

    public static $loadable_relations = [
        //LOADABLERELATIONS//
    ];

    public static $loadable_counts = [
        //LOADABLECOUNTS//
    ];

    /*
    protected static function newFactory()
    {
        return \Innoboxrr\DomainManager\Database\Factories\DomainDnsFactory::new();
    }
    */

}
