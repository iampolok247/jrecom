<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class TenantSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:setup 
                            {--name= : Name of the Site/Store}
                            {--email= : Admin Email}
                            {--password= : Admin Password}
                            {--primary-color= : Primary Theme Hex Color}
                            {--paymently-key= : Paymently API Key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize and customize site white-label settings for new hosting instance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('====================================================');
        $this->info('   JR-Ecom White-Label Site Tenant Initializer     ');
        $this->info('====================================================');

        $siteName = $this->option('name') ?: $this->ask('Enter Site/Store Name', 'Bongomart');
        $adminEmail = $this->option('email') ?: $this->ask('Enter Admin Email', 'admin@' . strtolower(preg_replace('/[^A-Za-z0-9]/', '', $siteName)) . '.com');
        $adminPass = $this->option('password') ?: $this->secret('Enter Admin Password (default: admin123)') ?: 'admin123';
        $primaryColor = $this->option('primary-color') ?: $this->ask('Enter Primary Hex Color', '#4f46e5');
        $paymentlyKey = $this->option('paymently-key') ?: $this->ask('Enter Paymently API Key (optional)', 'f94ikvBxS2NJVhvuYyJqquE60My9QJXmjsLKZi1q');

        $this->info("Setting up $siteName ...");

        // 1. Update Site Settings
        SiteSetting::setKey('site_name', $siteName, 'general');
        SiteSetting::setKey('site_title', $siteName . ' - Premium E-Commerce Store', 'general');
        SiteSetting::setKey('primary_color', $primaryColor, 'theme');
        SiteSetting::setKey('paymently_api_key', $paymentlyKey, 'paymently');
        SiteSetting::setKey('paymently_enabled', '1', 'paymently');

        // 2. Create or Update Admin User
        $user = User::where('email', $adminEmail)->first();
        if (!$user) {
            $user = User::create([
                'name' => $siteName . ' Admin',
                'email' => $adminEmail,
                'password' => Hash::make($adminPass),
                'role' => 'admin',
            ]);
            $this->info("Created new Admin user: $adminEmail");
        } else {
            $user->update([
                'password' => Hash::make($adminPass),
                'role' => 'admin',
            ]);
            $this->info("Updated existing Admin user: $adminEmail");
        }

        // 3. Clear Caches
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('view:clear');

        $this->newLine();
        $this->info('SUCCESS: Site tenant initialized successfully for ' . $siteName);
        $this->info('Admin Login Email: ' . $adminEmail);
        $this->info('====================================================');

        return Command::SUCCESS;
    }
}
