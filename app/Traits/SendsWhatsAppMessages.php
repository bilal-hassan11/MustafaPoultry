<?php

namespace App\Traits;

trait SendsWhatsAppMessages
{
    /**
     * Send a WhatsApp message using the provided API.
     *
     * @param string $apiKey
     * @param string $mobile
     * @param string $message
     * @param int $priority
     * @param int $type
     * @return string
     */
    public function sendWhatsAppMessage($mobile, $message, $file_url = null, $priority = 10, $type = 2)
    {
        $url = "http://mywhatsapp.pk/api/send.php";

        $parameters = array(
            "api_key" => '923020004130-95d5e94b-7b4f-4702-b572-48b1b163c967',
            "mobile" => $mobile,
            "message" => $message,
            "priority" => $priority,
            "type" => $type,
            'url' => $file_url
        );

        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
