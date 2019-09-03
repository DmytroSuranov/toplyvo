<?php

namespace App\Http\Controllers;


use App\Card;
use App\CardHistory;
use App\Http\Requests\CardRequest;
use App\Http\Requests\CreditCashRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardsController extends Controller
{
    public function index()
    {
        return view('cards.cards', [
            'user' => Auth::user()
        ]);
    }

    public function create()
    {
        return view('cards.create', [
            'user' => Auth::user()
        ]);
    }

    public function store(CardRequest $request)
    {
        Card::create([
            'card_number' => Card::generateCardNumber(),
            'pin' => $request->input('code'),
            'user_id' => auth()->id()
        ]);

        return redirect()->route('cards')->with('success', 'Card was created.');
    }

    public function delete(Card $card)
    {
        $card->delete();
        return redirect()->route('cards')->with('success', 'Card was deleted.');
    }

    public function cashServicePage()
    {
        return view('cards.cash', [
            'user' => Auth::user()
        ]);
    }

    public function transfer(Request $request)
    {
        return view('cards.transfer', [
            'user' => Auth::user(),
            'card' => Card::getCardById($request->input('card_id')),
        ]);
    }

    public function send()
    {
        $response = Card::sendMoneyToAnotherCard();
        if ($response !== true) {
            return redirect()->route('cards')->withErrors(['msg' => $response]);
        }
        return redirect()->route('cards')->with('success', 'Request was sent.');
    }

    public function getCards(Request $request)
    {
        $cardNum = $request->input('query');
        return Card::getCardsByNumber($cardNum);
    }

    public function changeCardValue(CreditCashRequest $request)
    {
        $response = Card::charge();
        if ($response !== true) {
            return redirect()->route('cards')->withErrors(['msg' => $response]);
        }
        return redirect()->route('cards')->with('success', 'Request was sent.');
    }
}
