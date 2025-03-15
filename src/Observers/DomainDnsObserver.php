<?php
 
namespace Innoboxrr\DomainManager\Observers;
 
use Innoboxrr\DomainManager\Models\DomainDns;
 
class DomainDnsObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    // public $afterCommit = true;

    /**
     * Handle the DomainDns "created" event.
     */
    public function created(DomainDns $domainDns): void
    {
        // Remove if laravel-audit is used: $domainDns->log('created');
    }
 
    /**
     * Handle the DomainDns "updated" event.
     */
    public function updated(DomainDns $domainDns): void
    {
        // Remove if laravel-audit is used: $domainDns->log('updated');
    }
 
    /**
     * Handle the DomainDns "deleted" event.
     */
    public function deleted(DomainDns $domainDns): void
    {
        // Remove if laravel-audit is used: $domainDns->log('deleted');
    }
 
    /**
     * Handle the DomainDns "restored" event.
     */
    public function restored(DomainDns $domainDns): void
    {
        // Remove if laravel-audit is used: $domainDns->log('restored');
    }
 
    /**
     * Handle the DomainDns "forceDeleted" event.
     */
    public function forceDeleted(DomainDns $domainDns): void
    {
        // Remove if laravel-audit is used: $domainDns->log('forceDeleted');
    }
}