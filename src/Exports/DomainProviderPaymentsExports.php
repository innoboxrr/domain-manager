<?php

namespace Innoboxrr\DomainManager\Exports;

use Innoboxrr\DomainManager\Models\DomainProviderPayment;
use Innoboxrr\SearchSurge\Search\Builder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DomainProviderPaymentsExports implements FromView
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
            ) . 'domain_provider_payment', 
            [
                'domain_provider_payments' => $this->getQuery(),
                'exportCols' => DomainProviderPayment::$export_cols
            ]
        );
    }

    public function getQuery()
    {   
        $builder = new Builder();
        return $builder->get(DomainProviderPayment::class, $this->data);
    }

}