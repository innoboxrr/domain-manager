<?php

namespace Innoboxrr\DomainManager\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Innoboxrr\DomainManager\Tests\TestCase;

class DomainRenewalEndpointsTest extends TestCase
{

    use RefreshDatabase,
        WithFaker;

    public function test_domain_renewal_policies_endpoint()
    {

        $domainRenewal = \Innoboxrr\DomainManager\Models\DomainRenewal::factory()->create();
        
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'id' => $domainRenewal->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-renewal/policies', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_renewal_policy_endpoint()
    {
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'policy' => 'index'
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-renewal/policy', $payload, $headers)
            ->assertJsonStructure([
                'index'
            ])
            ->assertStatus(200);

    }

    public function test_domain_renewal_index_auth_endpoint()
    {

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-renewal/index', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_renewal_index_guest_endpoint()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-renewal/index', $payload, $headers)
            ->assertStatus(401);
            
    }
    
    public function test_domain_renewal_show_auth_endpoint()
    {

        $domainRenewal = \Innoboxrr\DomainManager\Models\DomainRenewal::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_renewal_id' => $domainRenewal->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-renewal/show', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_renewal_show_guest_endpoint()
    {

        $domainRenewal = \Innoboxrr\DomainManager\Models\DomainRenewal::latest()->first();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_renewal_id' => $domainRenewal->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-renewal/show', $payload, $headers)
            ->assertStatus(401);
            
    }

    public function test_domain_renewal_create_endpoint()
    {

        $user = \Innoboxrr\DomainManager\Models\User::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = \Innoboxrr\DomainManager\Models\DomainRenewal::factory()->make()->getAttributes();

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-renewal/create', $payload, $headers)
            ->assertStatus(201);
            
    }

    public function test_domain_renewal_update_endpoint()
    {

        $domainRenewal = \Innoboxrr\DomainManager\Models\DomainRenewal::factory()->create();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            ...\Innoboxrr\DomainManager\Models\DomainRenewal::factory()->make()->getAttributes(),
            'domain_renewal_id' => $domainRenewal->id
        ];

        $this->json('PUT', '/api/innoboxrr/domainmanager/domain-renewal/update', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_renewal_delete_endpoint()
    {

        $domainRenewal = \Innoboxrr\DomainManager\Models\DomainRenewal::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_renewal_id' => $domainRenewal->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-renewal/delete', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_renewal_restore_endpoint()
    {

        $domainRenewal = \Innoboxrr\DomainManager\Models\DomainRenewal::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_renewal_id' => $domainRenewal->id
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-renewal/restore', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_renewal_force_delete_endpoint()
    {

        $domainRenewal = \Innoboxrr\DomainManager\Models\DomainRenewal::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_renewal_id' => $domainRenewal->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-renewal/force-delete', $payload, $headers)
            ->assertStatus(403);
            
    }

    public function test_domain_renewal_export_endpoint()
    {   

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            //
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-renewal/export', $payload, $headers)
            ->assertStatus(200);
            
    }

}
