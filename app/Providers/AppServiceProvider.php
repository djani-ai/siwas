<?php

namespace App\Providers;

use Filament\Forms\Components\RichEditor as ComponentsRichEditor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);


        ComponentsRichEditor::configureUsing(function (ComponentsRichEditor $component) {
            $component
                ->fileAttachmentsDisk('public2')
                ->fileAttachmentsDirectory('uploads');
            // ->fileAttachmentsVisibility('public');
            //->fileAttachmentsRules(['image', 'mimes:jpg,jpeg,png,gif', 'max:2048']);
        });
    }

    public function boot(): void
    {
        //
    }
}
