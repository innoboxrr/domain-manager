<?php

namespace Innoboxrr\DomainManager\Exports;

use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\SearchSurge\Search\Builder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DomainProvidersExports implements FromView
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
            ) . 'domain_provider', 
            [
                'domain_providers' => $this->getQuery(),
                'exportCols' => DomainProvider::$export_cols
            ]
        );
    }

    public function getQuery()
    {   
        $builder = new Builder();
        return $builder->get(DomainProvider::class, $this->data);
    }

}