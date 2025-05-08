<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use App\Enums\UserRole;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use App\Livewire\ProfileContactDetails;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

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
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        ProfileContactDetails::setSort(10);

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->visible(outsidePanels: true)
                ->locales(['en','ar']);
        });

        Gate::before(function ($user, $ability) {
            if ($user->hasRole(UserRole::SuperAdmin->value)) {
                return true;
            }
        });

        $this->configureTextColumn();
        $this->configureTextInput();
        $this->configureTable();
    }


    protected function configureTextColumn(): void
    {
        TextColumn::configureUsing(function (TextColumn $column) {
            $column->limit(23)
                ->tooltip(fn (TextColumn $column): ?string => $this->getTooltip($column))
                ->toggleable(true, fn () => $column->isToggledHiddenByDefault ?? false);
        });
    }

    protected function configureTextInput(): void
    {
        TextInput::configureUsing(fn (TextInput $textInput) => $textInput->maxLength(255));
    }

    protected function configureTable(): void
    {
        Table::configureUsing(function (Table $table): void {
            $table->filtersLayout(FiltersLayout::AboveContent)
                ->emptyStateHeading(__('No Records Found'))
                ->emptyStateDescription(__('There are no items to display.'))
                ->defaultSort('id', 'desc')
                ->poll(null)
                ->paginationPageOptions([10, 25, 50]);
        });
    }

    protected function getTooltip(TextColumn $column): ?string
    {
        $state = $column->getState();

        return is_string($state) && strlen($state) > $column->getCharacterLimit() ? $state : null;
    }
}
