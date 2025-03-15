<?php

namespace Innoboxrr\DomainManager\Models\Traits\Assignments;

/* Replace the word "Model" and "model" */

trait DomainProviderAssignment
{

	public function assignModel($request)
	{

        $operationResult = $this->models()->syncWithoutDetaching([
            $request->model_id => [
            	// Pivot values
            ]
        ]);

        return response()->json([
        	'model_id' => $request->model_id,
        	'domain_provider_id' => $request->domain_provider_id,
        	'operation' => $operationResult
        ]);

	}

	public function deallocateModel($request)
	{

		$operationResult = $this->models()->detach($request->model_id);

		return response()->json([
        	'model_id' => $request->model_id,
        	'domain_provider_id' => $request->domain_provider_id,
        	'operation' => $operationResult
        ]);

	}

}