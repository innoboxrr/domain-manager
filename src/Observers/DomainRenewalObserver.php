<?php
 
namespace Innoboxrr\DomainManager\Observers;
 
use Innoboxrr\DomainManager\Models\DomainRenewal;
 
class DomainRenewalObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    // public $afterCommit = true;

    /**
     * Handle the DomainRenewal "created" event.
     */
    public function created(DomainRenewal $domainRenewal): void
    {
        // Remove if laravel-audit is used: $domainRenewal->log('created');
    }
 
    /**
     * Handle the DomainRenewal "updated" event.
     */
    public function updated(DomainRenewal $domainRenewal): void
    {
        // Remove if laravel-audit is used: $domainRenewal->log('updated');
    }
 
    /**
     * Handle the DomainRenewal "deleted" event.
     */
    public function deleted(DomainRenewal $domainRenewal): void
    {
        // Remove if laravel-audit is used: $domainRenewal->log('deleted');
    }
 
    /**
     * Handle the DomainRenewal "restored" event.
     */
    public function restored(DomainRenewal $domainRenewal): void
    {
        // Remove if laravel-audit is used: $domainRenewal->log('restored');
    }
 
    /**
     * Handle the DomainRenewal "forceDeleted" event.
     */
    public function forceDeleted(DomainRenewal $domainRenewal): void
    {
        // Remove if laravel-audit is used: $domainRenewal->log('forceDeleted');
    }
}