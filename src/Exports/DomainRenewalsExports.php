<?php

namespace Innoboxrr\DomainManager\Exports;

use Innoboxrr\DomainManager\Models\DomainRenewal;
use Innoboxrr\SearchSurge\Search\Builder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DomainRenewalsExports implements FromView
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
            ) . 'domain_renewal', 
            [
                'domain_renewals' => $this->getQuery(),
                'exportCols' => DomainRenewal::$export_cols
            ]
        );
    }

    public function getQuery()
    {   
        $builder = new Builder();
        // lazy() en vez de get(): un export recorre la tabla entera y
        // hidratar todas las filas a la vez es lo que revienta la memoria.
        return $builder->lazy(DomainRenewal::class, $this->data);
    }

}