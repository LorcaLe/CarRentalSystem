<?php

require_once "../core/Controller.php";

class PaymentController extends Controller {

    public function checkout(){

        if($_SERVER["REQUEST_METHOD"] == "POST"){

            echo "Payment successful";

        }

        $this->view("payment/checkout");

    }

}