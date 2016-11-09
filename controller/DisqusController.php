<?php

namespace app\controller {


    use app\service\Disqus;

    class DisqusController extends AbstractController
    {
        /**
         * @RequestMapping(url="api/disqus/{hmac}/config.js",type="template")
         * @RequestParams(true)
         */
        public function cloudinary_configjs($model, $hmac)
        {
            header("Content-type: text/javascript");
            \app\service\Smarty::setTemplateDir(dirname(__FILE__) . "/../view/");
            if ($hmac == Disqus::$HMAC) {
                $model->assign("disqus_config", Disqus::config());
            } else {
                $this->header("HTTP/1.0","404 Not Found");
                exit();
            }
            return "js";
        }

        /**
         * @RequestMapping(url="api/disqus/config.js",type="template")
         * @RequestParams(true)
         */
        public function cloudinary_configjs_old($model)
        {
            header("Content-type: text/javascript");
            \app\service\Smarty::setTemplateDir(dirname(__FILE__) . "/../view/");
            $model->assign("disqus_config", Disqus::config());
            return "js";
        }
    }
}
