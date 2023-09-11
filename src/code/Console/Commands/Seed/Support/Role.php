<?php

namespace Tagd\Core\Console\Commands\Seed\Support;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\User\Role as UserRole;
use Tagd\Core\Models\User\User;
use Tagd\Core\Services\Interfaces\RetailerSales;

class Role extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:seed:support:role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets a user role';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(RetailerSales $retailerSales)
    {
        try {
            $existingRole = $this->choice(
                'What\'s the existing role?',
                $this->roleNames()
            );

            $userEmail = $this->choice(
                'What\'s the existing user?',
                User::actingAs($existingRole)->pluck('email')->toArray()
            );
            $user = User::where('email', $userEmail)->firstOrFail();

            $newRole = $this->choice(
                'What\'s the new role?',
                $this->roleNames()
            );

            switch ($newRole) {
                case UserRole::CONSUMER:
                    $newActorName = $this->choice(
                        'What\'s the new actor?',
                        Consumer::pluck('name')->toArray()
                    );
                    $newActor = Consumer::where('name', $newActorName)->firstOrFail();
                    break;

                case UserRole::RETAILER:
                    $newActorName = $this->choice(
                        'What\'s the new actor?',
                        Retailer::pluck('name')->toArray()
                    );
                    $newActor = Retailer::where('name', $newActorName)->firstOrFail();
                    break;

                case UserRole::RESELLER:
                    $newActorName = $this->choice(
                        'What\'s the new actor?',
                        Reseller::pluck('name')->toArray()
                    );
                    $newActor = Reseller::where('name', $newActorName)->firstOrFail();
                    break;
            }

            throw_if($user->canActAs($newActor), new \Exception('User already has this role'));

            DB::transaction(function () use ($user, $newActor) {
                //delete current roles
                foreach ($user->roles as $role) {
                    $role->delete();
                }

                $user->startActingAs($newActor);
            }, 5);

            $this->info('Done.');
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function roleNames(): array
    {
        return [
            UserRole::CONSUMER,
            UserRole::RETAILER,
            UserRole::RESELLER,
        ];
    }
}
