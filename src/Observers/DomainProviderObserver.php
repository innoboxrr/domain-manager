<?php
 
namespace Innoboxrr\DomainManager\Observers;
 
use Innoboxrr\DomainManager\Models\DomainProvider;
 
class DomainProviderObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    // public $afterCommit = true;

    /**
     * Handle the DomainProvider "created" event.
     */
    public function created(DomainProvider $domainProvider): void
    {
        // Remove if laravel-audit is used: $domainProvider->log('created');
    }
 
    /**
     * Handle the DomainProvider "updated" event.
     */
    public function updated(DomainProvider $domainProvider): void
    {
        // Remove if laravel-audit is used: $domainProvider->log('updated');
    }
 
    /**
     * Handle the DomainProvider "deleted" event.
     */
    public function deleted(DomainProvider $domainProvider): void
    {
        // Remove if laravel-audit is used: $domainProvider->log('deleted');
    }
 
    /**
     * Handle the DomainProvider "restored" event.
     */
    public function restored(DomainProvider $domainProvider): void
    {
        // Remove if laravel-audit is used: $domainProvider->log('restored');
    }
 
    /**
     * Handle the DomainProvider "forceDeleted" event.
     */
    public function forceDeleted(DomainProvider $domainProvider): void
    {
        // Remove if laravel-audit is used: $domainProvider->log('forceDeleted');
    }
}