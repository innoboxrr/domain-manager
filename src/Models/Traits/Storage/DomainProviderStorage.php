<?php

namespace Innoboxrr\DomainManager\Models\Traits\Storage;

trait DomainProviderStorage
{
    public function createModel($request)
    {
        $domainProvider = $this->create($this->persistableData($request, true));

        return $domainProvider;
    }

    public function updateModel($request)
    {
        $this->update($this->persistableData($request, false));

        return $this;
    }

    public function deleteModel()
    {
        $this->delete();
    }

    public function restoreModel()
    {
        $this->restore();
    }

    public function forceDeleteModel()
    {
        abort(403, 'Force delete is not allowed');

    }

    protected function persistableData($request, bool $creating = false): array
    {
        $fields = $creating ? $this->creatable : $this->updatable;

        $data = [];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        foreach (['secrets', 'settings', 'payload'] as $jsonField) {
            if (!array_key_exists($jsonField, $data)) {
                if ($creating) {
                    $data[$jsonField] = [];
                }
            } elseif (!is_array($data[$jsonField])) {
                $data[$jsonField] = (array) $data[$jsonField];
            }
        }

        return $data;
    }
}
