<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Hash;

class Identification extends Component
{

    #Rule[(required|max:15)]
    public $name;
    #Rule[(required|email|unique:users)]
    public $email;
    #Rule[(required)]
    public $password;
    public function save(){

        
        User::create([
            "name" => $this->name,
            "email" => $this->email,
            "password" => Hash::make($this->password)
        ]);

        return redirect("/admin");
    }

    public function render()
    {
        return view('livewire.identification');
    }
}
