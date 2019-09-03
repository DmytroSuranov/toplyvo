<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class CardHistory extends Model
{
    const REPLENISH_TYPE = 1;

    const CASH_OUT_TYPE = 2;

    const TRANSFER_TYPE_TO = 3;

    const TRANSFER_TYPE_FROM = 4;

    protected $table = 'user_cards_history';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_card_id', 'amount', 'type', 'recipient_card_number'
    ];

    /**
     * @var array
     */
    public $types = [
        1 => 'Replenish',
        2 => 'Cash out',
        3 => 'Transfer to',
        4 => 'Transfer from',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function card()
    {
        return $this->hasOne(Card::class, 'id', 'user_card_id');
    }

    /**
     * @param $value
     * @return string
     */
    public function getDateAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)
            ->format('d F Y H:i:s');
    }

    /**
     * @param $value
     * @return string
     */
    public function getAmountAttribute($value)
    {
        return number_format($value, 2);
    }

    /**
     * @return string
     */
    public static function generateCardNumber()
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 16; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @param $amount
     * @param $type
     * @param null $senderCard
     * @param null $recipientCard
     * @return bool
     */
    public static function createRowForTransfer($amount, $type, $senderCard = null, $recipientCard = null)
    {
        $history = new CardHistory();
        $history->fill([
            'user_card_id' => $senderCard->id,
            'amount' => $amount,
            'type' => $type,
            'recipient_card_number' => $recipientCard->card_number,
        ]);
        return $history->save();
    }
}
