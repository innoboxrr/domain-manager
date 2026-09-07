<?php

namespace Innoboxrr\DomainManager\Database\Factories;

/*
 * Docs: https://fakerphp.github.io/ 
 */

use App\Models\Workspace;
use Innoboxrr\DomainManager\Models\DomainProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class DomainProviderFactory extends Factory
{

    protected $model = DomainProvider::class;

    public function definition()
    {
        $driver = $this->faker->randomElement(['namecheap', 'route53']);

        $workspace = Workspace::factory()->create();

        if ($driver === 'namecheap') {
            $secrets = [
                'api_user' => $this->faker->userName(),
                'api_key' => $this->faker->regexify('[A-Za-z0-9]{32}'),
                'username' => $this->faker->userName(),
                'client_ip' => $this->faker->ipv4(),
            ];

            $settings = [
                'sandbox' => false,
            ];

            $payload = [
                'base_url' => 'https://api.namecheap.com/xml.response',
                'sandbox_base_url' => 'https://api.sandbox.namecheap.com/xml.response',
            ];
        } else {
            $secrets = [
                'access_key' => 'AKIA' . $this->faker->regexify('[A-Z0-9]{16}'),
                'secret_key' => $this->faker->regexify('[A-Za-z0-9]{32}'),
            ];

            $settings = [];

            $payload = [
                'region' => 'us-east-1',
                'hosted_zone_id' => null,
                'role_arn' => null,
            ];
        }

        return [
            'workspace_id' => $workspace->id,
            'name' => $this->faker->company() . ' Domains',
            'driver' => $driver,
            'secrets' => $secrets,
            'settings' => $settings,
            'payload' => $payload,
        ];
    }

}
