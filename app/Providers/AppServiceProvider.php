<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\Link;

class AppServiceProvider extends ServiceProvider
{
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
        view()->composer('layouts.sidebar', function ($view) {
            $menu = Menu::new()
                ->addclass('sidebar-nav')
                ->add(Link::to(route('home'), $this->wrapInSpan('Home'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
                ->add(Link::to(route('resources.index'), $this->wrapInSpan('Resources'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
                ->add(Link::to(route('contracts.index'), $this->wrapInSpan('Contracts'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
                ->add(Link::to(route('leaves.index'), $this->wrapInSpan('Leaves'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
                ->add(Link::to(route('projects.index'), $this->wrapInSpan('Projects'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
                ->add(Link::to(route('allocations.index'), $this->wrapInSpan('Allocations'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
                ->add(Link::to(route('demands.index'), $this->wrapInSpan('Demands'))->addClass('sidebar-link')->addParentClass('sidebar-item'))
                ->setActiveFromRequest();

            $view->with('sidebarMenu', $menu);
        });
    }

    
    private function wrapInSpan(string $string): string
    {
        $wrapper = '<span class="align-middle">' . $string . '</span>';
        //TODO look up an icon
        return $wrapper;
    }
}
