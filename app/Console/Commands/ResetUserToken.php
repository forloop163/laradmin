<?php

namespace App\Console\Commands;

use App\Business\System\User as UserBusiness;
use App\Models\User as UserModel;
use Illuminate\Console\Command;

class ResetUserToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laradmin:reset-user-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重置用户访问Token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userBusiness = new UserBusiness(new UserModel());
        $userBusiness->resetRememberToken();
    }
}
