<?php

namespace Orchestra\Tenanti\Tests\Unit\Jobs;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;
use Orchestra\Tenanti\Jobs\DeleteTenant;

class DeleteTenantTest extends TestCase
{
    /**
     * @var \Illuminate\Container\Container
     */
    private $app;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        $this->app = new Container();
        $this->app['orchestra.tenanti'] = m::mock('Orchestra\Tenanti\TenantiManager');

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->app);
        Container::setInstance($this->app);
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        unset($this->app);

        m::close();
    }

    /**
     * Test Orchestra\Tenanti\Jobs\DeleteTenant::fire() method.
     *
     * @test
     */
    public function testHandleMethod()
    {
        $migrator = m::mock('Orchestra\Tenanti\Migrator\Factory');
        $model = m::mock('Illuminate\Database\Eloquent\Model');
        $tenanti = $this->app['orchestra.tenanti'];

        $data = [
            'database' => 'foo',
            'driver' => 'user',
        ];

        $stub = new DeleteTenant($model, $data);

        $model->shouldReceive('getKey')->never()->andReturn(4);

        $tenanti->shouldReceive('driver')->once()->andReturn($migrator);
        $migrator->shouldReceive('runReset')->once()->with($model, 'foo')->andReturnNull();

        App::swap($this->app);

        $this->assertNull($stub->handle());
    }
}
