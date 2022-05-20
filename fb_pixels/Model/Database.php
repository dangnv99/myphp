<?php
class Database
{
    protected $connection = null;

    /**
     * Connect Database
     */
    public function __construct()
    {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);

            if (mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * select
     */
    public function select($query = "", $params = [])
    {
        try {
            // echo json_encode($query) . "\n\r";
            $stmt = $this->executeStatement($query, $params);
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            //var_dump($result);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return false;
    }
    /**
     * count
     */
    public function count()
    {
        try {
            $link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
            $result = mysqli_query($link, "SELECT count(id) FROM fb_pixels");
            $num_rows = mysqli_fetch_row($result)[0];
            return $num_rows;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return false;
    }



    /**
     * delete
     */
    public function delete($query = "", $params = [])
    {
        try {
            $stmt = $this->Statement($query);
            $affectRows = $stmt->affected_rows;
            return $affectRows > 0;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return false;
    }


    /**
     * create
     */
    public function create($query = "")
    {
        try {
            $stmt = $this->Statement($query);
            //$affectRows = $stmt->affected_rows;
            //echo mysqli_insert_id($this->connection);
            //die();
            return mysqli_insert_id($this->connection);
            //return $affectRows > 0;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return false;
    }

    /**
     * update
     */
    public function update($query = "")
    {
        try {
            $stmt = $this->Statement($query);
            $affectRows = $stmt->affected_rows;
            return $affectRows > 0;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return false;
    }


    /**
     * query params
     */
    private function executeStatement($query = "", $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);

            if ($stmt === false) {
                throw new Exception("Unable to do prepared statement: " . $query);
            }

            if ($params) {
                $stmt->bind_param($params[0], $params[1]);
            }

            $stmt->execute();

            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * query no params
     */
    private function Statement($query = "")
    {
        //echo json_encode($query) . "\n\r";
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to do prepared statement: " . $query);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
