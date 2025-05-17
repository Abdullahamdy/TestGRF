<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDashboardService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new Dashboard service class';

    public function handle()
    {
        $name = $this->argument('name');
        $directory = app_path('Services/Dashboard');
        $filePath = "$directory/{$name}.php";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($filePath)) {
            $this->error("Service {$name} already exists!");
            return Command::FAILURE;
        }

        $content = <<<PHP
<?php

namespace App\Services\Dashboard;

class {$name}
{
    public function __construct()
    {
        // Initialization
    }
}
PHP;

        File::put($filePath, $content);

        $this->info("Dashboard service {$name} created successfully.");

        return Command::SUCCESS;
    }
}
