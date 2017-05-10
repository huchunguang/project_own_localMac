<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Sendmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send {user  : The Id of the student} { --queue= :Whether the job should be queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send emails comsole with test purpose';

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
        $username=$this->ask('what is your username','David');
        $name = $this->anticipate('What is your name?', ['Taylor', 'Dayle']);
        
        print_r($username);
        print_r($this->argument());
        print_r($this->option());
    }
}
