<?php

namespace App\Models;

use App\Models\Crypto;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portfolio extends Model
{
    use HasFactory;

    //Properties
    protected $fillable = ['user_id'];

    //Methods
    public function hasCrypto($name)
    {
        if ($this->cryptos()->where('name', $name)->first() == NULL)
            return false;
        else 
            return true;
    }

    public function findCrypto($cryptoName)
    {
        return $this->cryptos()->where('name', $cryptoName)->first();
    }

    public function addCrypto($id, $amount)
    {
        $this->cryptos()->attach($id, ['amount' => $amount]);
    }

    public function updateCryptoAmount($id, $amount)
    {
        $this->cryptos()->updateExistingPivot($id, [
            'amount' => $amount
        ]);
    }

    public function removeCrypto($id)
    {
        $this->cryptos()->detach($id);
    }

    //Relationships
    /**
     * Get the user that owns the Portfolio
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cryptos()
    {
        return $this->belongsToMany(Crypto::class)->withPivot('amount')->withTimestamps();
    }


}
