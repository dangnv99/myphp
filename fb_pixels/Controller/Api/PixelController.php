<?php
// Limit = per page
/**
 * http://localhost/myphp/fb_pixels/index.php/list
 */
class PixelController extends BaseController
{
    /**
     * "/fb_pixels/index.php/list"
     */
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $PixelModel = new PixelModel();
                $current_page = isset($_GET['current_page']) ? $_GET['current_page'] : 1;
                $intLimit = isset($_GET['intLimit']) ? $_GET['intLimit'] : 0;

                $count = $PixelModel->getCount();
                if ($intLimit <= 0) {
                    $intLimit = $count;
                }
                $meta = new stdClass();
                $meta = $this->responseMeta($intLimit, $count, $current_page);

                $arrPixels = $PixelModel->getPixels($intLimit);
                if (count((array)$arrPixels) > 0) {
                    $this->responseHandler(200, 'success', $arrPixels, $meta);
                } else {
                    $this->responseHandler($code = 422, $status = 'error', $arrPixels);
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
                $PixelModel = new PixelModel();
                if (isset($_GET['shop']) && $_GET['pixel_id']) {
                    $arrPixels = $PixelModel->getDetail($_GET['shop'], $_GET['pixel_id']);
                }

                if (count((array)$arrPixels) > 0) {
                    $this->responseHandler($code = 200, $status = 'success', $arrPixels);
                } else {
                    $this->responseHandler($code = 422, $status = 'error', $arrPixels);
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
                $PixelModel = new PixelModel();
                $arrPixels = $PixelModel->postDelete($_GET['shop'], $_GET['pixel_id']); //pixel_id
                if ($arrPixels) {
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
                $PixelModel = new PixelModel();
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

                    $arrPixels = $PixelModel->postCreate($datakey, $dataVal);

                    if ($arrPixels > 0) {
                        $firstItem = array('id' => $arrPixels);
                        //$_POST = $firstItem + $_POST;
                        $arrdata = $PixelModel->getReturn($arrPixels);
                        $this->responseHandler($code = 200, $status = 'success', $arrdata); //Post
                    } else {
                        echo "Error deleting record: " . $PixelModel->error;
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
                $PixelModel = new PixelModel();
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
                    $arrPixels = $PixelModel->postUpdate($_POST['id'], rtrim($push, ","));
                    if ($arrPixels) {
                        $arrdata = $PixelModel->getReturn($_POST['id']);
                        $this->responseHandler($code = 200, $status = 'success', $arrdata);
                    } else {
                        echo "Error deleting record: " . $PixelModel->error;
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
