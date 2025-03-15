<?php

namespace Innoboxrr\DomainManager\Exports;

use Innoboxrr\DomainManager\Models\DomainPayment;
use Innoboxrr\SearchSurge\Search\Builder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DomainPaymentsExports implements FromView
{

    protected $data;

    public function __construct( array $data) 
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view(
            config(
                'innoboxrrdomainmanager.excel_view', 
                'innoboxrrdomainmanager::excel.'
            ) . 'domain_payment', 
            [
                'domain_payments' => $this->getQuery(),
                'exportCols' => DomainPayment::$export_cols
            ]
        );
    }

    public function getQuery()
    {   
        $builder = new Builder();
        return $builder->get(DomainPayment::class, $this->data);
    }

}