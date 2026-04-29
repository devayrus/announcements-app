# School Announcement System (Sistem Pengumuman Sekolah)

A web-based school announcement system built with Laravel 11 and Filament v3. This system allows administrators to create and manage multiple announcements, such as graduation results, and enables students to check their results using their NISN (Nomor Induk Siswa Nasional).

## Features

- **Multi-Announcement Management**: Create and manage multiple announcements from a single admin panel.
- **Scheduled Access**: Set specific dates and times for when announcements become publicly accessible.
- **NISN-Based Result Checking**: Students can securely check their results by entering their NISN.
- **CSV Data Import**: Easily upload participant data (NISN, name, class, results) via CSV files.
- **Responsive Design**: Modern and responsive user interface optimized for both desktop and mobile.
- **Countdown Timer**: Automatically displays a countdown for upcoming announcements.

## Tech Stack

- **Backend**: [Laravel 11](https://laravel.com)
- **Admin Panel**: [Filament v3](https://filamentphp.com)
- **Database**: MySQL 8
- **Frontend**: Blade + Tailwind CSS
- **Local Development**: [Herd](https://herd.laravel.com) (recommended)

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/YOUR_USERNAME/announcements-app.git
   cd announcements-app
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Setup**:
   - Copy `.env.example` to `.env`
   - Generate app key: `php artisan key:generate`
   - Configure your database in `.env`

4. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

5. **Create Admin User**:
   ```bash
   php artisan make:filament-user
   ```

6. **Serve the Application**:
   ```bash
   php artisan serve
   ```

## Deployment

Designed to be lightweight and compatible with shared hosting (cPanel). See `PRD.md` for detailed deployment instructions.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
