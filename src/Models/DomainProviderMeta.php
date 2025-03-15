<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DomainProviderMeta extends Model
{

    use HasFactory;

    protected $guarded = [];

    public function domainProvider()
    {
        return $this->belongsTo(DomainProvider::class);
    }

}
