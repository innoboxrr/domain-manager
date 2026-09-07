<?php

namespace Innoboxrr\DomainManager\Http\Controllers;

use Innoboxrr\DomainManager\Http\Requests\Domain\{
    PoliciesRequest,
    PolicyRequest,
    IndexRequest,
    ShowRequest,
    CreateRequest,
    UpdateRequest,
    DeleteRequest,
    RestoreRequest,
    ForceDeleteRequest,
    ExportRequest,
    RegistrarSyncOperationRequest,
    RegistrarUpsertRecordsRequest,
    RegistrarPurchaseRequest,
    RegistrarRenewRequest,
    RegistrarTransferRequest,
    RegistrarReleaseRequest,
    RegistrarSyncDnsRequest
};

class DomainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function policies(PoliciesRequest $request)
    {
        return $request->handle($this);   
    }

    public function policy(PolicyRequest $request)
    {
        return $request->handle();
    }

    public function index(IndexRequest $request)
    {
        return $request->handle();   
    }

    public function show(ShowRequest $request)
    {
        return $request->handle();   
    }

    public function create(CreateRequest $request)
    {
        return $request->handle();   
    }

    public function update(UpdateRequest $request)
    {
        return $request->handle();   
    }

    public function delete(DeleteRequest $request)
    {
        return $request->handle();   
    }

    public function restore(RestoreRequest $request)
    {
        return $request->handle();   
    }

    public function forceDelete(ForceDeleteRequest $request)
    {
        return $request->handle();   
    }

    public function export(ExportRequest $request)
    {
        return $request->handle();   
    }

    public function registrarSyncOperation(RegistrarSyncOperationRequest $request)
    {
        return $request->handle();   
    }

    public function registrarUpsertRecords(RegistrarUpsertRecordsRequest $request)
    {
        return $request->handle();   
    }

    public function registrarPurchase(RegistrarPurchaseRequest $request)
    {
        return $request->handle();
    }

    public function registrarRenew(RegistrarRenewRequest $request)
    {
        return $request->handle();
    }

    public function registrarTransfer(RegistrarTransferRequest $request)
    {
        return $request->handle();
    }

    public function registrarRelease(RegistrarReleaseRequest $request)
    {
        return $request->handle();
    }

    public function registrarSyncDns(RegistrarSyncDnsRequest $request)
    {
        return $request->handle();
    }
}
