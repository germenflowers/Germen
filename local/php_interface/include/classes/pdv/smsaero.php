<?php

namespace PDV;

use \Bitrix\Main\Loader;

class Smsaero
{
    public const url = 'https://gate.smsaero.ru/v2/';
    public const login = 'servicegermen@gmail.com';
    public const key = 'dyjd7tsholWSzhqV69AZUdF2Ahoz';
    public const sign = 'germen';

    /**
     * INFO            Инфоподпись для всех операторов
     * DIGITAL         Цифровой канал отправки (допускается только транзакционный трафик)
     * INTERNATIONAL   Международная доставка (Операторы РФ и Казахстана)
     * DIRECT          Рекламный канал отправки сообщений с бесплатной буквенной подписью.
     * SERVICE         Сервисный канал для отправки сервисных SMS по утвержденному шаблону с платной подписью отправителя.
     */
    public const channel = 'DIRECT';

    /**
     * @param string $url
     * @param array $result
     */
    private static function logs(string $url, array $result): void
    {
        if ($handle = fopen($_SERVER['DOCUMENT_ROOT'].'/upload/logSmsaero.txt', 'ab+')) {
            fwrite($handle, $url.PHP_EOL);
            fwrite($handle, print_r($result, true).PHP_EOL);
            fclose($handle);
        }
    }

    /**
     * @param string $str
     * @return array
     */
    private function send(string $str): array
    {
        $result = array();

        if (empty($str)) {
            return $result;
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::url.$str);
        curl_setopt($ch, CURLOPT_USERPWD, self::login.':'.self::key);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);

        self::logs(self::url.$str, $result);

        return $result;
    }

    /**
     * @return array
     */
    public function getBalance(): array
    {
        $str = 'balance';

        return $this->send($str);
    }

    /**
     * @param string $phone
     * @param string $text
     * @return array
     */
    public function sendSMS(string $phone, string $text): array
    {
        if (empty($phone)) {
            return array();
        }

        $params = array(
            'number' => $phone,
            'text' => html_entity_decode($text),
            'sign' => self::sign,
            'channel' => self::channel,
        );

        return $this->send('sms/send?'.http_build_query($params));
    }

}