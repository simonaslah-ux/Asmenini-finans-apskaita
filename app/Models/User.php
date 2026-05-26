<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function createDefaultCategories(): void
    {
        $defaultCategories = [
            [
                'name' => 'Atlyginimas',
                'type' => 'income',
            ],
            [
                'name' => 'Stipendija',
                'type' => 'income',
            ],
            [
                'name' => 'Kita',
                'type' => 'income',
            ],
            [
                'name' => 'Maistas',
                'type' => 'expense',
            ],
            [
                'name' => 'Kuras',
                'type' => 'expense',
            ],
            [
                'name' => 'Komunaliniai',
                'type' => 'expense',
            ],
            [
                'name' => 'Nuoma',
                'type' => 'expense',
            ],
            [
                'name' => 'Pramogos',
                'type' => 'expense',
            ],
            [
                'name' => 'Kita',
                'type' => 'expense',
            ],
        ];

        foreach ($defaultCategories as $category) {
            $this->categories()->create($category);
        }
    }
}