<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Perfil;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user)
    {
        Perfil::create([
            'usuario_id' => $user->id,
            'nombre' => $user->username,
        ]);

        Mail::to($user->email)->send(new WelcomeMail($user));

    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
