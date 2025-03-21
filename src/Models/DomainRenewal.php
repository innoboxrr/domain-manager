<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainRenewalRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainRenewalStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainRenewalAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainRenewalOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainRenewalMutators;

class DomainRenewal extends Model
{

    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainRenewalRelations,
        DomainRenewalStorage,
        DomainRenewalAssignment,
        DomainRenewalOperations,
        DomainRenewalMutators;
        
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
        return \Innoboxrr\DomainManager\Database\Factories\DomainRenewalFactory::new();
    }
    */

}
