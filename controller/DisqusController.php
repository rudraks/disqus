<?php

namespace app\controller {


    use app\service\Disqus;

    class DisqusController extends AbstractController
    {
        /**
         * @RequestMapping(url="api/disqus/{hmac}/config.js{counter}",type="template")
         * @RequestParams(true)
         */
        public function cloudinary_configjs($model, $hmac,$counter=1)
        {
            header("Content-type: text/javascript");
            \app\service\Smarty::setTemplateDir("/../view/");

            echo "//=|".Disqus::$HMAC.$_SESSION['disqus_penname'];

            if ($hmac == Disqus::$HMAC) {
                $model->assign("disqus_config", Disqus::config());
                return "js";
            } 
            if($counter!=2){
               return "js2";
            }
            //$this->header("HTTP/1.0","404 Not Found");
            exit();
        }

        /**
         * @RequestMapping(url="api/disqus/config.js",type="template")
         * @RequestParams(true)
         */
        public function cloudinary_configjs_old($model)
        {
            header("Content-type: text/javascript");
            \app\service\Smarty::setTemplateDir("/../view/");
            $model->assign("disqus_config", Disqus::config());
            return "js";
        }
    }
}
