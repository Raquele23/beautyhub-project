<?php

namespace App\Observers;

use App\Models\Professional;

class ProfessionalObserver
{
    public function created(Professional $professional): void
    {
        $professional->geocode();
    }

    public function updated(Professional $professional): void
    {
        $addressChanged =
            $professional->wasChanged('street') ||
            $professional->wasChanged('house_number') ||
            $professional->wasChanged('city') ||
            $professional->wasChanged('state');

        $missingCoordinates = !$professional->latitude || !$professional->longitude;

        // Re-geocodifica quando o endereço mudar ou quando ainda estiver sem coordenadas.
        if ($addressChanged || $missingCoordinates) {
            $professional->geocode();
        }
    }
}