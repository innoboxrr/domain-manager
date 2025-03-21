<?php

namespace Innoboxrr\DomainManager\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Innoboxrr\DomainManager\Tests\TestCase;

class DomainPaymentMethodEndpointsTest extends TestCase
{

    use RefreshDatabase,
        WithFaker;

    public function test_domain_payment_method_policies_endpoint()
    {

        $domainPaymentMethod = \Innoboxrr\DomainManager\Models\DomainPaymentMethod::factory()->create();
        
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'id' => $domainPaymentMethod->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment-method/policies', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_payment_method_policy_endpoint()
    {
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'policy' => 'index'
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment-method/policy', $payload, $headers)
            ->assertJsonStructure([
                'index'
            ])
            ->assertStatus(200);

    }

    public function test_domain_payment_method_index_auth_endpoint()
    {

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment-method/index', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_payment_method_index_guest_endpoint()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment-method/index', $payload, $headers)
            ->assertStatus(401);
            
    }
    
    public function test_domain_payment_method_show_auth_endpoint()
    {

        $domainPaymentMethod = \Innoboxrr\DomainManager\Models\DomainPaymentMethod::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_payment_method_id' => $domainPaymentMethod->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment-method/show', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_payment_method_show_guest_endpoint()
    {

        $domainPaymentMethod = \Innoboxrr\DomainManager\Models\DomainPaymentMethod::latest()->first();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_payment_method_id' => $domainPaymentMethod->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-payment-method/show', $payload, $headers)
            ->assertStatus(401);
            
    }

    public function test_domain_payment_method_create_endpoint()
    {

        $user = \Innoboxrr\DomainManager\Models\User::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = \Innoboxrr\DomainManager\Models\DomainPaymentMethod::factory()->make()->getAttributes();

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-payment-method/create', $payload, $headers)
            ->assertStatus(201);
            
    }

    public function test_domain_payment_method_update_endpoint()
    {

        $domainPaymentMethod = \Innoboxrr\DomainManager\Models\DomainPaymentMethod::factory()->create();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            ...\Innoboxrr\DomainManager\Models\DomainPaymentMethod::factory()->make()->getAttributes(),
            'domain_payment_method_id' => $domainPaymentMethod->id
        ];

        $this->json('PUT', '/api/innoboxrr/domainmanager/domain-payment-method/update', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_payment_method_delete_endpoint()
    {

        $domainPaymentMethod = \Innoboxrr\DomainManager\Models\DomainPaymentMethod::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_payment_method_id' => $domainPaymentMethod->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-payment-method/delete', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_payment_method_restore_endpoint()
    {

        $domainPaymentMethod = \Innoboxrr\DomainManager\Models\DomainPaymentMethod::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_payment_method_id' => $domainPaymentMethod->id
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-payment-method/restore', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_payment_method_force_delete_endpoint()
    {

        $domainPaymentMethod = \Innoboxrr\DomainManager\Models\DomainPaymentMethod::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_payment_method_id' => $domainPaymentMethod->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-payment-method/force-delete', $payload, $headers)
            ->assertStatus(403);
            
    }

    public function test_domain_payment_method_export_endpoint()
    {   

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            //
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-payment-method/export', $payload, $headers)
            ->assertStatus(200);
            
    }

}
