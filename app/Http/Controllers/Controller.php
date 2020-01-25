<?php

namespace App\Http\Controllers;

use App\Foundation\Bus\CommandBus;
use App\Foundation\Bus\QueryExecutor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var CommandBus
     */
    protected $command;

    /**
     * @var QueryExecutor
     */
    protected $query;

    /**
     * @param CommandBus $commandBus
     * @param QueryExecutor $executor
     */
    public function __construct(CommandBus $commandBus, QueryExecutor $executor)
    {
        $this->command = $commandBus;
        $this->query = $executor;
    }
}
