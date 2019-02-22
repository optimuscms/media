<?php

namespace Optimus\Media\Tests;

use Optimus\Users\Models\AdminUser;
use Optimus\Users\UserServiceProvider;
use Optimus\Media\MediaServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            UserServiceProvider::class,
            MediaServiceProvider::class
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => ''
        ]);
    }

    protected function signIn()
    {
        $user = AdminUser::create([
            'name' => 'Admin',
            'email' => 'admin@optimuscms.com',
            'username' => 'admin',
            'password' => bcrypt('password')
        ]);

        $this->actingAs($user, 'admin');

        return $user;
    }
}
