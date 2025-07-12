<?php

namespace App\Services\Dashboard;

use App\Http\Resources\Dashboard\TicketResource;
use App\Mail\TicketReplayEmail;
use App\Models\Ticket;
use App\Services\Sender\WhatsAppService;
use Illuminate\Support\Facades\Mail;


class TicketService
{
    protected $modelName = Ticket::class;


    protected $whatsAppService;
    public function __construct(WhatsAppService $whatsAppService)
    {

        $this->whatsAppService = $whatsAppService;
    }
    public function index()
    {

        $tickets = Ticket::orderBy('id', 'desc')
            ->filter()
            ->paginate(request()->has('per_page') ? request()->per_page : 10);
        return TicketResource::collection($tickets);
    }

    public function show(string $id)
    {
        $model = Ticket::find($id);

        if (!$model) return 'not_found';

        // if (!auth()->user()->can('view', $model)) {
        //     throw new UnauthorizedAccessException();
        // }

        return  new TicketResource($model);
    }


    public function messageReplay(array $data, string $id)
    {
        $model = Ticket::find($id);

        if (!$model) return 'not_found';

        // if (!auth()->user()->can('replay ticket')) {
        //     throw new UnauthorizedAccessException();
        // }

        if (isset($data['method']) && $data['method'] == 'email') {
            Mail::to($data['email'])->send(new TicketReplayEmail(strip_tags($data['message_replay'])));
        } else if (isset($data['method']) && isset($data['phone']) && $data['method'] == 'phone') {
            return $this->whatsAppService->send(strip_tags($data['message_replay']), $data['phone']);
        }
        $model->message_replay = $data['message_replay'];
        $model->status = 1;
        $model->save();

        return  'success';
    }

    public function destroy(string $id)
    {
        $model = Ticket::find($id);

        if (!$model) return 'not_found';

        // if (!auth()->user()->can('delete', $model)) {
        //     throw new UnauthorizedAccessException();
        // }

        $model->delete();
        return  'success';
    }
}
