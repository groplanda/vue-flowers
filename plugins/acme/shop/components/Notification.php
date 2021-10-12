<?php namespace Acme\Shop\Components;

use Cms\Classes\ComponentBase;

use Backend\Models\User;
use Acme\Shop\Models\Order;

use Mail;
use Illuminate\Support\Facades\Log;

use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\Notification\NotificationCanceled;
use YooKassa\Model\NotificationEventType;

class Notification extends ComponentBase
{

  public function componentDetails()
  {
      return [
          'name' => 'Yandex notification',
          'description' => 'Yandex notification'
      ];
  }

  public function onRun()
  {
    $this->notificate();
  }

  public function notificate()
  {
    try {
      $source = file_get_contents('php://input');
      Log::info($source);
      $data = json_decode($source, true);
      $factory = new \YooKassa\Model\Notification\NotificationFactory();
      $notificationObject = $factory->factory($data);
      $responseObject = $notificationObject->getObject();
      $order = $this->getOrderById($responseObject->getId());

      if ($notificationObject->getEvent() === NotificationEventType::PAYMENT_SUCCEEDED) {
        $this->updateStatus($order->id);
        Log::info($responseObject);
        Mail::send('acme.shop::mail.payment_admin', $order, function($message) use ($order) {
          $message->to($this->getUserMail(), 'Admin Person');
          $message->subject('Заказ '. $order->name . ' успешно оплачен!');
        });

        if(isset($order->user_mail) && !empty($order->user_mail)) {
          Mail::send('acme.shop::mail.payment_user', $order, function($message) use ($order) {
            $message->to($order->user_mail, 'User Person');
            $message->subject('Заказ '. $order->name . ' успешно оплачен!');
          });
        };

      } elseif ($notificationObject->getEvent() === NotificationEventType::PAYMENT_CANCELED) {
        Log::info($responseObject);
        Mail::send('acme.shop::mail.payment_canceled_admin', $order, function($message) use ($order) {
          $message->to($this->getUserMail(), 'Admin Person');
          $message->subject('Оплата '. $order->name . ' отменена');
        });
      } else {
        header('HTTP/1.1 400 Something went wrong');
        exit();
      }

    } catch (Exception $e) {
      header('HTTP/1.1 400 Something went wrong');
      Log::error($e);
      exit();
    }
    // $source = file_get_contents('php://input');
    // if($source) {
    //   $requestBody = json_decode($source, true);
    //   try {
    //     $order = $this->getOrderById($requestBody['object']['id']);
    //     Mail::send('acme.shop::mail.payment_admin', $order, function($message) use ($order) {
    //       $message->to($this->getUserMail(), 'Admin Person');
    //       $message->subject('Заказ '. $order->name . ' успешно оплачен!');
    //     });

    //     if(isset($order->user_mail) && !empty($order->user_mail)) {
    //       Mail::send('acme.shop::mail.payment_user', $order, function($message) use ($order) {
    //         $message->to($order->user_mail, 'User Person');
    //         $message->subject('Заказ '. $order->name . ' успешно оплачен!');
    //       });
    //     };

    //     if($requestBody['event'] === NotificationEventType::PAYMENT_SUCCEEDED) { // если оплачен
    //       $this->updateStatus($order->id);
    //     }

    //     if($requestBody['event'] === NotificationEventType::PAYMENT_CANCELED) { // если отменен
    //       Mail::send('acme.shop::mail.payment_canceled_admin', $order, function($message) use ($order) {
    //         $message->to($this->getUserMail(), 'Admin Person');
    //         $message->subject('Оплата '. $order->name . ' отменена');
    //       });
    //     }
    //     Log::info($requestBody['object']);
    //   } catch (Exception $e) {
    //     Log::error($e);
    //   }
    // }
  }

  private function getUserMail()
  {
    return User::where('is_superuser', 1)->value('email');
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

}
