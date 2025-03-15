<?php

namespace Innoboxrr\DomainManager\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Innoboxrr\DomainManager\Tests\TestCase;

class DomainPaymentEndpointsTest extends TestCase
{

    use RefreshDatabase,
        WithFaker;

    public function test_domain_payment_policies_endpoint()
    {

        $domainPayment = \Innoboxrr\DomainManager\Models\DomainPayment::factory()->create();
        
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'id' => $domainPayment->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment/policies', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_payment_policy_endpoint()
    {
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'policy' => 'index'
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment/policy', $payload, $headers)
            ->assertJsonStructure([
                'index'
            ])
            ->assertStatus(200);

    }

    public function test_domain_payment_index_auth_endpoint()
    {

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment/index', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_payment_index_guest_endpoint()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment/index', $payload, $headers)
            ->assertStatus(401);
            
    }
    
    public function test_domain_payment_show_auth_endpoint()
    {

        $domainPayment = \Innoboxrr\DomainManager\Models\DomainPayment::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_payment_id' => $domainPayment->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment/show', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_payment_show_guest_endpoint()
    {

        $domainPayment = \Innoboxrr\DomainManager\Models\DomainPayment::latest()->first();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_payment_id' => $domainPayment->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment/show', $payload, $headers)
            ->assertStatus(401);
            
    }

    public function test_domain_payment_create_endpoint()
    {

        $user = \Innoboxrr\DomainManager\Models\User::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = \Innoboxrr\DomainManager\Models\DomainPayment::factory()->make()->getAttributes();

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-payment/create', $payload, $headers)
            ->assertStatus(201);
            
    }

    public function test_domain_payment_update_endpoint()
    {

        $domainPayment = \Innoboxrr\DomainManager\Models\DomainPayment::factory()->create();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            ...\Innoboxrr\DomainManager\Models\DomainPayment::factory()->make()->getAttributes(),
            'domain_payment_id' => $domainPayment->id
        ];

        $this->json('PUT', '/api/innoboxrr/domainmanager/domain-payment/update', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_payment_delete_endpoint()
    {

        $domainPayment = \Innoboxrr\DomainManager\Models\DomainPayment::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_payment_id' => $domainPayment->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-payment/delete', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_payment_restore_endpoint()
    {

        $domainPayment = \Innoboxrr\DomainManager\Models\DomainPayment::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_payment_id' => $domainPayment->id
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-payment/restore', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_payment_force_delete_endpoint()
    {

        $domainPayment = \Innoboxrr\DomainManager\Models\DomainPayment::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_payment_id' => $domainPayment->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-payment/force-delete', $payload, $headers)
            ->assertStatus(403);
            
    }

    public function test_domain_payment_export_endpoint()
    {   

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            //
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-payment/export', $payload, $headers)
            ->assertStatus(200);
            
    }

}
