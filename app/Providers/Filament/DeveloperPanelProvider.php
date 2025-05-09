<?php

namespace App\Providers\Filament;

use App\Filament\Pages\TasksBoardBoardPage;
use App\Livewire\ProfileContactDetails;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use SolutionForest\FilamentSimpleLightBox\SimpleLightBoxPlugin;

class DeveloperPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('developer')
            ->path('developer')
            ->colors([
                'primary' => Color::Orange,
                'gray' => Color::Slate,
            ])
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->discoverResources(in: app_path('Filament/Developer/Resources'), for: 'App\\Filament\\Developer\\Resources')
            ->discoverPages(in: app_path('Filament/Developer/Pages'), for: 'App\\Filament\\Developer\\Pages')
            ->pages([
                Pages\Dashboard::class,
                TasksBoardBoardPage::class,
            ])
            ->sidebarFullyCollapsibleOnDesktop()
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->visible(fn() => Filament::auth()->check())
                    ->url(url('/client/my-profile')) // Adjusted route helper here
                    ->icon('heroicon-m-user-circle'),
                'logout' => MenuItem::make(),
            ])
            ->discoverWidgets(in: app_path('Filament/Developer/Widgets'), for: 'App\\Filament\\Developer\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->plugins([
                SimpleLightBoxPlugin::make(),
                SpatieLaravelTranslatablePlugin::make()->defaultLocales(['en', 'ar']),
                GlobalSearchModalPlugin::make(),
                BreezyCore::make()
                    ->myProfileComponents([
                        ProfileContactDetails::class,
                    ])
                    ->myProfile(
                        hasAvatars: true,
                        shouldRegisterNavigation: true
                    )
                    ->avatarUploadComponent(fn ($fileUpload) => $fileUpload->columnSpan('full')),
            ]);
    }
}
