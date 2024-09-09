<?php

namespace App\Services\TicketValidationStrategies;

use App\Http\Requests\CreateTicketRequest;

interface ValidationStrategy
{
    public function validate(CreateTicketRequest $request): array;
}
