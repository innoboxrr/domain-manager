<?php

namespace Innoboxrr\DomainManager\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Innoboxrr\DomainManager\Tests\TestCase;

class DomainProviderEndpointsTest extends TestCase
{

    use RefreshDatabase,
        WithFaker;

    public function test_domain_provider_policies_endpoint()
    {

        $domainProvider = \Innoboxrr\DomainManager\Models\DomainProvider::factory()->create();
        
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'id' => $domainProvider->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider/policies', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_provider_policy_endpoint()
    {
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'policy' => 'index'
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider/policy', $payload, $headers)
            ->assertJsonStructure([
                'index'
            ])
            ->assertStatus(200);

    }

    public function test_domain_provider_index_auth_endpoint()
    {

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider/index', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_provider_index_guest_endpoint()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider/index', $payload, $headers)
            ->assertStatus(401);
            
    }
    
    public function test_domain_provider_show_auth_endpoint()
    {

        $domainProvider = \Innoboxrr\DomainManager\Models\DomainProvider::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_provider_id' => $domainProvider->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider/show', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_provider_show_guest_endpoint()
    {

        $domainProvider = \Innoboxrr\DomainManager\Models\DomainProvider::latest()->first();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_provider_id' => $domainProvider->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider/show', $payload, $headers)
            ->assertStatus(401);
            
    }

    public function test_domain_provider_create_endpoint()
    {

        $user = \Innoboxrr\DomainManager\Models\User::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = \Innoboxrr\DomainManager\Models\DomainProvider::factory()->make()->getAttributes();

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-provider/create', $payload, $headers)
            ->assertStatus(201);
            
    }

    public function test_domain_provider_update_endpoint()
    {

        $domainProvider = \Innoboxrr\DomainManager\Models\DomainProvider::factory()->create();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            ...\Innoboxrr\DomainManager\Models\DomainProvider::factory()->make()->getAttributes(),
            'domain_provider_id' => $domainProvider->id
        ];

        $this->json('PUT', '/api/innoboxrr/domainmanager/domain-provider/update', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_provider_delete_endpoint()
    {

        $domainProvider = \Innoboxrr\DomainManager\Models\DomainProvider::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_provider_id' => $domainProvider->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-provider/delete', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_provider_restore_endpoint()
    {

        $domainProvider = \Innoboxrr\DomainManager\Models\DomainProvider::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_provider_id' => $domainProvider->id
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-provider/restore', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_provider_force_delete_endpoint()
    {

        $domainProvider = \Innoboxrr\DomainManager\Models\DomainProvider::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_provider_id' => $domainProvider->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-provider/force-delete', $payload, $headers)
            ->assertStatus(403);
            
    }

    public function test_domain_provider_export_endpoint()
    {   

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            //
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-provider/export', $payload, $headers)
            ->assertStatus(200);
            
    }

}
