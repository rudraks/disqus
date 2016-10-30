<?php

namespace app\controller {


    use app\RX;
    use RudraX\Utils\Webapp;
    use app\service\Disqus;

    class DisqusController extends AbstractController
    {
        /**
         * @RequestMapping(url="api/disqus/config.js",type="template")
         * @RequestParams(true)
         */
        public function cloudinary_configjs($model)
        {
            header("Content-type: text/javascript");
            \app\service\Smarty::setTemplateDir(dirname(__FILE__) . "/../view/");
            $model->assign("disqus_config", Disqus::config());
            return "js";
        }
    }
}
