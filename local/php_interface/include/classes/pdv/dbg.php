<?
namespace PDV;

class DBG {
    /**
     * @param mixed                 $data
     * @param bool | string | false $die
     * @param string | null         $msg
     * @param string | null         $color
     */
    public function __invoke($data, $die = false, $msg = null, $color = null) {
        self::dbg($data, $die, $msg, $color);
    }

    /**
     * @param  mixed $data
     *
     * @return string
     */
    public function __toString() {
        list($data) = func_get_arg(1);
        return self::debugmessage($data);
    }

    /**
     * @param mixed                 $data
     * @param bool | string | false $die
     * @param string | null         $msg
     * @param string | null         $color
     *
     * @return null
     */
    public static function dbg($data, $die = false, $msg = null, $color = null) {
        if (!is_bool($die)) {
            $msg = $die;
            $die = false;
        }
        if (is_string($msg) && preg_match("/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/", $msg)) {
            $color = $msg;
            $msg   = null;
        }
        if (self::isValidUser()) {
            echo self::debugmessage($data, (!empty($msg) ? $msg : null), $color);
            if ( $die )
                die();
        }
    }

    /**
     * @param mixed                 $data
     * @param bool | string | false $die
     * @param string                $msg
     *
     * @return null
     */
    public static function dbg2EventLog($data, $die = false, $msg = 'DEBUG') {
        if (!is_bool($die)) {
            $msg = $die;
            $die = false;
        }
        $sDebug    = self::_debugmessage($data);
        $oEventLog = new \CEventLog();
        $oEventLog->Add(array(
            "SEVERITY"      => "SECURITY",
            "AUDIT_TYPE_ID" => "DEBUG_MESSAGE",
            "MODULE_ID"     => "DEBUG",
            "ITEM_ID"       => $msg,
            "DESCRIPTION"   => $sDebug
        ));
        if ( $die && self::isValidUser() )
            die();
    }

    /**
     * @param mixed                 $data
     * @param bool | string | false $die
     * @param string                $msg
     *
     * @return null
     */
    public static function dbg2File($data, $die = false, $msg = 'DEBUG') {
        if (!is_bool($die)) {
            $msg = $die;
            $die = false;
        }
        if (!defined('LOG_FILENAME')) {
            define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"] . "/upload/debug.log");
        }
        AddMessage2Log(self::_debugmessage($data), $msg);
        if ($die && self::isValidUser())
            die();
    }

    /**
     * @param  mixed  $data
     * @param  string $header
     * @param  string $color
     *
     * @return string
     */
    public static function debugmessage($data, $header = '', $color = '') {
        if ( $color == null || $color == '' )
            $color = '#008000';
        $dbg = debug_backtrace();
        $sRetStr = "<table style='border:0;color:${color};text-align:left;font-size:12px;border:1px solid ${color};'>";
        if ( strlen($header) > 0 )
            $sRetStr .= "<tr><td>[${header}]</td></tr>";
        $sRetStr .= "<tr><td style='padding:5px 10px;'>" . $dbg[1]["file"] . " (" . $dbg[1]["line"] . ")</td></tr>";
        $sRetStr .= "<tr><td style='padding:5px 10px;'><pre>".self::_debugmessage($data)."</pre></td></tr>";
        $sRetStr .= "</table>";

        return $sRetStr;
    }

    /**
     * @param mixed $data
     *
     * @return string | NULL
     */

    public static function _debugmessage($data) {
        return print_r($data, true);
    }

    /**
     * @return bool
     */
    public static function isValidUser() {
        global $USER;
        if ( $USER->IsAdmin() ){
            return true;
        }
        return false;
    }
}