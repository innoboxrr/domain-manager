<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProvider;

use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderResource;
use Innoboxrr\DomainManager\Http\Events\DomainProvider\Events\CreateEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{

    public function authorize()
    {

        return $this->user()->can('create', DomainProvider::class);

    }

    public function rules()
    {
        return [
            'workspace_id' => ['required', 'integer', 'exists:workspaces,id'],
            'name' => ['required', 'string', 'max:191'],
            'driver' => ['required', 'string', Rule::in(['namecheap', 'route53'])],
            'secrets' => ['required', 'array'],
            'settings' => ['nullable', 'array'],
            'payload' => ['nullable', 'array'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $driver = $this->input('driver');
            $secrets = $this->input('secrets', []);

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

        $domainProvider = (new DomainProvider)->createModel($this);

        $response = new DomainProviderResource($domainProvider);

        event(new CreateEvent($domainProvider, $this->all(), $response));

        return $response;

    }
    
}
