<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class PixelModel extends Database
{
    // Get list
    public function getPixels($limit)
    {
        //echo $limit;
        return $this->select("SELECT * FROM fb_pixels ORDER BY id ASC LIMIT ?", ["i", $limit]);
    }

    // Get cout total rows
    public function getCount()
    {
        return $this->count();
    }
    // Get Detail 
    public function getDetail($shop, $pixel_id)
    {

        return $this->select("SELECT * FROM fb_pixels where shop  = $shop && pixel_id = $pixel_id");;
    }

    //get Return
    public function getReturn($id)
    {
        return $this->select("SELECT * FROM fb_pixels where id = $id");
    }

    // Post Delete
    public function postDelete($shop, $pixel_id)
    {
        return $this->delete("DELETE FROM fb_pixels where shop = '$shop' && pixel_id = '$pixel_id'");; //pixel_id

    }

    // Post Create
    public function postCreate($datakey, $dataValn)
    {
        return $this->create("INSERT INTO fb_pixels ($datakey) VALUES ($dataValn)");;
    }

    //Post Update
    public function postUpdate($id, $data)
    {
        return $this->update("UPDATE  fb_pixels SET $data where id = $id");;
    }
}
