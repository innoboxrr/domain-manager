<?php
 
namespace Innoboxrr\DomainManager\Observers;
 
use Innoboxrr\DomainManager\Models\DomainPaymentMethod;
 
class DomainPaymentMethodObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    // public $afterCommit = true;

    /**
     * Handle the DomainPaymentMethod "created" event.
     */
    public function created(DomainPaymentMethod $domainPaymentMethod): void
    {
        // Remove if laravel-audit is used: $domainPaymentMethod->log('created');
    }
 
    /**
     * Handle the DomainPaymentMethod "updated" event.
     */
    public function updated(DomainPaymentMethod $domainPaymentMethod): void
    {
        // Remove if laravel-audit is used: $domainPaymentMethod->log('updated');
    }
 
    /**
     * Handle the DomainPaymentMethod "deleted" event.
     */
    public function deleted(DomainPaymentMethod $domainPaymentMethod): void
    {
        // Remove if laravel-audit is used: $domainPaymentMethod->log('deleted');
    }
 
    /**
     * Handle the DomainPaymentMethod "restored" event.
     */
    public function restored(DomainPaymentMethod $domainPaymentMethod): void
    {
        // Remove if laravel-audit is used: $domainPaymentMethod->log('restored');
    }
 
    /**
     * Handle the DomainPaymentMethod "forceDeleted" event.
     */
    public function forceDeleted(DomainPaymentMethod $domainPaymentMethod): void
    {
        // Remove if laravel-audit is used: $domainPaymentMethod->log('forceDeleted');
    }
}