<?php

namespace Innoboxrr\DomainManager\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Innoboxrr\DomainManager\Tests\TestCase;

class DomainProviderPaymentEndpointsTest extends TestCase
{

    use RefreshDatabase,
        WithFaker;

    public function test_domain_provider_payment_policies_endpoint()
    {

        $domainProviderPayment = \Innoboxrr\DomainManager\Models\DomainProviderPayment::factory()->create();
        
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'id' => $domainProviderPayment->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider-payment/policies', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_provider_payment_policy_endpoint()
    {
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'policy' => 'index'
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider-payment/policy', $payload, $headers)
            ->assertJsonStructure([
                'index'
            ])
            ->assertStatus(200);

    }

    public function test_domain_provider_payment_index_auth_endpoint()
    {

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider-payment/index', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_provider_payment_index_guest_endpoint()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider-payment/index', $payload, $headers)
            ->assertStatus(401);
            
    }
    
    public function test_domain_provider_payment_show_auth_endpoint()
    {

        $domainProviderPayment = \Innoboxrr\DomainManager\Models\DomainProviderPayment::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_provider_payment_id' => $domainProviderPayment->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider-payment/show', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_provider_payment_show_guest_endpoint()
    {

        $domainProviderPayment = \Innoboxrr\DomainManager\Models\DomainProviderPayment::latest()->first();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_provider_payment_id' => $domainProviderPayment->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider-payment/show', $payload, $headers)
            ->assertStatus(401);
            
    }

    public function test_domain_provider_payment_create_endpoint()
    {

        $user = \Innoboxrr\DomainManager\Models\User::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = \Innoboxrr\DomainManager\Models\DomainProviderPayment::factory()->make()->getAttributes();

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-provider-payment/create', $payload, $headers)
            ->assertStatus(201);
            
    }

    public function test_domain_provider_payment_update_endpoint()
    {

        $domainProviderPayment = \Innoboxrr\DomainManager\Models\DomainProviderPayment::factory()->create();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            ...\Innoboxrr\DomainManager\Models\DomainProviderPayment::factory()->make()->getAttributes(),
            'domain_provider_payment_id' => $domainProviderPayment->id
        ];

        $this->json('PUT', '/api/innoboxrr/domainmanager/domain-provider-payment/update', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_provider_payment_delete_endpoint()
    {

        $domainProviderPayment = \Innoboxrr\DomainManager\Models\DomainProviderPayment::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_provider_payment_id' => $domainProviderPayment->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-provider-payment/delete', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_provider_payment_restore_endpoint()
    {

        $domainProviderPayment = \Innoboxrr\DomainManager\Models\DomainProviderPayment::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_provider_payment_id' => $domainProviderPayment->id
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-provider-payment/restore', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_provider_payment_force_delete_endpoint()
    {

        $domainProviderPayment = \Innoboxrr\DomainManager\Models\DomainProviderPayment::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_provider_payment_id' => $domainProviderPayment->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-provider-payment/force-delete', $payload, $headers)
            ->assertStatus(403);
            
    }

    public function test_domain_provider_payment_export_endpoint()
    {   

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            //
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-provider-payment/export', $payload, $headers)
            ->assertStatus(200);
            
    }

}
