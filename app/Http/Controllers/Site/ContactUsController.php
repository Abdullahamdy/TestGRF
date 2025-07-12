<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Dashboard\BaseController;
use App\Http\Requests\Site\ContactUsRequest;
use App\Models\Ticket;

class ContactUsController extends BaseController
{
    public function createTicket(ContactUsRequest $request){

     Ticket::create($request->validated());
     return $this->respondMessage('سيتم التواصل معكم قريبا شكرا لكم');

    }
}
