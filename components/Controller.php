<?php


namespace app\components;


class Controller {
    /**
     * Renders a view file as a PHP script.
     *
     * This method treats the view file as a PHP script and includes the file.
     * It extracts the given parameters and makes them available in the view file.
     * The method captures the output of the included view file and returns it as a string.
     *
     *
     * @param string $_file_ the view file.
     * @param array $_params_ the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @return string the rendering result
     * @throws \Exception
     */
    public function render($_file_, $_params_ = [])
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            require '../views/' . $_file_ . '.php';
            $params['content'] = ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if ( !@ob_end_clean() ) {
                    ob_clean();
                }
            }
            throw $e;
        }

        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        try {
            require '../views/layout/main.php';
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if ( !@ob_end_clean() ) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}