<?php

namespace app\Services;

use App\Http\Requests\CreateTicketRequest;

class TicketValidatorService
{
    private array $ticketValidationStrategies;

    public function __construct($ticketValidationStrategies)
    {
        $this->ticketValidationStrategies = $ticketValidationStrategies;
    }

    public function validateTicket(CreateTicketRequest $request): array
    {
        foreach ($this->ticketValidationStrategies as $strategy) {
            $result = $strategy->validate($request);
            if (isset($result['error'])) {
                return $result;
            }
        }
        return [];
    }

}
