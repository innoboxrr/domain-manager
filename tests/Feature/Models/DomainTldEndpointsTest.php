<?php

namespace Innoboxrr\DomainManager\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Innoboxrr\DomainManager\Tests\TestCase;

class DomainTldEndpointsTest extends TestCase
{

    use RefreshDatabase,
        WithFaker;

    public function test_domain_tld_policies_endpoint()
    {

        $domainTld = \Innoboxrr\DomainManager\Models\DomainTld::factory()->create();
        
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'id' => $domainTld->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-tld/policies', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_tld_policy_endpoint()
    {
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'policy' => 'index'
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-tld/policy', $payload, $headers)
            ->assertJsonStructure([
                'index'
            ])
            ->assertStatus(200);

    }

    public function test_domain_tld_index_auth_endpoint()
    {

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-tld/index', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_tld_index_guest_endpoint()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-tld/index', $payload, $headers)
            ->assertStatus(401);
            
    }
    
    public function test_domain_tld_show_auth_endpoint()
    {

        $domainTld = \Innoboxrr\DomainManager\Models\DomainTld::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_tld_id' => $domainTld->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-tld/show', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_tld_show_guest_endpoint()
    {

        $domainTld = \Innoboxrr\DomainManager\Models\DomainTld::latest()->first();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_tld_id' => $domainTld->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-tld/show', $payload, $headers)
            ->assertStatus(401);
            
    }

    public function test_domain_tld_create_endpoint()
    {

        $user = \Innoboxrr\DomainManager\Models\User::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = \Innoboxrr\DomainManager\Models\DomainTld::factory()->make()->getAttributes();

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-tld/create', $payload, $headers)
            ->assertStatus(201);
            
    }

    public function test_domain_tld_update_endpoint()
    {

        $domainTld = \Innoboxrr\DomainManager\Models\DomainTld::factory()->create();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            ...\Innoboxrr\DomainManager\Models\DomainTld::factory()->make()->getAttributes(),
            'domain_tld_id' => $domainTld->id
        ];

        $this->json('PUT', '/api/innoboxrr/domainmanager/domain-tld/update', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_tld_delete_endpoint()
    {

        $domainTld = \Innoboxrr\DomainManager\Models\DomainTld::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_tld_id' => $domainTld->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-tld/delete', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_tld_restore_endpoint()
    {

        $domainTld = \Innoboxrr\DomainManager\Models\DomainTld::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_tld_id' => $domainTld->id
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-tld/restore', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_tld_force_delete_endpoint()
    {

        $domainTld = \Innoboxrr\DomainManager\Models\DomainTld::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_tld_id' => $domainTld->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-tld/force-delete', $payload, $headers)
            ->assertStatus(403);
            
    }

    public function test_domain_tld_export_endpoint()
    {   

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            //
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-tld/export', $payload, $headers)
            ->assertStatus(200);
            
    }

}
