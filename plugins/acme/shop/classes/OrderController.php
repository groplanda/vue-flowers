<?php

namespace Acme\Shop\Classes;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;
use Mail;
use Backend\Models\User;
use Acme\Shop\Models\Order;
use Acme\Settings\Models\Demand;

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
      'user_email'  => 'email',
      'user_comment'  => 'max:500',
      'products' => 'required',
      'total'    => 'required'
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

      if($request->get('user_email')) {
        $vars = $request->all();
        Mail::send('acme.shop::mail.order', $vars, function($message) use ($vars) {
          $message->to($vars["user_email"], 'Admin Person');
          $message->subject('Заказ - '.date('m/d/Y H:i:s', time()));
        });
      };

      $this->insertData($request);

      return response()->json(['status' => 'success', 'message' => 'Спасибо за ваш заказ, вскоре мы с вами свяжемся!']);
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
      $vars = $request->all();
      Mail::send('acme.shop::mail.request', $vars, function($message) use ($vars) {
        $message->to($this->getUserMail(), 'Admin Person');
        $message->subject($vars['user_subject']);
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
    $order->user_mail = $request->get('user_email');
    $order->user_address = $request->get('user_address');
    $order->user_comment = $request->get('user_comment');
    $order->products = $request->get('products');
    $order->user_id = $request->get('user_id');
    $order->total = $request->get('total');

    $query = $order->save();
    return $query;
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
