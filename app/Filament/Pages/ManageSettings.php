<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
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

    public static function getNavigationIcon(): string | \BackedEnum | null
    {
        return 'heroicon-o-cog-6-tooth';
    }

    public static function getNavigationLabel(): string
    {
        return 'Pengaturan Tampilan';
    }

    public static function getNavigationGroup(): string | \UnitEnum | null
    {
        return 'Pengaturan Website';
    }

    public static function getNavigationSort(): ?int
    {
        return 10;
    }

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'Pengaturan Tampilan';
    }
    
    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        $this->form->fill($setting->toArray());
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Brand')
                    ->description('Atur nama dan logo yang muncul di navigasi atas')
                    ->columns(2)
                    ->schema([
                        TextInput::make('brand_name')
                            ->label('Nama Brand (Navigasi)')
                            ->placeholder('SMA Negeri 15 Bandung')
                            ->maxLength(255),
                        TextInput::make('site_title')
                            ->label('Judul Website (Tab Browser)')
                            ->placeholder('Sistem Pengumuman SMAN 15 Bandung')
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
                    ]),
                Section::make('SEO & Metadata')
                    ->description('Atur tampilan saat link website dibagikan ke WhatsApp atau Media Sosial')
                    ->schema([
                        TextInput::make('site_description')
                            ->label('Deskripsi Website')
                            ->placeholder('Halaman resmi pengumuman hasil seleksi/kelulusan SMA Negeri 15 Bandung.')
                            ->maxLength(255),
                        FileUpload::make('seo_image')
                            ->label('Gambar Preview (OG Image)')
                            ->image()
                            ->directory('settings')
                            ->helperText('Gambar yang muncul saat link dibagikan (Rekomendasi: 1200x630px)')
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
