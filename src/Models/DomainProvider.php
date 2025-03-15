<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainProviderRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainProviderStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainProviderAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainProviderOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainProviderMutators;

class DomainProvider extends Model
{

    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainProviderRelations,
        DomainProviderStorage,
        DomainProviderAssignment,
        DomainProviderOperations,
        DomainProviderMutators;
        
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
        return \Innoboxrr\DomainManager\Database\Factories\DomainProviderFactory::new();
    }
    */

}
