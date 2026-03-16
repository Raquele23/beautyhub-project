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
        // Só re-geocodifica se o endereço mudou
        if (
            $professional->wasChanged('street') ||
            $professional->wasChanged('house_number') ||
            $professional->wasChanged('city') ||
            $professional->wasChanged('state')
        ) {
            $professional->geocode();
        }
    }
}