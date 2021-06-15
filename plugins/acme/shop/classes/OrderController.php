<?php

namespace Acme\Shop\Classes;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;
use Mail;
use Backend\Models\User;
use Acme\Shop\Models\Order;

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
      'user_name'  => 'required|min:4|max:50',
      'user_phone' => 'required|min:11|max:50',
      'user_address' => 'max:100',
      'user_mail'  => 'email',
      'user_comment'  => 'max:500',
      'products' => 'required'
    ];

    $messages = [
      'required' => 'Поле обязательно к заполнению!',
      'min'      => 'Минимум :min символов!',
      'max'      => 'Максимум :max символов!',
      'email'    => 'Некорректный e-mail',
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

      $this->insertData($request);

      return response()->json(['status' => 'success', 'message' => 'Заказ добавлен успешно!']);
    }
  }

  public function send(Request $request)
  {

    $rules = [
      'user_name'  => 'required|min:4|max:50',
      'user_phone' => 'required|min:11|max:50',
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

      Mail::send('acme.shop::mail.request', $request->all(), function($message) {
        $message->to($this->getUserMail(), 'Admin Person');
        $message->subject('Новое сообщение с сайта');
      });

      return response()->json(['status' => 'success', 'message' => 'Сообщение отправлено!']);
    }
  }

  public function getUserMail()
  {
    return User::where('is_superuser', 1)->value('email');
  }

  private function insertData($request) {

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

    $query = $order->save();
    return $query;
  }
}
