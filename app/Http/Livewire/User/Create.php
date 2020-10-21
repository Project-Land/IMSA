<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\User;

class Create extends Component
{
    public function render()
    {
        return view('livewire.user.create');
    }

    public $name, $email, $password;

    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function createUser()
    {
        $user = new User();
        $user->name = $this->name;
        dd($user->name);
    }
}
