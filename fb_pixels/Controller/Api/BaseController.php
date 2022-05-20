<?php
class BaseController
{
    /**
     * __call magic method.
     */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    /**
     * Get URI elements.
     */
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode('/', $uri);
        return $uri;
    }


    /**
     * Send Error.
     */
    protected function sendOutput($data, $httpHeaders = array())
    {
        // header_remove('Set-Cookie');

        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
        //echo $data;
        exit;
    }


    function responseHandler($code = 200, $status = 'success', $payload = null, $meta = null)
    {
        http_response_code($code);
        $response = [
            "code" => $code,
            "status" => $status,
        ];
        //var_dump($payload);
        if (isset($payload)) {
            foreach ($payload as $key => $value) {
                if ($status === 'success') {
                    $response['data'][$key] = $value;
                } else {
                    $response['error'][$key] =  $value;
                }
            }
        }
        if (isset($response['error'])) {
            $response['exception']  = "List array errors";
        }
        if (isset($meta)) {
            foreach ($meta as $key => $value) {
                $response['meta'][$key] =  $value;
            }
        }

        echo json_encode($response);
    }

    function responseMeta($intLimit, $count, $current_page)
    {
        $object = new stdClass();

        $total_pages = ceil($count / $intLimit);
        if ($current_page >= $total_pages) {
            $current_page = $total_pages;
            $has_next = false;
            $has_pre = true;
        } else if ($current_page < 1) {
            $current_page = 1;
            $has_next = true;
            $has_pre = false;
        } elseif ($current_page > 0 && $current_page < $total_pages) {
            $has_next = true;
            $has_pre = true;
        }
        if ($current_page = 1 && $current_page = 1) {
            $has_next = false;
            $has_pre = false;
        }
        $object->total = $count;
        $object->per_page = $intLimit;
        $object->current_page = $current_page;
        $object->total_pages = $total_pages;
        $object->has_next = $has_next;
        $object->has_pre = $has_pre;
        return  $object;
    }
    protected function checkData($data, $option)
    {
        $check = 0;
        $object = new stdClass();
        // echo $option . "\n\r";
        //echo $data;
        foreach ($data as $key => $val) {
            if ($option === 1) {
                //echo 1 . "\n\r";
                if (!isset($_POST['shop']) || ($key == 'shop' && ($val == null || empty($val) || $val === ''))) {
                    //$data['shop']   = "The Shop field is required!";
                    $errors_shop = array();
                    $errors_shop = array_merge($errors_shop, array('The Shop field is required!'));
                    $object->shop = $errors_shop;
                    //echo array_values($data)[$n] . "\n\r";
                }
                if (!isset($_POST['pixel_id']) || ($key == 'pixel_id' && ($val == null || empty($val) || $val === ''))) {
                    $errors_pixel_id = array();
                    $errors_pixel_id = array_merge($errors_pixel_id, array('The Shop field is required!'));
                    $object->pixel_id = $errors_pixel_id;
                }
                if (!isset($_POST['pixel_title']) || ($key == 'pixel_title' && ($val == null || empty($val) || $val === ''))) {

                    $errors_pixel_title = array();
                    $errors_pixel_title = array_merge($errors_pixel_title, array('The Shop field is required!'));
                    $object->pixel_title = $errors_pixel_title;
                }
                if (!isset($_POST['access_token']) || ($key == 'access_token' && ($val == null || empty($val) || $val === ''))) {
                    $errors_access_token = array();
                    $errors_access_token = array_merge($errors_access_token, array('The Shop field is required!'));
                    $object->pixel_title = $errors_access_token;
                }
            }

            if ($option === 0) {
                if (!isset($_POST['id']) || ($key == 'id' && ($val == null || empty($val) || $val === ''))) {

                    $errors_shop = array();
                    $errors_shop = array_merge($errors_shop, array('The Shop field is required!'));
                    $object->id = $errors_shop;
                }
            }
            if ($option === 2) {
                if (!isset($_GET['current_page']) || ($key == 'current_page' && ($val == null || empty($val) || $val === ''))) {
                    $errors_current_page = array();
                    $errors_current_page = array_merge($errors_current_page, array('The Shop field is required!'));
                    $object->current_page = $errors_current_page;
                }
                if (!isset($_GET['limit']) || ($key == 'limit' && ($val == null || empty($val) || $val === ''))) {
                    $errors_limit = array();
                    $errors_limit = array_merge($errors_limit, array('The Shop field is required!'));
                    $object->limit = $errors_limit;
                }
            }

            if ($option === 3) {
                if (!isset($_GET['shop']) || ($key == 'shop' && ($val == null || empty($val) || $val === ''))) {
                    $errors_shop = array();
                    $errors_shop = array_merge($errors_shop, array('The Shop field is required!'));
                    $object->current_page = $errors_shop;
                }
                if (!isset($_GET['pixel_id']) || ($key == 'pixel_id' && ($val == null || empty($val) || $val === ''))) {
                    $errors_pixel_id = array();
                    $errors_pixel_id = array_merge($errors_pixel_id, array('The Shop field is required!'));
                    $object->pixel_id = $errors_pixel_id;
                }
            }
            if ($option === 4) {
                //echo $option . "\n\r";
                if (!isset($_POST['shop']) || ($key == 'shop' && ($val == null || empty($val) || $val === ''))) {
                    $errors_shop = array();
                    $errors_shop = array_merge($errors_shop, array('The Shop field is required!'));
                    $object->current_page = $errors_shop;
                }
                if (!isset($_POST['pixel_id']) || ($key == 'pixel_id' && ($val == null || empty($val) || $val === ''))) {
                    $errors_pixel_id = array();
                    $errors_pixel_id = array_merge($errors_pixel_id, array('The Shop field is required!'));
                    $object->pixel_id = $errors_pixel_id;
                }
            }
        }
        return $object;
    }
}
