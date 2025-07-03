<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
// use Illuminate\Http\Request; // Hapus ini

class UserActivityDetected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public ?string $ipAddress; // Ubah dari Request menjadi string IP
    public ?string $userAgent; // Ubah dari Request menjadi string User Agent

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, ?string $ipAddress, ?string $userAgent) // Sesuaikan parameter konstruktor
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }
}
