<?php

namespace app\service {

    class Disqus
    {
        public static $DISQUS_SECRET_KEY = false;
        public static $DISQUS_PUBLIC_KEY = false;
        public static $SHORTNAME = false;
        public static $MESSAGE = null;
        public static $TIMESTAMP = null;
        public static $HMAC = null;
        public static $CREDITS = null;
        public static $CONFIG_JS_FILE = "nojs.js";


        public static function rx_setup()
        {
            $config = \Config::getSection("DISQUS_CONFIG");
            self::$DISQUS_PUBLIC_KEY = $config["api_key"];
            self::$DISQUS_SECRET_KEY = $config["api_secret"];
            self::$SHORTNAME = $config["shortname"];
            self::define_vars();
        }

        public static function dsq_hmacsha1($data, $key)
        {
            $blocksize=64;
            $hashfunc='sha1';
            if (strlen($key)>$blocksize)
                $key=pack('H*', $hashfunc($key));
            $key=str_pad($key,$blocksize,chr(0x00));
            $ipad=str_repeat(chr(0x36),$blocksize);
            $opad=str_repeat(chr(0x5c),$blocksize);
            $hmac = pack(
                        'H*',$hashfunc(
                            ($key^$opad).pack(
                                'H*',$hashfunc(
                                    ($key^$ipad).$data
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

            //echo "====".$_SESSION['disqus_user_id']."==".$_SESSION['disqus_penname']."===". $_SESSION['disqus_email'];

            $data = array(
                "id" => isset($_SESSION['disqus_user_id']) ? $_SESSION['disqus_user_id'] : -1,
                "username" => isset($_SESSION['disqus_penname']) ? $_SESSION['disqus_penname'] : "guest",
                "email" => isset($_SESSION['disqus_email']) ? $_SESSION['disqus_email'] : "guest@guest"
            );
            $_SESSION['disqus_MESSAGE'] = base64_encode(json_encode($data));
            $_SESSION['disqus_TIMESTAMP'] = time();
            $_SESSION['disqus_HMAC'] = self::dsq_hmacsha1($_SESSION['disqus_MESSAGE'] . ' ' . $_SESSION['disqus_TIMESTAMP'], self::$DISQUS_SECRET_KEY);
            self::define_vars();
        }

        public static function define_vars()
        {
            self::$CREDITS = isset($_SESSION['disqus_user_id']) ? ($_SESSION['disqus_user_id']."=".$_SESSION['disqus_penname']."=". $_SESSION['disqus_email']) : "disqus_MESSAGE";
            self::$MESSAGE = isset($_SESSION['disqus_MESSAGE']) ? $_SESSION['disqus_MESSAGE'] : "disqus_MESSAGE";
            self::$TIMESTAMP = isset($_SESSION['disqus_TIMESTAMP']) ? $_SESSION['disqus_TIMESTAMP'] : "disqus_TIMESTAMP";
            self::$HMAC = isset($_SESSION['disqus_HMAC']) ? $_SESSION['disqus_HMAC'] : "disqus_HMAC";
            self::$CONFIG_JS_FILE = "api/disqus/".self::$HMAC."/config.js";
        }


        public static function config()
        {
            return array(
                "CREDITS" => self::$CREDITS,
                "message" => self::$MESSAGE,
                "timestamp" => self::$TIMESTAMP,
                "hmac" => self::$HMAC,
                "api_key" => self::$DISQUS_PUBLIC_KEY,
                "api_secret" => self::$DISQUS_SECRET_KEY,
                "shortname" => self::$SHORTNAME
            );
        }
    }

    Disqus::rx_setup();
}


