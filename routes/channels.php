<?php

use Illuminate\Support\Facades\Broadcast;

// Canal public pour tous les employés
Broadcast::channel('employes', function () {
    return true;
});
