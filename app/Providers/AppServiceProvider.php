<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // view()->composer('layouts.sidebar', function ($view) {
        //     $menu = Menu::new()
        //         ->addclass('sidebar-nav')
        //         ->add(Link::to(route('home'), $this->wrapInSpan('Home'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
        //         ->add(Link::to(route('resources.index'), $this->wrapInSpan('Resources'))->addClass('sidebar-link')->addClass('collapsed')->addParentClass('sidebar-item'))
        //         ->add(Link::to(route('contracts.index'), $this->wrapInSpan('Contracts'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
        //         ->add(Link::to(route('leaves.index'), $this->wrapInSpan('Leaves'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
        //         ->add(Link::to(route('skills.index'), $this->wrapInSpan('Skills'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
        //         ->add(Link::to(route('projects.index'), $this->wrapInSpan('Projects'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
        //         ->add(Link::to(route('allocations.index'), $this->wrapInSpan('Allocations'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
        //         ->add(Link::to(route('services.index'), $this->wrapInSpan('Services'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
        //         ->add(Link::to(route('demands.index'), $this->wrapInSpan('Demands'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
        //         ->setActiveFromRequest();

        //     $view->with('sidebarMenu', $menu);
        // });

        $this->bootRoute();
    }

    private function wrapInSpan(string $string): string
    {
        $wrapper = '<span class="align-middle">'.$string.'</span>';

        // TODO look up an icon
        return $wrapper;
    }

    public function bootRoute(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
