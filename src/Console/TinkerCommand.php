<?php

namespace Orchestra\Tenanti\Console;

use Symfony\Component\Console\Input\InputArgument;

class TinkerCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tenanti:tinker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run tinker using tenant connection';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $arguments = $this->getArgumentsWithDriver('id');

        \tap($this->tenantDriver($arguments['driver']), static function ($tenanti) use ($arguments) {
            $tenanti->asDefaultConnection(
                $tenanti->model()->findOrFail($arguments['id']), 'tinker'
            );
        });

        $this->call('tinker');

        return 0;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['driver', InputArgument::OPTIONAL, 'Tenant driver name.'],
            ['id', InputArgument::OPTIONAL, 'The entity ID.'],
        ];
    }
}
