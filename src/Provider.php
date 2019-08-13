<?php

namespace GlaivePro\Hidevara;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
		$this->publishes([
				__DIR__.'/config.php' => config_path('hidevara.php'),
		], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
		// We merge the config inside the class because the script might crash before calling this provider.
		$handler = resolve(\Illuminate\Contracts\Debug\ExceptionHandler::class);
		
		// If the developer bound the Hider manually, we're leaving
		if ($handler instanceof HidingHandler)
			return;
		
		if ('testing' != env('APP_ENV'))
			$this->app->extend(
				\Illuminate\Contracts\Debug\ExceptionHandler::class,
				function($originalHandler) {
					return new HidingHandler($originalHandler);
			});
    }
}