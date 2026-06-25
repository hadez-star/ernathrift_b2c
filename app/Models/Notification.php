<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    /**
     * Relasi ke User pemilik notifikasi.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper: Buat notifikasi baru untuk seorang user.
     */
    public static function kirim(int $userId, array $data): self
    {
        return self::create([
            'user_id' => $userId,
            'type'    => $data['type']    ?? 'info',
            'title'   => $data['title'],
            'message' => $data['message'],
            'url'     => $data['url']     ?? null,
            'icon'    => $data['icon']    ?? 'fa-bell',
            'color'   => $data['color']   ?? '#D4AF37',
            'is_read' => false,
        ]);
    }
}
