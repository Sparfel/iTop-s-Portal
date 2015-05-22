<?php 
class Syleps_Controller_Action_Helper_GoBack
 extends Zend_Controller_Action_Helper_Abstract
 {
  /**
  * @todo Check if redirecting to the same domain
  * @param bool $required Throw exception?
  * @param bool $validateDomain
  * @param bool $allowSubdomain
  * @param string $alternative URL to redirect to when validation fails and required = true
  * @param string $anchorParam Request parameter name which holds anchor name (#). Redirect to page fragment is not allowed according to HTTP protocol specification, but browsers do support it
  * @throws Zend_Controller_Action_Exception if no referer is specified and $required == false or $checkdomain is true and domains do not match
  */
  public function direct($required = true, $anchorParam = null, $validateDomain = true, $allowSubdomain = false, $alternative = null)
  {
   $front = Zend_Controller_Front::getInstance();
   $request = $front->getRequest();

   $referer = $request->getPost('http_referer');

   if (empty($referer)) {
    $referer = $request->getServer('HTTP_REFERER');
    if (empty($referer)) {

     $referer = $request->getParam('http_referer');
   

    }
   }

   if (null === $alternative) {
    $alternative = $request->getPost('http_referer');
    if (null === $alternative) {
     $alternative = $request->getParam('http_referer');
    }
   }

   if ($referer) {

    if ($validateDomain) {
     if (!$this->validateDomain($referer, $allowSubdomain)) {
      $this->_exception($alternative);
     }
    }

    if (null != $anchorParam) {
     $referer .= '#' . $request->getParam($anchorParam);
    }

    $redirector = new Zend_Controller_Action_Helper_Redirector();
	$redirector->gotoUrl($referer);
   
   } elseif($required) {
    $this->_exception($alternative);
   }
  }

  /**
   * @throws Zend_Controller_Action_Exception With specified message
   * @param string $message Exception message
   * @param string $alternative
   */
  private function _exception($alternative = null, $message = 'HTTP_REFERER is required.')
  {
   if ($alternative) {
    if (Zend_Uri::check($alternative)) {
     $redirector = new Zend_Controller_Action_Helper_Redirector();
     $redirector->gotoUrl($alternative);
    }
   }

   throw new Zend_Controller_Action_Exception($message);
  }


  /**
  * Check if domain from current url and domain from specified url are the same
  * @param string $url Target url
  * @param string $allowSubdomain false
  */
  public function validateDomain($url, $allowSubdomain = false)
  {
   if (!Zend_Uri::check($url)) {

    return false;
   }

   $currentUri = $this->getCurrentUri();

   $uri = Zend_Uri_Http::fromString($currentUri);
   $currentDomain = $uri->getHost();

   $uri = Zend_Uri_Http::fromString($url);
   $target = $uri->getHost();

   if ($allowSubdomain) {
    // Find second dot from the end
    $pos = strrpos($target, '.');

    if (false !== $pos) {
     $pos = strrpos(substr($target, 0, $pos), '.');

     if (false !== $pos) {
      $target = substr($target, $pos+1);
     }
    }
   }

   if ($target === $currentDomain) {
    return true;
   }

   return false;
  }

  /**
  * @return string Current URL
  */
  public function getCurrentUri()
  {
   $request = $this->getRequest();
   $path = $request->getRequestUri();

   $server = $request->getServer();

   $host = $request->getServer('HTTP_HOST');
   $protocol = $request->getServer('SERVER_PROTOCOL');

   if (!empty($protocol)) {
    $protocol = explode('/', $protocol);
    $protocol = strtolower($protocol[0]);
   }

   if (empty($protocol)) {
    $protocol = 'http';
   }

   $baseUrl = $protocol . '://' . $host . '/';

   $path = trim($path, '/\\');

   $url = $baseUrl . $path;

   return $url;
  }

  /**
   * Like str_replace, but only once
   * @param string $search
   * @param string $replace
   * @param string $subject
   */
  public function replaceOnce($search, $replace, $subject)
  {
   $firstChar = strpos($subject, $search);
   if($firstChar !== false) {
    $beforeStr = substr($subject, 0, $firstChar);
    $afterStr = substr($subject, $firstChar + strlen($search));

    return $beforeStr . $replace . $afterStr;
   } else {

    return $subject;
   }
  }
 }