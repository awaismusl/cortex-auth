<?php

declare(strict_types=1);

namespace Cortex\Auth\Console\Commands;

use Cortex\Auth\Models\Admin;
use Cortex\Auth\Models\Member;
use Cortex\Auth\Models\Manager;
use Illuminate\Console\Command;
use Cortex\Auth\Models\Guardian;

class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:seed:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Cortex Auth Data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->warn($this->description);

        $this->call('db:seed', ['--class' => 'CortexAuthSeeder']);

        // Create models
        $admin = $this->createAdmin($adminPassword = str_random());
        $guardian = $this->createGuardian($guardianPassword = str_random());
        $manager = $this->createManager($managerPassword = str_random());
        $member = $this->createMember($memberPassword = str_random());

        // Assign roles
        $admin->assign('superadmin');

        $this->table(['Username', 'Password'], [
            ['username' => $admin['username'], 'password' => $adminPassword],
            ['username' => $guardian['username'], 'password' => $guardianPassword],
            ['username' => $manager['username'], 'password' => $managerPassword],
            ['username' => $member['username'], 'password' => $memberPassword],
        ]);
    }

    /**
     * Create admin model.
     *
     * @param string $password
     *
     * @return \Cortex\Auth\Models\Admin
     */
    protected function createAdmin(string $password): Admin
    {
        $admin = [
            'is_active' => true,
            'username' => 'Admin',
            'given_name' => 'Admin',
            'family_name' => 'User',
            'email' => 'admin@example.com',
        ];

        return tap(app('cortex.auth.admin')->firstOrNew($admin)->fill([
            'remember_token' => str_random(10),
            'email_verified_at' => now(),
            'password' => $password,
        ]), function ($instance) {
            $instance->save();
        });
    }

    /**
     * Create guardian model.
     *
     * @param string $password
     *
     * @return \Cortex\Auth\Models\Guardian
     */
    protected function createGuardian(string $password): Guardian
    {
        $guardian = [
            'is_active' => true,
            'username' => 'Guardian',
            'email' => 'guardian@example.com',
        ];

        return tap(app('cortex.auth.guardian')->firstOrNew($guardian)->fill([
            'remember_token' => str_random(10),
            'password' => $password,
        ]), function ($instance) {
            $instance->save();
        });
    }

    /**
     * Create manager model.
     *
     * @param string $password
     *
     * @return \Cortex\Auth\Models\Manager
     */
    protected function createManager(string $password): Manager
    {
        $manager = [
            'is_active' => true,
            'username' => 'Manager',
            'given_name' => 'Manager',
            'family_name' => 'User',
            'email' => 'manager@example.com',
        ];

        return tap(app('cortex.auth.manager')->firstOrNew($manager)->fill([
            'remember_token' => str_random(10),
            'email_verified_at' => now(),
            'password' => $password,
        ]), function ($instance) {
            $instance->save();
        });
    }

    /**
     * Create member model.
     *
     * @param string $password
     *
     * @return \Cortex\Auth\Models\Member
     */
    protected function createMember(string $password): Member
    {
        $member = [
            'is_active' => true,
            'username' => 'Member',
            'given_name' => 'Member',
            'family_name' => 'User',
            'email' => 'member@example.com',
        ];

        return tap(app('cortex.auth.member')->firstOrNew($member)->fill([
            'remember_token' => str_random(10),
            'email_verified_at' => now(),
            'password' => $password,
        ]), function ($instance) {
            $instance->save();
        });
    }
}
