<?php
use App\Models\CentralVariable;
use Illuminate\Support\Facades\Http;

class PaymentHelper
{
    protected $secret_key;

    public function __construct()
    {
        $this->secret_key = CentralVariable::getVar('SECRET_KEY');
    }

    public function RedirectWithPostForm(array $data,$url)
    {
        $fullData = $data;
        ?>
       <html xmlns="http://www.w3.org/1999/xhtml">
           <head>
               <script type="text/javascript">
                   function closethisasap() {
                       document.forms["redirectpost"].submit();
                   }
               </script>
           </head>
           <body onload="closethisasap();">
               <form name="redirectpost" method="post" action="<?PHP echo $url; ?>">
                   <?php
if (!is_null($fullData)) {
            foreach ($fullData as $k => $v) {
                echo '<input type="hidden" name="' . $k . '" value="' . $v . '"> ';
            }
        }
        ?>
               </form>
           </body>
       </html>
       <?php
exit;
    }

    public function hostedPayment($urlData)
    {
        $params = array(
            'ivp_method' => 'create',
            'ivp_store' => '27696',
            'ivp_authkey' => 'xH3kw-qW4W@mxSVp',
            'ivp_test' => '0',
            'ivp_cart' => $urlData['order_id'],
            'ivp_amount' => $urlData['amount'],
            'ivp_currency' => 'SAR',
            'ivp_desc' => 'Whatsky Purchase Process',
            'return_auth' => $urlData['return_auth'],
            'return_can' => $urlData['return_can'],
            'return_decl' => $urlData['return_decl'],
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.telr.com/gateway/order.json");
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        $results = curl_exec($ch);
        curl_close($ch);
        $results = json_decode($results,true);
        
        return $results;
    }

    public function checkOrder($urlData)
    {
        $params = array(
            'ivp_method' => 'check',
            'ivp_store' => '27696',
            'ivp_authkey' => 'xH3kw-qW4W@mxSVp',
            'order_ref' => $urlData['order_id'],
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.telr.com/gateway/order.json");
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        $results = curl_exec($ch);
        curl_close($ch);
        $results = json_decode($results,true);
        
        return $results;
    }

}
