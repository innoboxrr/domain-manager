<?php

namespace Innoboxrr\DomainManager\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Innoboxrr\DomainManager\Tests\TestCase;

class DomainSubscriptionEndpointsTest extends TestCase
{

    use RefreshDatabase,
        WithFaker;

    public function test_domain_subscription_policies_endpoint()
    {

        $domainSubscription = \Innoboxrr\DomainManager\Models\DomainSubscription::factory()->create();
        
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'id' => $domainSubscription->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-subscription/policies', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_subscription_policy_endpoint()
    {
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'policy' => 'index'
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-subscription/policy', $payload, $headers)
            ->assertJsonStructure([
                'index'
            ])
            ->assertStatus(200);

    }

    public function test_domain_subscription_index_auth_endpoint()
    {

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-subscription/index', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_subscription_index_guest_endpoint()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-subscription/index', $payload, $headers)
            ->assertStatus(401);
            
    }
    
    public function test_domain_subscription_show_auth_endpoint()
    {

        $domainSubscription = \Innoboxrr\DomainManager\Models\DomainSubscription::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_subscription_id' => $domainSubscription->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-subscription/show', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_subscription_show_guest_endpoint()
    {

        $domainSubscription = \Innoboxrr\DomainManager\Models\DomainSubscription::latest()->first();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_subscription_id' => $domainSubscription->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-subscription/show', $payload, $headers)
            ->assertStatus(401);
            
    }

    public function test_domain_subscription_create_endpoint()
    {

        $user = \Innoboxrr\DomainManager\Models\User::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = \Innoboxrr\DomainManager\Models\DomainSubscription::factory()->make()->getAttributes();

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-subscription/create', $payload, $headers)
            ->assertStatus(201);
            
    }

    public function test_domain_subscription_update_endpoint()
    {

        $domainSubscription = \Innoboxrr\DomainManager\Models\DomainSubscription::factory()->create();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            ...\Innoboxrr\DomainManager\Models\DomainSubscription::factory()->make()->getAttributes(),
            'domain_subscription_id' => $domainSubscription->id
        ];

        $this->json('PUT', '/api/innoboxrr/domainmanager/domain-subscription/update', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_subscription_delete_endpoint()
    {

        $domainSubscription = \Innoboxrr\DomainManager\Models\DomainSubscription::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_subscription_id' => $domainSubscription->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-subscription/delete', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_subscription_restore_endpoint()
    {

        $domainSubscription = \Innoboxrr\DomainManager\Models\DomainSubscription::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_subscription_id' => $domainSubscription->id
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-subscription/restore', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_subscription_force_delete_endpoint()
    {

        $domainSubscription = \Innoboxrr\DomainManager\Models\DomainSubscription::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_subscription_id' => $domainSubscription->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-subscription/force-delete', $payload, $headers)
            ->assertStatus(403);
            
    }

    public function test_domain_subscription_export_endpoint()
    {   

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            //
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-subscription/export', $payload, $headers)
            ->assertStatus(200);
            
    }

}
