<?php

namespace app\service {

    class Disqus
    {
        public static $DISQUS_SECRET_KEY = false;
        public static $DISQUS_PUBLIC_KEY = false;
        public static $SHORTNAME = false;

        public static function rx_setup()
        {
            $config = \Config::getSection("DISQUS_CONFIG");
            self::$DISQUS_PUBLIC_KEY = $config["api_key"];
            self::$DISQUS_SECRET_KEY = $config["api_secret"];
            self::$SHORTNAME = $config["shortname"];
        }

        public static function dsq_hmacsha1($data, $key)
        {
            $blocksize = 64;
            $hashfunc = 'sha1';
            if (strlen($key) > $blocksize)
                $key = pack('H*', $hashfunc($key));
            $key = str_pad($key, $blocksize, chr(0x00));
            $ipad = str_repeat(chr(0x36), $blocksize);
            $opad = str_repeat(chr(0x5c), $blocksize);
            $hmac = pack(
                'H*', $hashfunc(
                    ($key ^ $opad) . pack(
                        'H*', $hashfunc(
                            ($key ^ $ipad) . $data
                        )
                    )
                )
            );
            return bin2hex($hmac);
        }
        public static function auth($user_id, $penname, $email)
        {
            $_SESSION['disqus_user_id'] = $user_id;
            $_SESSION['disqus_penname'] = $penname;
            $_SESSION['disqus_email'] = $email;
        }


        public static function config()
        {
            $data = array(
                "id" => isset($_SESSION['disqus_user_id']) ? $_SESSION['disqus_user_id'] : -1 ,
                "username" => isset($_SESSION['disqus_penname']) ? $_SESSION['disqus_penname'] : "guest",
                "email" => isset($_SESSION['disqus_email']) ? $_SESSION['disqus_email'] : "guest@guest"
            );

            $message = base64_encode(json_encode($data));
            $timestamp = time();

            return array(
                "message" => $message,
                "timestamp" => $timestamp,
                "hmac" => self::dsq_hmacsha1($message . ' ' . $timestamp, self::$DISQUS_SECRET_KEY),
                "api_key" => self::$DISQUS_PUBLIC_KEY,
                "api_secret" => self::$DISQUS_SECRET_KEY,
                "shortname" => self::$SHORTNAME
            );
        }


    }

    Disqus::rx_setup();
}


