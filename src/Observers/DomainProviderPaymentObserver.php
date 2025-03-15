<?php
 
namespace Innoboxrr\DomainManager\Observers;
 
use Innoboxrr\DomainManager\Models\DomainProviderPayment;
 
class DomainProviderPaymentObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    // public $afterCommit = true;

    /**
     * Handle the DomainProviderPayment "created" event.
     */
    public function created(DomainProviderPayment $domainProviderPayment): void
    {
        // Remove if laravel-audit is used: $domainProviderPayment->log('created');
    }
 
    /**
     * Handle the DomainProviderPayment "updated" event.
     */
    public function updated(DomainProviderPayment $domainProviderPayment): void
    {
        // Remove if laravel-audit is used: $domainProviderPayment->log('updated');
    }
 
    /**
     * Handle the DomainProviderPayment "deleted" event.
     */
    public function deleted(DomainProviderPayment $domainProviderPayment): void
    {
        // Remove if laravel-audit is used: $domainProviderPayment->log('deleted');
    }
 
    /**
     * Handle the DomainProviderPayment "restored" event.
     */
    public function restored(DomainProviderPayment $domainProviderPayment): void
    {
        // Remove if laravel-audit is used: $domainProviderPayment->log('restored');
    }
 
    /**
     * Handle the DomainProviderPayment "forceDeleted" event.
     */
    public function forceDeleted(DomainProviderPayment $domainProviderPayment): void
    {
        // Remove if laravel-audit is used: $domainProviderPayment->log('forceDeleted');
    }
}