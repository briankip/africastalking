<?php
namespace App\Libraries;
/*

  # COPYRIGHT (C) 2014 AFRICASTALKING LTD <www.africastalking.com>

  AFRICAStALKING SMS GATEWAY CLASS IS A FREE SOFTWARE IE. CAN BE MODIFIED AND/OR REDISTRIBUTED
  UNDER THE TERMS OF GNU GENERAL PUBLIC LICENCES AS PUBLISHED BY THE
  FREE SOFTWARE FOUNDATION VERSION 3 OR ANY LATER VERSION

  THE CLASS IS DISTRIBUTED ON 'AS IS' BASIS WITHOUT ANY WARRANTY, INCLUDING BUT NOT LIMITED TO
  THE IMPLIED WARRANTY OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
  IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
  WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
  OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

class AfricasTalkingGatewayException extends \Exception{}

class AfricasTalkingClient
{
  protected $_username;
  protected $_apiKey;

  protected $_requestBody;
  protected $_requestUrl;

  protected $_responseBody;
  protected $_responseInfo;

  //Turn this on if you run into problems. It will print the raw HTTP response from our server
  const Debug             = false;

  const HTTP_CODE_OK      = 200;
  const HTTP_CODE_CREATED = 201;

  public function __construct($username_, $apiKey_, $environment_ = "production")
  {
    $this->_username     = $username_;
    $this->_apiKey       = $apiKey_;

    $this->_environment  = $environment_;

    $this->_requestBody  = null;
    $this->_requestUrl   = null;

    $this->_responseBody = null;
    $this->_responseInfo = null;
  }


  //Messaging methods
  public function sendMessage($to_, $message_, $from_ = null, $bulkSMSMode_ = 1, Array $options_ = array())
  {
    if ( strlen($to_) == 0 || strlen($message_) == 0 ) {
      throw new AfricasTalkingGatewayException('Please supply both to and message parameters');
    }

    $params = array(
            'username' => $this->_username,
            'to'       => $to_,
            'message'  => $message_,
            );

    if ( $from_ !== null ) {
      $params['from']        = $from_;
      $params['bulkSMSMode'] = $bulkSMSMode_;
    }

    //This contains a list of parameters that can be passed in $options_ parameter
    if ( count($options_) > 0 ) {
      $allowedKeys = array (
                'enqueue',
                'keyword',
                'linkId',
                'retryDurationInHours'
                );

      //Check whether data has been passed in options_ parameter
      foreach ( $options_ as $key => $value ) {
    if ( in_array($key, $allowedKeys) && strlen($value) > 0 ) {
      $params[$key] = $value;
    } else {
      throw new AfricasTalkingGatewayException("Invalid key in options array: [$key]");
    }
      }
    }

    $this->_requestUrl  = $this->getSendSmsUrl();
    $this->_requestBody = http_build_query($params, '', '&');

    $this->executePOST();

    if ( $this->_responseInfo['http_code'] == self::HTTP_CODE_CREATED ) {
      $responseObject = json_decode($this->_responseBody);
      if(count($responseObject->SMSMessageData->Recipients) > 0)
    return $responseObject->SMSMessageData->Recipients;

      throw new AfricasTalkingGatewayException($responseObject->SMSMessageData->Message);
    }

    throw new AfricasTalkingGatewayException($this->_responseBody);
  }

  private function getApiHost() {
    return ($this->_environment == 'sandbox') ? 'https://api.sandbox.africastalking.com' : 'https://api.africastalking.com';
  }

  private function getVoiceHost() {
    return ($this->_environment == 'sandbox') ? 'https://voice.sandbox.africastalking.com' : 'https://voice.africastalking.com';
  }

  private function getSendSmsUrl($extension_ = "") {
    return $this->getApiHost().'/version1/messaging'.$extension_;
  }
}
