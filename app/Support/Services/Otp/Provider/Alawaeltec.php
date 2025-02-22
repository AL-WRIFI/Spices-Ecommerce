<?php 

namespace App\Support\Services\Otp\Provider;

use Illuminate\Support\Facades\Log;


class Alawaeltec
{
    public function send($phone_number, $message)
    {
        try {
            $url = "https://sms.alawaeltec.com/MainServlet";
            
            $query_params = [
                'orgName'   => 'Demosms',
                'userName'  => 'Pooqsha',
                'password'  => 'Pooqsha@177789',
                'mobileNo'  => $phone_number,
                'text'      => $message,
                'coding'    => 2
            ];

            $full_url = $url . '?' . http_build_query($query_params);

            Log::channel('daily')->info("full_url".$full_url);

            $ch = curl_init($full_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \Exception(curl_error($ch), curl_errno($ch));
            }

            $info = curl_getinfo($ch);
            curl_close($ch);

            list($headers, $content) = explode("\r\n\r\n", $response, 2);

            Log::channel('daily')->info("Response: $response, Message: $message, Number: $phone_number");

            if (!strpos($content, 'Success')) {
                throw new \Exception('Message sending failed');
            }

            return true;
        } catch (\Exception $e) {
            Log::channel('daily')->error("Error: " . $e->getCode() . " - " . $e->getMessage());
            return false;
        }
    }
}
