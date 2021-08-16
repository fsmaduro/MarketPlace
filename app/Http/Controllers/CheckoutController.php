<?php

namespace App\Http\Controllers;

use App\Payment\PagSeguro\CreditCard;
use App\Payment\PagSeguro\Notification;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use App\Store;
use App\UserOrder;
use Ramsey\Uuid\Uuid;

class CheckoutController extends Controller
{
    public function index()
    {
        try {
            if(!auth()->check()) {
                return redirect()->route('login');
            }

            if(!session()->has('cart')) {
                return redirect()->route('home');
            }

            $this->makePagseguroSession();

            $cartItems = array_map(function($line){
                return $line['amount'] * $line['price'];
            }, session()->get('cart'));

            $cartItems = array_sum($cartItems);

            return view('checkout', compact('cartItems'));
        } catch (\Exception $e) {
            session()->forget('pagseguro_session_code');
            redirect()->route('checkout.index');
        }
    }

    public function proccess(Request $request)
    {
        try {
            $dataPost = $request->all();
            $user = auth()->user();
            $cartItems = session()->get('cart');
            $stores = array_unique(array_column($cartItems, 'store_id'));
            $reference = UUid::uuid4();

            $creditCardPayment = new CreditCard($cartItems, $user, $dataPost, $reference);
            $result = $creditCardPayment->doPayment();

            $userOrder = [
                'reference' => $reference,
                'pagseguro_code' => $result->getCode(),
                'pagseguro_status' => $result->getStatus(),
                'items' => serialize($cartItems)
            ];

            $userOrder = $user->orders()->create($userOrder);
            $userOrder->stores()->sync($stores);

            //notificar
            $store = (new Store())->notfyStoreOwners($stores);

            session()->forget('cart');
            session()->forget('pagseguro_session_code');

            return response()->json([
                'data' => [
                    'status' => true,
                    'message' => 'Pedido criado com sucesso!',
                    'order' => $reference
                ]
            ]);
        } catch (\Exception $e) {
            $message = env('APP_DEBUG') ? simplexml_load_string($e->getMessage()) : 'Erro ao processar pedido!';
            return response()->json([
                'data' => [
                    'status' => false,
                    'message' => $message
                ]
            ], 401);
        }
    }

    public function thanks()
    {
        return view('thanks');
    }

    public function notification()
    {
        try {
            $notification = new Notification();
            $notification = $notification->getTransaction();

            $userOrder = UserOrder::whereReference(base64_decode($notification->getReference()));
            $userOrder->update([
                'pagseguro_status' => $notification->getStatus()
            ]);

            if($notification->getStatus() == 3) {
                //PEDIDO PAGO
                //criar regras de mudança de status do pedido
                //notificar loja por email
                //notificar usuário por email
            }

            return response()->json([], 204);
        } catch (\Exception $e) {
            $message = env('APP_EBUG') ? $e->getMessage() : 'falha desconhecida';
            return response()->json(['error' => $message], 500);
        }
    }

    private function makePagseguroSession()
    {
        if(!session()->has('pagseguro_session_code')) {
            $sessionCode = \PagSeguro\Services\Session::create(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );

            return session()->put('pagseguro_session_code', $sessionCode->getResult());
        }
    }
}
