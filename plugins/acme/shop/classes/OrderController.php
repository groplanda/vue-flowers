<?php

namespace Acme\Shop\Classes;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;
use Mail;
use Backend\Models\User;
use Acme\Shop\Models\Order;
use Acme\Settings\Models\Demand;
use YooKassa\Client;
use Illuminate\Support\Facades\Log;

use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;

class OrderController extends Controller
{
  /**
   *
   *
   * @return Response
   */
  public function add(Request $request)
  {

    $rules = [
      'user_name'    => 'required|min:4|max:50',
      'user_phone'   => 'required|min:11|max:50',
      'user_address' => 'max:100',
      'user_mail'    => 'email',
      'user_comment' => 'max:500',
      'user_payment' => 'required',
      'products'     => 'required',
      'user_sum'     => 'required'
    ];

    $messages = [
      'required' => 'Поле обязательно к заполнению!',
      'min'      => 'Минимум :min символов!',
      'max'      => 'Максимум :max символов!',
      'email'    => 'Некорректный e-mail',
      'user_payment.required' => 'Выберите тип оплаты!'
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {

      $validatorErrors = $validator->errors()->toArray();
      return response()->json(array_merge(['status' => 'error'], $validatorErrors));

    } else {

      Mail::send('acme.shop::mail.message', $request->all(), function($message) {
        $message->to($this->getUserMail(), 'Admin Person');
        $message->subject('Новый заказ с сайта');
      });

      if($request->get('user_mail')) {
        $vars = $request->all();
        Mail::send('acme.shop::mail.order', $vars, function($message) use ($vars) {
          $message->to($vars["user_mail"], 'Admin Person');
          $message->subject('Заказ - '.date('m/d/Y H:i:s', time()));
        });
      };


      if($request->get('user_payment') == 1) { //online
        $products = $this->createProductArray($request->get('products'));
        $client = new Client();
        $client->setAuth(env('SHOP_ID'), env('KASSA_KEY')); // keys
        $payment = $client->createPayment(
          [
            'amount' => [
              'value' => $request->get('user_sum').'.00',
              'currency' => 'RUB',
            ],
            'confirmation' => [
              'type' => 'redirect',
              'return_url' => 'orion.bardinteam.ru/checkout/result',
            ],
            'receipt' => [
              'customer' => [
                'full_name' => $request->get('user_name'),
                'phone' => $request->get('user_phone'),
              ],
              'items' => $products,
            ],
          ],
          uniqid('', true)
        );
        //вставка в базу данных
        $this->insertData($request, $payment['_id']);
        return response()->json(['status' => 'redirect', 'message' => $payment->getConfirmation()->getConfirmationUrl()]);

      } else {
        $this->insertData($request, null);
        return response()->json(['status' => 'success', 'message' => 'Спасибо за ваш заказ, вскоре мы с вами свяжемся!']);
      }

    }
  }

  public function send(Request $request)
  {

    $rules = [
      'user_name'  => 'required|min:4|max:50',
      'user_phone' => 'required|min:11|max:50',
      'user_subject' => 'required|min:11|max:50',
      'user_mail'  => 'email',
      'user_message' => 'max:500',
    ];

    $messages = [
      'required' => 'Поле обязательно к заполнению!',
      'min'      => 'Минимум :min символов!',
      'max'      => 'Максимум :max символов!',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {

      $validatorErrors = $validator->errors()->toArray();
      return response()->json(array_merge(['status' => 'error'], $validatorErrors));

    } else {
      $this->insertDemand($request);
      Mail::send('acme.shop::mail.request', $request->all(), function($message) {
        $message->to($this->getUserMail(), 'Admin Person');
        $message->subject('E-mail');
      });

      return response()->json(['status' => 'success', 'message' => 'Сообщение отправлено!']);
    }
  }

  public function getUserMail()
  {
    return User::where('is_superuser', 1)->value('email');
  }

  public function notification()
  {
    $source = file_get_contents('php://input');
    if($source) {
      $requestBody = json_decode($source, true);
      try {

        if($requestBody['event'] === NotificationEventType::PAYMENT_SUCCEEDED) { // если оплачен
          Log::info($requestBody['object']);
          $order = $this->getOrderById($requestBody['object']['id']);
          $this->updateStatus($order->id);

          Mail::send('acme.shop::mail.payment_admin', $order, function($message) use ($order) {
            $message->to($this->getUserMail(), 'Admin Person');
            $message->subject('Заказ '. $order->name .' успешно оплачен!');
          });

          if(isset($order->user_mail) && !empty($order->user_mail)) {
            Mail::send('acme.shop::mail.payment_user', $order, function($message) use ($order) {
              $message->to($order->user_mail, 'User Person');
              $message->subject('Заказ '. $order->name .' успешно оплачен!');
            });
          };

        }
      } catch (Exception $e) {
        Log::error($e);
      }
    }
  }

  private function getOrderById($id)
  {
    return Order::where('order_id', $id)->first();
  }

  private function updateStatus($id) {
    $order = Order::find($id);
    $order->status = 'pay';
    $order->save();
  }

  private function insertData($request, $u_id) {

    date_default_timezone_set('Europe/Moscow');
    $time = date('m/d/Y H:i:s', time());

    $order = new Order;
    $order->name = "Заказ № $time";
    $order->ip = $_SERVER['REMOTE_ADDR'];
    $order->status = 'new';
    $order->user_name = $request->get('user_name');
    $order->user_phone = $request->get('user_phone');
    $order->user_mail = $request->get('user_mail');
    $order->user_address = $request->get('user_address');
    $order->user_comment = $request->get('user_comment');
    $order->products = $request->get('products');
    $order->user_payment = $request->get('user_payment');

    if(isset($u_id) && !empty($u_id)) {
      $order->order_id = $u_id;
    }

    $query = $order->save();
    return $query;
  }

  private function createProductArray($products) {
    $result = [];
    foreach($products as $product) {
      $price = $product['price'].'.00';
      if (isset($product['sale_price']) && !empty($product['sale_price'])) {
        $price = $product['sale_price'].'.00';
      }
      $result[] = [
        'description' => $product['title'],
        'quantity' => $product['amount'],
        'vat_code' => '2',
        'amount' => [
          'value' => $price,
          'currency' => 'RUB'
        ],
        'payment_mode' => 'full_prepayment',
        'payment_subject' => 'commodity',
      ];
    };
    return $result;
  }

  private function insertDemand($request) {

    $data = new Demand;
    $data->user_name = $request->get('user_name');
    $data->user_phone = $request->get('user_phone');
    $data->user_mail = $request->get('user_mail');
    $data->user_subject = $request->get('user_subject');
    $data->user_message = $request->get('user_message');

    $query = $data->save();
    return $query;
  }
}
