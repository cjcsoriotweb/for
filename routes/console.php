<?php

use App\Console\Commands\RespondPendingAiMessages;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(RespondPendingAiMessages::class);
})->purpose('Display an inspiring quote');
