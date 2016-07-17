<?php namespace Mosaiqo\Packet;

use Illuminate\Support\ServiceProvider;
use Mosaiqo\Packet\Console\Commands\NewPackageCommand;
use Mosaiqo\Packet\Console\Commands\ClonePackageCommand;
/**
 * This is the service provider.
 *
 * You have to place this line in the providers array inside app/config/app.php
 * <code>'Mosaiqo\Packet\Providers\PacketServiceProvider',</code>
 *
 * @package Packet
 * @author Boudy de Geer <boudydegeer@mosaiqo.com>
 * 
 **/
class PacketServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The console commands.
     *
     * @var bool
     */
    protected $commands = [
        NewPackageCommand::class,
        ClonePackageCommand::class,
    ];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/packet.php' => config_path('packet.php')
        ], 'config');
    }

    /**
     * Register the command.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('packager');
    }
}
