<?php
// Limit = per page
/**
 * http://localhost/myphp/fb_pixels/index.php/list
 */
class UserController extends BaseController
{
    /**
     * "/fb_pixels/index.php/list"
     */
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        //$arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
                $intLimit = 0;
                if (isset($_GET['limit'])) {
                    $intLimit = $_GET['limit'];
                }
                $current_page = 0;
                if (isset($_GET['current_page'])) {
                    $current_page = $_GET['current_page'];
                }
                $count = $userModel->getCount();
                if ($intLimit <= 0) {
                    $intLimit = $count;
                }
                if (isset($_GET['limit']) && isset($_GET['current_page'])) {
                    $meta = $this->responseMeta($intLimit, $count, $current_page);
                }
                //echo  $count;
                //die();
                $arrUsers = $userModel->getPixels($intLimit);
                //var_dump($arrUsers);
                //$responseData = json_encode($arrUsers);
                if (count((array)$arrUsers) > 0) {
                    $this->responseHandler($code = 200, $status = 'success', $arrUsers, $meta);
                } else {
                    $this->responseHandler($code = 422, $status = 'error', $arrUsers);
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output if Error function 
        if (!$strErrorDesc) {
            $this->sendOutput(
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }


    /** 
     * "/fb_pixels/index.php/detail"
     */
    public function detailAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
                if (isset($_GET['shop']) && $_GET['pixel_id']) {
                    $arrUsers = $userModel->getDetail($_GET['shop'], $_GET['pixel_id']);
                }

                if (count((array)$arrUsers) > 0) {
                    $this->responseHandler($code = 200, $status = 'success', $arrUsers);
                } else {
                    $this->responseHandler($code = 422, $status = 'error', $arrUsers);
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output if Error function 
        if (!$strErrorDesc) {
            $this->sendOutput(
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }



    /** 
     * "/fb_pixels/index.php/delete"
     */
    public function deleteAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'POST') {
            try {
                $userModel = new UserModel();
                $arrUsers = $userModel->postDelete($_GET['shop'], $_GET['pixel_id']); //pixel_id
                if ($arrUsers) {
                    $object = new stdClass();
                    $object->pixel_id = $_GET['pixel_id'];
                    $this->responseHandler($code = 200, $status = 'success', $object);
                } else {
                    $object = new stdClass();
                    $object->pixel_id = "The Shop field is required!";
                    $object->shop = "The Shop field is required!";

                    $this->responseHandler($code = 422, $status = 'error', $object);
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output if Error function 
        if (!$strErrorDesc) {
            $this->sendOutput(
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }


    //
    /** 
     * "/fb_pixels/index.php/create"
     */
    public function createAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
                $userModel = new UserModel();
                $id =  mt_rand(10000000, 999999999);
                //echo $id . "\n\r";
                // $shop = isset($_POST['shop']) ? $_POST['shop'] : null;
                // $pixel_id = isset($_POST['pixel_id']) ? $_POST['pixel_id'] : null;
                // $pixel_title = isset($_POST['pixel_title']) ? $_POST['pixel_title'] : null;
                // $status = isset($_POST['status']) ? $_POST['status'] : 0;
                // $is_master = isset($_POST['is_master']) ? $_POST['is_master'] : 0;
                // $is_conversion_api = isset($_POST['is_conversion_api']) ? $_POST['is_conversion_api'] : 0;
                // $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
                // // $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : "";
                // // $updated_at = isset($_POST['updated_at']) ? $_POST['updated_at'] : "";
                $setData = array_values($_POST);

                foreach ($setData as $key => $val) {

                    if ($val == null) {
                    } else if (is_numeric($val)) {
                        $setData[$key] = $val;
                    } elseif ($val == 'true' || $val == 'false') {
                        $setData[$key] = $val;
                    } else {
                        $setData[$key] = "'" . $val . "'";
                        //$val == null || empty($val) || $val === ''
                    }
                    //echo $val . "\n\r";
                }

                $datakey = implode(",", array_keys($_POST));
                //echo $datakey . "\n\r";

                $dataVal = implode(",", $setData);
                //echo $dataVal . "\n\r";
                //die();
                $check = $this->checkData($_POST, 1);

                if (count((array)$check)  == 0) {

                    $arrUsers = $userModel->postCreate($datakey, $dataVal);

                    if ($arrUsers > 0) {
                        $firstItem = array('id' => $arrUsers);
                        //$_POST = $firstItem + $_POST;
                        $arrdata = $userModel->getReturn($arrUsers);
                        $this->responseHandler($code = 200, $status = 'success', $arrdata); //Post
                    } else {
                        echo "Error deleting record: " . $userModel->error;
                    }
                } else {
                    $this->responseHandler($code = 422, $status = 'error', $check);
                }
                //echo $id;
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong!.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output if Error function 
        if (!$strErrorDesc) {
            $this->sendOutput(
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }


    /** 
     * "/fb_pixels/index.php/update"
     */
    public function updateAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'POST') {
            try {
                $userModel = new UserModel();
                //$datakey = implode(",", array_keys($_POST));
                //$dataVal = implode(",", array_values($_POST));

                $check = $this->checkData($_POST, 0);

                if (count((array)$check)  == 0) {
                    $push = "";
                    foreach ($_POST as $key => $val) {
                        if ($val == null) {
                        } else if (is_numeric($val)) {
                            $val = $val;
                        } elseif ($val == 'true' || $val == 'false') {
                            $val = $val;
                        } else {
                            $val = "'" . $val . "'";
                        }
                        if ($key != 'id') {
                            $push .= $key . "=" . $val . ",";
                        }
                        // echo $val . "\n\r";
                    };
                    $arrUsers = $userModel->postUpdate($_POST['id'], rtrim($push, ","));
                    if ($arrUsers) {
                        $arrdata = $userModel->getReturn($_POST['id']);
                        $this->responseHandler($code = 200, $status = 'success', $arrdata);
                    } else {
                        echo "Error deleting record: " . $userModel->error;
                    }
                } else {
                    $this->responseHandler($code = 422, $status = 'error', $check);
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output if Error function 
        if (!$strErrorDesc) {
            $this->sendOutput(
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}
