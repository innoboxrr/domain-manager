<?php

namespace Innoboxrr\DomainManager\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Innoboxrr\DomainManager\Tests\TestCase;

class DomainContactEndpointsTest extends TestCase
{

    use RefreshDatabase,
        WithFaker;

    public function test_domain_contact_policies_endpoint()
    {

        $domainContact = \Innoboxrr\DomainManager\Models\DomainContact::factory()->create();
        
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'id' => $domainContact->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-contact/policies', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_contact_policy_endpoint()
    {
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'policy' => 'index'
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-contact/policy', $payload, $headers)
            ->assertJsonStructure([
                'index'
            ])
            ->assertStatus(200);

    }

    public function test_domain_contact_index_auth_endpoint()
    {

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-contact/index', $payload, $headers)
            ->assertStatus(200);

    }

    public function test_domain_contact_index_guest_endpoint()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'managed' => true
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-contact/index', $payload, $headers)
            ->assertStatus(401);
            
    }
    
    public function test_domain_contact_show_auth_endpoint()
    {

        $domainContact = \Innoboxrr\DomainManager\Models\DomainContact::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_contact_id' => $domainContact->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-contact/show', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_contact_show_guest_endpoint()
    {

        $domainContact = \Innoboxrr\DomainManager\Models\DomainContact::latest()->first();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_contact_id' => $domainContact->id
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-contact/show', $payload, $headers)
            ->assertStatus(401);
            
    }

    public function test_domain_contact_create_endpoint()
    {

        $user = \Innoboxrr\DomainManager\Models\User::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = \Innoboxrr\DomainManager\Models\DomainContact::factory()->make()->getAttributes();

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-contact/create', $payload, $headers)
            ->assertStatus(201);
            
    }

    public function test_domain_contact_update_endpoint()
    {

        $domainContact = \Innoboxrr\DomainManager\Models\DomainContact::factory()->create();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            ...\Innoboxrr\DomainManager\Models\DomainContact::factory()->make()->getAttributes(),
            'domain_contact_id' => $domainContact->id
        ];

        $this->json('PUT', '/api/innoboxrr/domainmanager/domain-contact/update', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_contact_delete_endpoint()
    {

        $domainContact = \Innoboxrr\DomainManager\Models\DomainContact::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_contact_id' => $domainContact->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-contact/delete', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_contact_restore_endpoint()
    {

        $domainContact = \Innoboxrr\DomainManager\Models\DomainContact::first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_contact_id' => $domainContact->id
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-contact/restore', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_contact_force_delete_endpoint()
    {

        $domainContact = \Innoboxrr\DomainManager\Models\DomainContact::latest()->first();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_contact_id' => $domainContact->id
        ];

        $this->json('DELETE', '/api/innoboxrr/domainmanager/domain-contact/force-delete', $payload, $headers)
            ->assertStatus(403);
            
    }

    public function test_domain_contact_export_endpoint()
    {   

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            //
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-contact/export', $payload, $headers)
            ->assertStatus(200);
            
    }

}
