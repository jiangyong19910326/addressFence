<?php
/**
 * Created by PhpStorm.
 * User: 82683
 * Date: 2018/9/25 0025
 * Time: 下午 6:47
 */

namespace Jiangyong\AddressFence;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(addressFence::class, function($app){
            return new addressFence($app['config']['services']['addressFence']);
        });

        $this->app->alias(addressFence::class, 'addressFence');
    }

    public function provides()
    {
        return [addressFence::class, 'addressFence'];
    }
}