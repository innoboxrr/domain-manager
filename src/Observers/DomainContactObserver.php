<?php
 
namespace Innoboxrr\DomainManager\Observers;
 
use Innoboxrr\DomainManager\Models\DomainContact;
 
class DomainContactObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    // public $afterCommit = true;

    /**
     * Handle the DomainContact "created" event.
     */
    public function created(DomainContact $domainContact): void
    {
        // Remove if laravel-audit is used: $domainContact->log('created');
    }
 
    /**
     * Handle the DomainContact "updated" event.
     */
    public function updated(DomainContact $domainContact): void
    {
        // Remove if laravel-audit is used: $domainContact->log('updated');
    }
 
    /**
     * Handle the DomainContact "deleted" event.
     */
    public function deleted(DomainContact $domainContact): void
    {
        // Remove if laravel-audit is used: $domainContact->log('deleted');
    }
 
    /**
     * Handle the DomainContact "restored" event.
     */
    public function restored(DomainContact $domainContact): void
    {
        // Remove if laravel-audit is used: $domainContact->log('restored');
    }
 
    /**
     * Handle the DomainContact "forceDeleted" event.
     */
    public function forceDeleted(DomainContact $domainContact): void
    {
        // Remove if laravel-audit is used: $domainContact->log('forceDeleted');
    }
}