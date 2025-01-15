<?php

use App\Core\Router;
use App\Controllers\UpdateStatusController;

App\Core\Router::route('update-status', [UpdateStatusController::class, 'updateStatus']);



?>