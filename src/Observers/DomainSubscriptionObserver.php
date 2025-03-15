<?php
 
namespace Innoboxrr\DomainManager\Observers;
 
use Innoboxrr\DomainManager\Models\DomainSubscription;
 
class DomainSubscriptionObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    // public $afterCommit = true;

    /**
     * Handle the DomainSubscription "created" event.
     */
    public function created(DomainSubscription $domainSubscription): void
    {
        // Remove if laravel-audit is used: $domainSubscription->log('created');
    }
 
    /**
     * Handle the DomainSubscription "updated" event.
     */
    public function updated(DomainSubscription $domainSubscription): void
    {
        // Remove if laravel-audit is used: $domainSubscription->log('updated');
    }
 
    /**
     * Handle the DomainSubscription "deleted" event.
     */
    public function deleted(DomainSubscription $domainSubscription): void
    {
        // Remove if laravel-audit is used: $domainSubscription->log('deleted');
    }
 
    /**
     * Handle the DomainSubscription "restored" event.
     */
    public function restored(DomainSubscription $domainSubscription): void
    {
        // Remove if laravel-audit is used: $domainSubscription->log('restored');
    }
 
    /**
     * Handle the DomainSubscription "forceDeleted" event.
     */
    public function forceDeleted(DomainSubscription $domainSubscription): void
    {
        // Remove if laravel-audit is used: $domainSubscription->log('forceDeleted');
    }
}