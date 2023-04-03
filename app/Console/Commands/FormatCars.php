<?php

namespace App\Console\Commands;

use App\Models\Car;
use Illuminate\Console\Command;

class FormatCars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'format:cars';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '차량데이터 정리';

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
     * @return int
     */
    public function handle()
    {
        $cars = Car::latest()->where("check", 0)->cursor();

        foreach($cars as $car){
            $car->format();
        }
    }
}
