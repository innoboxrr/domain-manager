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
        return \Innoboxrr\DomainManager\Database\Factories\DomainContactFactory::new();
    }
    */

}
