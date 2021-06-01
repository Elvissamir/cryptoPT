<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Crypto;
use Illuminate\Http\Request;
use App\Http\Resources\CryptoResource;
use App\Http\Requests\StoreCryptoInPortfolioRequest;
use App\Http\Requests\UpdateCryptoInPortfolioRequest;

class PortfolioCryptosController extends Controller
{
    //Properties
    private $jsonHeaders = ['Content-Type' => 'application/json'];
    private $cryptoInPortfolioMessage = 'The crypto has already been added to the portfolio of current user.';
    private $cryptoNotInPortfolioMessage = 'The crypto hasnt been added to the portfolio.';

    //Handling methods
    public function index()
    {
        $user = auth()->user();
        $cryptos = $user->portfolio->cryptos;

        return CryptoResource::collection($cryptos);
    }

    public function store(StoreCryptoInPortfolioRequest $request)
    {
        $portfolio = auth()->user()->portfolio;

        $crypto = Crypto::findOrFail($request->id);

        $this->abortWIthStatusIf($portfolio->hasCrypto($crypto->name), 409, $this->cryptoInPortfolioMessage);

        $portfolio->addCrypto($crypto->id, $request->amount);

        return response()->json([
            'data' => [
                'id' => $request->id,
                'amount' => $request->amount
            ]
        ], 201);
    }

    public function update(Crypto $crypto, UpdateCryptoInPortfolioRequest $request)
    {
        $portfolio = auth()->user()->portfolio;

        $this->abortWIthStatusIf(!$portfolio->hasCrypto($crypto->name), 404, $this->cryptoNotInPortfolioMessage);

        $portfolio->updateCryptoAmount($crypto->id, $request->amount);

        return response()->json([
            'data' => [
                'id' => $crypto->id,
                'amount' => $request->amount
            ]
        ], 201);
    }

    public function destroy(Crypto $crypto)
    {
        $portfolio = auth()->user()->portfolio;

        $this->abortWithStatusIf(!$portfolio->hasCrypto($crypto->name), 404, $this->cryptoNotInPortfolioMessage);

        $portfolio->removeCrypto($crypto->id);

        return new CryptoResource($crypto);
    }

    private function abortWithStatusIf($condition, $status, $message)
    {
        return abort_if($condition, $status, $message, $this->jsonHeaders);
    }

    private function checkIntegerId($id)
    {
        $message = 'The crypto id must be an integer.';
        $headers = ['Content-Type' => 'application/json'];

        return abort_if(!ctype_digit(strval($id)), 422, $message, $headers);
    }

    private function tryToFindCrypto($id)
    {
        try {
            return Crypto::findOrFail($cryptoId);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'message' => "The crypto with the given id doesnt exist."
            ], 404);
        }
    }
}
