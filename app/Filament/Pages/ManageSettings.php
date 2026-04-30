<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Tampilan';
    protected static ?string $title = 'Pengaturan Tampilan';
    
    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        $this->form->fill($setting->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Brand')
                    ->description('Atur nama dan logo yang muncul di navigasi atas')
                    ->schema([
                        TextInput::make('brand_name')
                            ->label('Nama Brand')
                            ->placeholder('SMA Negeri 15 Bandung')
                            ->maxLength(255),
                        FileUpload::make('brand_logo')
                            ->label('Logo Brand')
                            ->image()
                            ->directory('settings')
                            ->maxSize(1024),
                        FileUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->directory('settings')
                            ->maxSize(512)
                            ->helperText('Gambar kecil yang muncul di tab browser (rekomendasi: 32x32px)')
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $setting = Setting::firstOrCreate(['id' => 1]);
        $setting->update($data);

        Notification::make()
            ->success()
            ->title('Pengaturan berhasil disimpan.')
            ->send();
    }
}
