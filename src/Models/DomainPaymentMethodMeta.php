<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DomainPaymentMethodMeta extends Model
{

    use HasFactory;

    protected $guarded = [];

    public function domainPaymentMethod()
    {
        return $this->belongsTo(DomainPaymentMethod::class);
    }

}
