<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeFilter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filter {name : The name of the filter class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new filter class';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Filters/{$name}.php");

        // Check if file already exists
        if (File::exists($path)) {
            $this->error("Filter {$name} already exists!");
            return Command::FAILURE;
        }

        // Create Filters directory if it doesn't exist
        if (!File::isDirectory(app_path('Filters'))) {
            File::makeDirectory(app_path('Filters'));
        }

        // Generate filter class content
        $stub = $this->getStub();
        $content = str_replace('{{className}}', $name, $stub);

        // Write file
        File::put($path, $content);

        $this->info("Filter {$name} created successfully.");
        return Command::SUCCESS;
    }

    /**
     * Get the stub content.
     *
     * @return string
     */
    private function getStub()
    {
        return <<<PHP
        <?php

        namespace App\Filters;

        use Illuminate\Database\Eloquent\Builder;

        class {{className}} extends BaseFilters
        {
            /**
             * Registered filters to operate upon.
             *
             * @var array
             */
            protected \$filters = [
                // Add your filterable fields here
            ];

            // Define methods for filters here
        }
        PHP;
    }
}
