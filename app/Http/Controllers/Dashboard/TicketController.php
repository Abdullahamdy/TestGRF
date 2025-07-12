<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\MessageReplayRequest;
use App\Services\Dashboard\TicketService;

class TicketController extends BaseController
{
    protected $ticketService;
    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->ticketService->index();
        return $this->respondWithPagination($response, '', 200);
    }

    public function show(string $id)
    {
        $response = $this->ticketService->show($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response);
    }
    public function messageReplay(MessageReplayRequest $request, $id)
    {
        $response = $this->ticketService->messageReplay($request->all(), $id);

        return $response == 'error'
            ? $this->respondError(__('general.something_wrong'))
            : ($response == 'not_found'
                ? $this->respondMessage(__('general.not_found'), 404)
                : $this->respondMessage('تم ارسال الرد بنجاح'));
    }

    public function destroy(string $id)
    {
        $response = $this->ticketService->destroy($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response, __('general.deleted'));
    }
}
