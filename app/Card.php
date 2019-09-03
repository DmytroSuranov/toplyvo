<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Card extends Model
{
    use Notifiable;
    /**
     * @var string
     */
    protected $table = 'user_cards';
    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'card_number', 'pin'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(CardHistory::class, 'user_card_id', 'id')->latest();
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
     * @param null $number
     * @return array
     */
    public static function getCardsByNumber($number = null)
    {
        if (!$number) {
            $cards = Card::all();
        } else {
            $cards = Card::where('card_number', 'like', '%' . $number . '%')->get();
        }
        return $cards->pluck('card_number')->toArray();
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
     * @return bool|string
     */
    public static function sendMoneyToAnotherCard()
    {
        DB::beginTransaction();
        try {
            $postData = request()->all();
            $senderCard = self::getCardById($postData['card_id']);
            $recipientCard = self::getCardByNumber($postData['card']);
            if (!$recipientCard) {
                throw new \Exception('Wrong card number');
            }
            if ($postData['pin'] != $senderCard->pin) {
                throw new \Exception('Wrong PIN code. Please try again.');
            }
            if ($recipientCard->id == $senderCard->id) {
                throw new \Exception('You can\'t send money to the same card');
            }
            $amount = $postData['amount'];
            if ($senderCard->amount < $amount) {
                throw new \Exception('You do not have enough money');
            }
            $recipientCard->amount += $amount;
            $senderCard->amount -= $amount;
            $recipientCard->save();
            $senderCard->save();

            CardHistory::createRowForTransfer($amount, CardHistory::TRANSFER_TYPE_TO, $senderCard, $recipientCard);
            CardHistory::createRowForTransfer($amount, CardHistory::TRANSFER_TYPE_FROM, $recipientCard, $senderCard);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
        return true;
    }

    /**
     * @return bool|string
     */
    public static function charge()
    {
        DB::beginTransaction();
        try {
            $card = Card::getCardById(request()->input('card_id'));
            if (request()->input('pin') != $card->pin) {
                throw new \Exception('Wrong PIN code. Please try again.');
            }
            $type = request()->input('type');
            $amount = request()->input('amount');
            if ($type == CardHistory::REPLENISH_TYPE) {
                $card->amount += $amount;
            } elseif ($type == CardHistory::CASH_OUT_TYPE) {
                if ($card->amount < $amount) {
                    throw new \Exception('You do not have enough money.');
                }
                $card->amount -= $amount;
            }
            $card->save();
            $data = request()->all();
            $data['user_card_id'] = $card->id;
            CardHistory::create($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
        return true;
    }

    /**
     * @param $number
     * @return mixed
     */
    public static function getCardByNumber($number)
    {
        return Card::where('card_number', $number)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getCardById($id)
    {
        return Card::findOrFail($id);
    }
}
