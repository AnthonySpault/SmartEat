<?php
namespace Model;

use Model\UserManager;
use Model\CartManager;

class OrderManager
{
    private $DBManager;

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new OrderManager();
        return self::$instance;
    }

    private function __construct()
    {
        $this->DBManager = DBManager::getInstance();
    }

    public function checkAddresses($data) {
        if (!is_numeric($data['billing']) || !is_numeric($data['shipping']))
            return 'Action interdite';
        $UserManager = UserManager::getInstance();
        $billing = $UserManager->getAddressById($data['billing']);
        if ($billing['userid'] != $_SESSION['user_id'])
            return 'L\'adresse de facturation ne vous appartient pas.';
        $shipping = $UserManager->getAddressById($data['shipping']);
        if ($shipping['userid'] != $_SESSION['user_id'])
            return 'L\'adresse d\'expedition ne vous appartient pas.';
        $_SESSION['order']['data']['billing'] = $data['billing'];
        $_SESSION['order']['data']['shipping'] = $data['shipping'];
        $_SESSION['order']['step'] = 2;
        return true;

    }

    private function checkTransactionId($id) {
        $check = $this->DBManager->findOneSecure("SELECT * FROM payments WHERE txn_id= :id", ['id' => $id]);
        if ($check !== false)
            return false;
        return true;
    }

    public function validatePayment() {
        $req = 'cmd=_notify-validate';
        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }
        $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        $id_user = $_POST['custom'];
        if (!$fp) {
        // ERREUR HTTP
        } else {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                if (strcmp ($res, "VERIFIED") == 0) {
                    // transaction valide
                }
                else if (strcmp ($res, "INVALID") == 0) {
                    // Transaction invalide
                }
            }
            fclose ($fp);
        }
        if ($payment_status == "Completed") {
            if (checkTransactionId($txn_id)) {
                if ("admin@smarteat.fr" == $receiver_email) {

                    $paymentInsert['txn_id'] = $txn_id;
                    $paymentInsert['amount'] = $payment_amount;
                    $this->DBManager->insert('payments', $paymentInsert);
                    $orderInsert['userid'] = $_SESSION['user_id'];
                    $orderInsert['products'] = '';
                    foreach ($_SESSION['cart'] as $key => $value) {
                        $orderInsert['products'] .= $key.',';
                    }
                    $orderInsert['total'] = $payment_amount;
                    $orderInsert['billing'] = $_SESSION['order']['data']['billing'];
                    $orderInsert['shipping'] = $_SESSION['order']['data']['shipping'];
                    $this->DBManager->insert('orders', $orderInsert);
                }
            }
        }
    }
}
