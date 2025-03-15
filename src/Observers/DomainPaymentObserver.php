<?php
 
namespace Innoboxrr\DomainManager\Observers;
 
use Innoboxrr\DomainManager\Models\DomainPayment;
 
class DomainPaymentObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    // public $afterCommit = true;

    /**
     * Handle the DomainPayment "created" event.
     */
    public function created(DomainPayment $domainPayment): void
    {
        // Remove if laravel-audit is used: $domainPayment->log('created');
    }
 
    /**
     * Handle the DomainPayment "updated" event.
     */
    public function updated(DomainPayment $domainPayment): void
    {
        // Remove if laravel-audit is used: $domainPayment->log('updated');
    }
 
    /**
     * Handle the DomainPayment "deleted" event.
     */
    public function deleted(DomainPayment $domainPayment): void
    {
        // Remove if laravel-audit is used: $domainPayment->log('deleted');
    }
 
    /**
     * Handle the DomainPayment "restored" event.
     */
    public function restored(DomainPayment $domainPayment): void
    {
        // Remove if laravel-audit is used: $domainPayment->log('restored');
    }
 
    /**
     * Handle the DomainPayment "forceDeleted" event.
     */
    public function forceDeleted(DomainPayment $domainPayment): void
    {
        // Remove if laravel-audit is used: $domainPayment->log('forceDeleted');
    }
}