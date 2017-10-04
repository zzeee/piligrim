<?php
/**
 * Created by PhpStorm.
 * User: леново
 * Date: 12.08.2017
 * Time: 13:15
 */




class Log {

    private $settings;

    function __construct($settings) {
        $this->settings = $settings;
    }

    public function info($str) {
        if(is_array($str) || is_object($str)) {
            $str = print_r($str,true);
        }
        $str = $str . "\n";
        file_put_contents($this->settings->LOG_FILE, '[' . date("Y-m-d H:i:s") . '] ' . $str, FILE_APPEND);
    }
}


class Utils {

    public static function formatDate(\DateTime $date) {
        $performedDatetime = $date->format("Y-m-d") . "T" . $date->format("H:i:s") . ".000" . $date->format("P");
        return $performedDatetime;
    }

    public static function formatDateForMWS(\DateTime $date) {
        $performedDatetime = $date->format("Y-m-d") . "T" . $date->format("H:i:s") . ".000Z";
        return $performedDatetime;
    }
}

/**
 * The implementation of payment notification methods.
 */
class YaMoneyCommonHttpProtocol {

    private $action;
    private $settings;
    private $log;

    public function __construct($action, Settings $settings, $testmode) {
        $this->action = $action;
        $this->testmode=$testmode;
        $this->settings = $settings;
        $this->log = new Log($settings);
    }

    /**
     * Processes "checkOrder" and "paymentAviso" requests.
     * @param array $request payment parameters
     */
    public function processRequest($req) {
        $request=$req;
        if (!isset($request["invoiceId"])) $request["invoiceId"]="";
        main::logVar("REQUEST {$this->action}".json_encode($request));

        //$this->log("Start " . $this->action);
        //$this->log("Security type " . $this->settings->SECURITY_TYPE);
        if ($this->settings->SECURITY_TYPE == "MD5") {
          //  $this->log("Request: " . print_r($request, true));
            // If the MD5 checking fails, respond with "1" error code
            main::logVar('MD5');
            if (!$this->checkMD5($req)) {

                $response = $this->buildResponse($this->action, $request['invoiceId'], 1);
                $this->sendResponse($response);
            }
        } else if ($this->settings->SECURITY_TYPE == "PKCS7") {
            //main::logVar('PKCS');
            // Checking for a certificate sign. If the checking fails, respond with "200" error code.
            if (($request = $this->verifySign()) == null) {
                $response = $this->buildResponse($this->action, null, 200);
                $this->sendResponse($response);
            }
            $this->log("Request: " . print_r($request, true));
        }
        $response = null;
        //main::logVar("CHECKI".$this->action);
        if ($this->action == 'checkOrder') {
            $response = $this->checkOrder($request);
        } else {
            $response = $this->paymentAviso($request);
        }
        $this->sendResponse($response);
    }

    /**
     * CheckOrder request processing. We suppose there are no item with price less
     * than 100 rubles in the shop.
     * @param  array $request payment parameters
     * @return string         prepared XML response
     */
    private function checkOrder($request) {
        //var_dump($request);
        main::logVar("YANDEX:CHECKORDER");
        main::logVar(json_encode($request));
        $response = null;

        if (!isset($request["orderSumBankPaycash"])) $request["orderSumBankPaycash"]="";
        if (!isset($request["orderSumCurrencyPaycash"])) $request["orderSumCurrencyPaycash"]="";
        if (!isset($request["invoiceId"])) $request["invoiceId"]="";
        if (!isset($request["orderSumAmount "])) $request["orderSumAmount "]="";
        if (!isset($request["customerNumber"])) $request["customerNumber"]="";
        if (!isset($request["orderNumber"])) $request["orderNumber"]="";
        if (!isset($request["paymentType"])) $request["paymentType"]="";

        if ($request['orderSumAmount'] < 1) {
            $response = $this->buildResponse($this->action, $request['invoiceId'], 100, "The amount should be more than 1 rubles.");
        } else {
            main::logVar("CHECK_INS");

            $sq="insert into yandex_pay(invoice_id, orderSumAmount, customerNumber, orderNumber, paymentType, requestDatetime, type,test) 
values (${request["invoiceId"]},${request["orderSumAmount"]},${request["customerNumber"]},${request["orderNumber"]},'${request["paymentType"]}','".date_format(new DateTime($request["requestDatetime"]),"Y-m-d H:m")."',0, $this->testmode)";
            main::logVar($sq);
            db::query2($sq);
            $response = $this->buildResponse($this->action, $request['invoiceId'], 0);
        }

        return $response;
    }

    /**
     * PaymentAviso request processing.
     * @param  array $request payment parameters
     * @return string prepared response in XML format
     */
    private function paymentAviso($request) {
        //main::logVar("AVISO");
        main::logVar(json_encode($request));

        if (!isset($request["orderSumBankPaycash"])) $request["orderSumBankPaycash"]="";
        if (!isset($request["orderSumCurrencyPaycash"])) $request["orderSumCurrencyPaycash"]="";
        if (!isset($request["invoiceId"])) $request["invoiceId"]="";
        if (!isset($request["orderSumAmount "])) $request["orderSumAmount "]="";
        if (!isset($request["customerNumber"])) $request["customerNumber"]="";
        if (!isset($request["orderNumber"])) $request["orderNumber"]="";
        if (!isset($request["paymentType"])) $request["paymentType"]="";

        $sq="insert into yandex_pay(invoice_id, orderSumAmount, customerNumber, orderNumber, paymentType, requestDatetime, type,test) 
values (${request["invoiceId"]},${request["orderSumAmount"]},${request["customerNumber"]},${request["orderNumber"]},'${request["paymentType"]}','".date_format(new DateTime($request["requestDatetime"]),"Y-m-d H:m")."',1, $this->testmode)";

        //if ($this->testmode!=1)        {
            db::query2("update orders set payment_status=1 where id=".$request["orderNumber"]);
        //}


        main::logVar($sq);
        db::query2($sq);

        return $this->buildResponse($this->action, $request['invoiceId'], 0);
    }

    /**
     * Checking the MD5 sign.
     * @param  array $request payment parameters
     * @return bool true if MD5 hash is correct
     */
    private function checkMD5($req) {
        $request=$req;
        //return true;
      /*  if (!isset($request["invoiceId"])) $request["invoiceId"]="";

        if (!isset($request["action"])) $request["action"]="";
        if (!isset($request["orderSumAmount "])) $request["orderSumAmount "]="";
        if (!isset($request["orderSumBankPaycash"])) $request["orderSumBankPaycash"]="";
        if (!isset($request["orderSumAmount"])) $request["orderSumAmount"]="";
        if (!isset($request["orderSumCurrencyPaycash"])) $request["orderSumCurrencyPaycash"]="";
        if (!isset($request["customerNumber"])) $request["customerNumber"]="";
        if (!isset($request["shopId"])) $request["shopId"]="";
        if (!isset($request["action"])) $request["action"]="";
        if (!isset($request["md5"])) $request["md5"]="";
        //if (!isset($request["action"])) $request["action"]="";*/
        $str = $request['action'] . ";" . $request['orderSumAmount'] . ";" . $request['orderSumCurrencyPaycash'] . ";" .$request['orderSumBankPaycash'] . ";" . $request['shopId'] . ";" .$request['invoiceId'] . ";" . trim($request['customerNumber']) . ";" . $this->settings->SHOP_PASSWORD;
        $this->log("String to md5: " . $str);
        $md5 = strtoupper(md5($str));
        main::logVar("String to md5:" . $str." md5:".$md5);

        if ($md5 != strtoupper($request['md5'])) {
            $this->log("Wait for md5:" . $md5 . ", recieved md5: " . $request['md5']);
            return false;
        }
        return true;
    }

    /**
     * Building XML response.
     * @param  string $functionName  "checkOrder" or "paymentAviso" string
     * @param  string $invoiceId     transaction number
     * @param  string $result_code   result code
     * @param  string $message       error message. May be null.
     * @return string                prepared XML response
     */
    private function buildResponse($functionName, $invoiceId, $result_code, $message = null) {
        try {
            $performedDatetime = Utils::formatDate(new DateTime());
            $response = '<?xml version="1.0" encoding="UTF-8"?><' . $functionName . 'Response performedDatetime="' . $performedDatetime .
                '" code="' . $result_code . '" ' . ($message != null ? 'message="' . $message . '"' : "") . ' invoiceId="' . $invoiceId . '" shopId="' . $this->settings->SHOP_ID . '"/>';
            return $response;
        } catch (\Exception $e) {
            $this->log($e);
        }
        return null;
    }


    /**
     * Checking for sign when XML/PKCS#7 scheme is used.
     * @return array if request is successful, returns key-value array of request params, null otherwise.
     */
    private function verifySign() {
        $descriptorspec = array(0 => array("pipe", "r"), 1 => array("pipe", "w"), 2 => array("pipe", "w"));
        $certificate = 'yamoney.pem';
        $process = proc_open('openssl smime -verify -inform PEM -nointern -certfile ' . $certificate . ' -CAfile ' . $certificate,
            $descriptorspec, $pipes);
        if (is_resource($process)) {
            // Getting data from request body.
            $data = file_get_contents($this->settings->request_source); // "php://input"
            fwrite($pipes[0], $data);
            fclose($pipes[0]);
            $content = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            $resCode = proc_close($process);
            if ($resCode != 0) {
                return null;
            } else {
                $this->log("Row xml: " . $content);
                $xml = simplexml_load_string($content);
                $array = json_decode(json_encode($xml), TRUE);
                return $array["@attributes"];
            }
        }
        return null;
    }

    private function log($str) {
        $this->log->info($str);
    }

    private function sendResponse($responseBody) {
        $this->log("Response: " . $responseBody);
        //header("HTTP/1.0 200");
        //header("Content-Type: application/xml");
        echo $responseBody;
        exit;
    }
}
?>
