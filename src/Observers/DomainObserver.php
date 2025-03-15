<?php
 
namespace Innoboxrr\DomainManager\Observers;
 
use Innoboxrr\DomainManager\Models\Domain;
 
class DomainObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    // public $afterCommit = true;

    /**
     * Handle the Domain "created" event.
     */
    public function created(Domain $domain): void
    {
        // Remove if laravel-audit is used: $domain->log('created');
    }
 
    /**
     * Handle the Domain "updated" event.
     */
    public function updated(Domain $domain): void
    {
        // Remove if laravel-audit is used: $domain->log('updated');
    }
 
    /**
     * Handle the Domain "deleted" event.
     */
    public function deleted(Domain $domain): void
    {
        // Remove if laravel-audit is used: $domain->log('deleted');
    }
 
    /**
     * Handle the Domain "restored" event.
     */
    public function restored(Domain $domain): void
    {
        // Remove if laravel-audit is used: $domain->log('restored');
    }
 
    /**
     * Handle the Domain "forceDeleted" event.
     */
    public function forceDeleted(Domain $domain): void
    {
        // Remove if laravel-audit is used: $domain->log('forceDeleted');
    }
}