<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProvider;

use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderResource;
use Innoboxrr\DomainManager\Http\Events\DomainProvider\Events\UpdateEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{

    public function authorize()
    {

        $domainProvider = DomainProvider::findOrFail($this->domain_provider_id);

        return $this->user()->can('update', $domainProvider);

    }

    public function rules()
    {
        return [
            'domain_provider_id' => ['required', 'integer', 'exists:domain_providers,id'],
            'workspace_id' => ['sometimes', 'integer', 'exists:workspaces,id'],
            'name' => ['sometimes', 'required', 'string', 'max:191'],
            'driver' => ['sometimes', 'string', Rule::in(['namecheap', 'route53'])],
            'secrets' => ['nullable', 'array'],
            'settings' => ['nullable', 'array'],
            'payload' => ['nullable', 'array'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $provider = DomainProvider::find($this->domain_provider_id);

            if (! $provider) {
                return;
            }

            $driver = $this->input('driver', $provider->driver ?? '');
            $secrets = $this->has('secrets') ? $this->input('secrets', []) : ($provider->secrets ?? []);

            if ($driver === 'namecheap') {
                foreach (['api_user', 'api_key', 'username', 'client_ip'] as $key) {
                    if (empty($secrets[$key])) {
                        $validator->errors()->add("secrets.{$key}", __('validation.required'));
                    }
                }
            } elseif ($driver === 'route53') {
                foreach (['access_key', 'secret_key'] as $key) {
                    if (empty($secrets[$key])) {
                        $validator->errors()->add("secrets.{$key}", __('validation.required'));
                    }
                }
            }
        });
    }

    public function handle()
    {

        $domainProvider = DomainProvider::findOrFail($this->domain_provider_id);

        $domainProvider = $domainProvider->updateModel($this);

        $response = new DomainProviderResource($domainProvider);

        event(new UpdateEvent($domainProvider, $this->all(), $response));

        return $response;

    }

}
