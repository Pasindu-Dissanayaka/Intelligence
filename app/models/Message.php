<?php

namespace App\Models;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $table = 'messages';
    protected $fillable = [
        'userID',
        'message',
        'is_bot',
        'sent_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = []; // Nothing for this table

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'is_bot' => 'boolean',
        'sent_at' => 'datetime',
    ];

    /**
     * The Eloquent Model Relationship definition
     * @var class
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    // -----------------------------------------------------
    // This is a little out of scope but..
    // If we are to store the messages on the database in
    // plaintext that is unsafe. Ergo these encrypt & decrypt
    // functions

    public static function encryptText($plaintext)
    {
        $key = base64_decode(_env('MESSAGE_ENCRYPTION_KEY', null));
        $iv = random_bytes(16);

        $encrypted = openssl_encrypt($plaintext, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decryptText($encrypted)
    {
        $key = base64_decode(_env('MESSAGE_ENCRYPTION_KEY', null));
        $data = base64_decode($encrypted);

        $iv = substr($data, 0, 16);
        $cipherText = substr($data, 16);

        return openssl_decrypt($cipherText, 'AES-256-CBC', $key, 0, $iv);
    }

    /**
     *  TODO: Write a cli function to generate / rotate the message encryption keys on deployment
     */

    public function getDecryptedMessage()
    {
        return self::decryptText($this->attributes['message']);
    }

    public function setEncryptedMessage($value)
    {
        $this->attributes['message'] = self::encryptText($value);
    }
}
