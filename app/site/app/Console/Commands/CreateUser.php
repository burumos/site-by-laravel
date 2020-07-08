<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ログインユーザーを作成する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private $validateRule = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8'],
    ];

    private $validateMessages = [
        'required' => '入力して!!',
        'string' => '文字列を入力して!!',
        'email' => 'メールアドレス形式で入力して！！',
        'max' => ':max文字以上で入力して！！',
        'min' => ':min文字以内に入力して！！',
        'unique' => '他のものとカブってます！！',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->inputAndValid('name');
        $email = $this->inputAndValid('email');
        $password = $this->inputAndValid('password');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $this->line("ID:{$user->id}, email:'{$user->email}' で登録されました");
    }

    private function inputAndValid($key)
    {
        $val = $this->ask("What's new user {$key}?");
        if ($this->validate($val, $key, $this->validateRule[$key])) {
            $val = $this->inputAndValid($key);
        }
        return $val;
    }

    private function validate($val, $name, $rules)
    {
        $validator = Validator::make([$name => $val], [$name => $rules], $this->validateMessages);
        if ($validator->fails()) {
            $this->error($validator->errors()->first($name));
            return true;
        }
        return false;
    }

}
