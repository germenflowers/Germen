<?php
namespace PDV;

use \Bitrix\Main\Loader;

class Smsaero {
    const url = 'https://gate.smsaero.ru/v2/';
    const login = 'servicegermen@gmail.com';
    const key = 'dyjd7tsholWSzhqV69AZUdF2Ahoz';
    const sign = 'germen';

    /*
    INFO            Инфоподпись для всех операторов
    DIGITAL 	    Цифровой канал отправки (допускается только транзакционный трафик)
    INTERNATIONAL 	Международная доставка (Операторы РФ и Казахстана)
    DIRECT 	        Рекламный канал отправки сообщений с бесплатной буквенной подписью.
    SERVICE 	    Сервисный канал для отправки сервисных SMS по утвержденному шаблону с платной подписью отправителя.
    */
    const channel = 'DIRECT';

    private static function logs( $url, $result ){
        if ( $handle = fopen($_SERVER['DOCUMENT_ROOT'].'/upload/logSmsaero.txt', 'a+') ) {
            fwrite($handle, $url."\n");
            fwrite($handle, print_r($result, true)."\n");
            fclose($handle);
        }
    }

    private function send( $str ) {
        $result = '';
        if ( !empty($str) ) {
            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, self::url . $str);
            curl_setopt($ch, CURLOPT_USERPWD, self::login.':'.self::key);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $result = curl_exec($ch);
            curl_close($ch);
        }

        $result = json_decode($result, true);
        self::logs(self::url . $str, $result);

        return $result;
    }

    public function getBalance(){
        $str = 'balance';
        $result = self::send( $str );

        return $result;
    }

    public function sendSMS( $phone, $text ) {
        if ( !empty($phone) && !empty(!empty($phone)) ) {
            $str = 'sms/send?number=' . $phone . '&text=' . html_entity_decode($text) . '&sign=' . self::sign . '&channel=' . self::channel;
            $result = self::send( $str );

            return $result;
        }
    }

}