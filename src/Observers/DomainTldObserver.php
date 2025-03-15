<?php
 
namespace Innoboxrr\DomainManager\Observers;
 
use Innoboxrr\DomainManager\Models\DomainTld;
 
class DomainTldObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    // public $afterCommit = true;

    /**
     * Handle the DomainTld "created" event.
     */
    public function created(DomainTld $domainTld): void
    {
        // Remove if laravel-audit is used: $domainTld->log('created');
    }
 
    /**
     * Handle the DomainTld "updated" event.
     */
    public function updated(DomainTld $domainTld): void
    {
        // Remove if laravel-audit is used: $domainTld->log('updated');
    }
 
    /**
     * Handle the DomainTld "deleted" event.
     */
    public function deleted(DomainTld $domainTld): void
    {
        // Remove if laravel-audit is used: $domainTld->log('deleted');
    }
 
    /**
     * Handle the DomainTld "restored" event.
     */
    public function restored(DomainTld $domainTld): void
    {
        // Remove if laravel-audit is used: $domainTld->log('restored');
    }
 
    /**
     * Handle the DomainTld "forceDeleted" event.
     */
    public function forceDeleted(DomainTld $domainTld): void
    {
        // Remove if laravel-audit is used: $domainTld->log('forceDeleted');
    }
}