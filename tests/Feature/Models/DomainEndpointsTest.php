<?php

namespace Innoboxrr\DomainManager\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Innoboxrr\DomainManager\Tests\TestCase;

class DomainEndpointsTest extends TestCase
{

    use RefreshDatabase,
        WithFaker;

    public function test_domain_policies_endpoint()
    {

        $domain = \Innoboxrr\DomainManager\Models\Domain::factory()->create();
        
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'id' => $domain->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain/policies', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_policy_endpoint()
    {
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'policy' => 'index'
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain/policy', $payload, $headers)
            ->assertJsonStructure([
                'index'
            ])
            ->assertStatus(200);

    }

    public function test_domain_index_auth_endpoint()
    {

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain/index', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_index_guest_endpoint()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain/index', $payload, $headers)
            ->assertStatus(401);
            
    }
    
    public function test_domain_show_auth_endpoint()
    {

        $domain = \Innoboxrr\DomainManager\Models\Domain::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_id' => $domain->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain/show', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_show_guest_endpoint()
    {

        $domain = \Innoboxrr\DomainManager\Models\Domain::latest()->first();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_id' => $domain->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain/show', $payload, $headers)
            ->assertStatus(401);
            
    }

    public function test_domain_create_endpoint()
    {

        $user = \Innoboxrr\DomainManager\Models\User::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = \Innoboxrr\DomainManager\Models\Domain::factory()->make()->getAttributes();

        $this->json('POST', '/api/innoboxrr/domainmanager/domain/create', $payload, $headers)
            ->assertStatus(201);
            
    }

    public function test_domain_update_endpoint()
    {

        $domain = \Innoboxrr\DomainManager\Models\Domain::factory()->create();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            ...\Innoboxrr\DomainManager\Models\Domain::factory()->make()->getAttributes(),
            'domain_id' => $domain->id
        ];

        $this->json('PUT', '/api/innoboxrr/domainmanager/domain/update', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_delete_endpoint()
    {

        $domain = \Innoboxrr\DomainManager\Models\Domain::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_id' => $domain->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain/delete', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_restore_endpoint()
    {

        $domain = \Innoboxrr\DomainManager\Models\Domain::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_id' => $domain->id
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain/restore', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_force_delete_endpoint()
    {

        $domain = \Innoboxrr\DomainManager\Models\Domain::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_id' => $domain->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain/force-delete', $payload, $headers)
            ->assertStatus(403);
            
    }

    public function test_domain_export_endpoint()
    {   

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            //
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain/export', $payload, $headers)
            ->assertStatus(200);
            
    }

}
