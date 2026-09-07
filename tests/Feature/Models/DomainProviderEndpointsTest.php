<?php

namespace Innoboxrr\DomainManager\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Innoboxrr\DomainManager\Tests\TestCase;
use Innoboxrr\DomainManager\Models\DomainProvider;

class DomainProviderEndpointsTest extends TestCase
{

    use RefreshDatabase,
        WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        DomainProvider::factory()->create();
    }

    public function test_domain_provider_policies_endpoint()
    {

        $domainProvider = DomainProvider::factory()->create();
        
        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'id' => $domainProvider->id,
            'workspace_id' => $domainProvider->workspace_id,
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

        $provider = DomainProvider::first();

        $payload = [
            'policy' => 'index',
            'workspace_id' => $provider->workspace_id,
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

        $provider = DomainProvider::first();

        $payload = [
            'managed' => true,
            'workspace_id' => $provider->workspace_id,
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

        $provider = DomainProvider::first();

        $payload = [
            'managed' => true,
            'workspace_id' => $provider->workspace_id,
        ];

        $this->json('GET', '/api/innoboxrr/domainmanager/domain-provider/index', $payload, $headers)
            ->assertStatus(401);
            
    }
    
    public function test_domain_provider_show_auth_endpoint()
    {

        $domainProvider = DomainProvider::latest()->first();

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

        $domainProvider = DomainProvider::latest()->first();

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

        $provider = DomainProvider::factory()->make();

        $payload = [
            'workspace_id' => $provider->workspace_id,
            'name' => $provider->name,
            'driver' => $provider->driver,
            'secrets' => $provider->secrets,
            'settings' => $provider->settings,
            'payload' => $provider->payload,
        ];

        $this->json('POST', '/api/innoboxrr/domainmanager/domain-provider/create', $payload, $headers)
            ->assertStatus(201);
            
    }

    public function test_domain_provider_update_endpoint()
    {

        $domainProvider = DomainProvider::factory()->create();

        $headers = [
            'Authorization' => config('test.token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];  

        $payload = [
            'domain_provider_id' => $domainProvider->id,
            'workspace_id' => $domainProvider->workspace_id,
            'name' => 'Updated Provider',
            'driver' => 'route53',
            'secrets' => [
                'access_key' => 'AKIA' . $this->faker->regexify('[A-Z0-9]{16}'),
                'secret_key' => $this->faker->regexify('[A-Za-z0-9]{32}'),
            ],
            'settings' => [],
            'payload' => [
                'region' => 'us-east-1',
                'hosted_zone_id' => null,
                'role_arn' => null,
            ],
        ];

        $this->json('PUT', '/api/innoboxrr/domainmanager/domain-provider/update', $payload, $headers)
            ->assertStatus(200);
            
    }

    public function test_domain_provider_delete_endpoint()
    {

        $domainProvider = DomainProvider::latest()->first();

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

        $domainProvider = DomainProvider::first();

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
