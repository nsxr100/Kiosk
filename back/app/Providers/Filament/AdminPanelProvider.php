<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Mang Inasal Kiosk')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<link rel="stylesheet" href="' . asset('css/mang-inasal-admin.css') . '?v=' . filemtime(public_path('css/mang-inasal-admin.css')) . '">',
            )
            ->colors([
                'primary' => [
                    50 => '#e7f6ec',
                    100 => '#c6ead2',
                    200 => '#92d8aa',
                    300 => '#5ec582',
                    400 => '#2fb25f',
                    500 => '#009637',
                    600 => '#008531',
                    700 => '#006b2e',
                    800 => '#005424',
                    900 => '#003d1a',
                    950 => '#002d13',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([]);
    }
}
