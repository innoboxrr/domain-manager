<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainContact;

use Innoboxrr\DomainManager\Models\DomainContact;
use Innoboxrr\DomainManager\Http\Events\DomainContact\Events\ExportEvent;
use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        
        $this->merge([
            'paginate' => 0,
            'managed' => true,
            'except_view_any' => true,
        ]);

    }

    public function authorize()
    {
        return $this->user()->can('export', DomainContact::class);
    }

    public function rules()
    {
        return [
            //
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }

    public function attributes()
    {
        return [
            //
        ];
    }

    protected function passedValidation()
    {
        //
    }

    public function handle()
    {

        event(new ExportEvent($this->all(), $this->user()));

        return response()->json(['status' => true]);

    }
    
}
