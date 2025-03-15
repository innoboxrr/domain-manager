<?php

namespace Innoboxrr\DomainManager\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Innoboxrr\DomainManager\Tests\TestCase;

class DomainDnsEndpointsTest extends TestCase
{

    use RefreshDatabase,
        WithFaker;

    public function test_domain_dns_policies_endpoint()
    {

        $domainDns = \Innoboxrr\DomainManager\Models\DomainDns::factory()->create();
        
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'id' => $domainDns->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-dns/policies', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_dns_policy_endpoint()
    {
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'policy' => 'index'
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-dns/policy', $payload, $headers)
            ->assertJsonStructure([
                'index'
            ])
            ->assertStatus(200);

    }

    public function test_domain_dns_index_auth_endpoint()
    {

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-dns/index', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_dns_index_guest_endpoint()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-dns/index', $payload, $headers)
            ->assertStatus(401);
            
    }
    
    public function test_domain_dns_show_auth_endpoint()
    {

        $domainDns = \Innoboxrr\DomainManager\Models\DomainDns::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_dns_id' => $domainDns->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-dns/show', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_dns_show_guest_endpoint()
    {

        $domainDns = \Innoboxrr\DomainManager\Models\DomainDns::latest()->first();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_dns_id' => $domainDns->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-dns/show', $payload, $headers)
            ->assertStatus(401);
            
    }

    public function test_domain_dns_create_endpoint()
    {

        $user = \Innoboxrr\DomainManager\Models\User::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = \Innoboxrr\DomainManager\Models\DomainDns::factory()->make()->getAttributes();

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-dns/create', $payload, $headers)
            ->assertStatus(201);
            
    }

    public function test_domain_dns_update_endpoint()
    {

        $domainDns = \Innoboxrr\DomainManager\Models\DomainDns::factory()->create();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            ...\Innoboxrr\DomainManager\Models\DomainDns::factory()->make()->getAttributes(),
            'domain_dns_id' => $domainDns->id
        ];

        $this->json('PUT', '/api/innoboxrr/domainmanager/domain-dns/update', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_dns_delete_endpoint()
    {

        $domainDns = \Innoboxrr\DomainManager\Models\DomainDns::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_dns_id' => $domainDns->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-dns/delete', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_dns_restore_endpoint()
    {

        $domainDns = \Innoboxrr\DomainManager\Models\DomainDns::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_dns_id' => $domainDns->id
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-dns/restore', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_dns_force_delete_endpoint()
    {

        $domainDns = \Innoboxrr\DomainManager\Models\DomainDns::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_dns_id' => $domainDns->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-dns/force-delete', $payload, $headers)
            ->assertStatus(403);
            
    }

    public function test_domain_dns_export_endpoint()
    {   

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            //
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-dns/export', $payload, $headers)
            ->assertStatus(200);
            
    }

}
