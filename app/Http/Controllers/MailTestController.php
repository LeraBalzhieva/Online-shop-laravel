<?php

namespace App\Http\Controllers;

use App\Jobs\SendTestEmailJob;
use Illuminate\Http\Request;

class MailTestController extends Controller
{
    /**
     * Контроллер для тестовой отправки писем.
     */

    /**
     * Отправляет тестовое письмо указанному адресу.
     * @param Request $request HTTP-запрос, содержащий email-получателя.
     * @return \Illuminate\Http\JsonResponse JSON-ответ с сообщением и email-адресом.
     */
    public function send(Request $request)
    {
        $email = $request->input('email', 'youremail@example.com');

        SendTestEmailJob::dispatch($email);

        return response()->json([
            'message' => 'Письмо отправлено в очередь!',
            'email' => $email,
        ]);
    }
}
