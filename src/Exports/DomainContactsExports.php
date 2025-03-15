<?php

namespace Innoboxrr\DomainManager\Exports;

use Innoboxrr\DomainManager\Models\DomainContact;
use Innoboxrr\SearchSurge\Search\Builder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DomainContactsExports implements FromView
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
            ) . 'domain_contact', 
            [
                'domain_contacts' => $this->getQuery(),
                'exportCols' => DomainContact::$export_cols
            ]
        );
    }

    public function getQuery()
    {   
        $builder = new Builder();
        return $builder->get(DomainContact::class, $this->data);
    }

}