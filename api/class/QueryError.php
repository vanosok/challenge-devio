<?php
class QueryError
{
    public static function Show(Exception $e)
    {
        if (DEBUG_QUERY) {

            echo '<pre>';
            print_r($e); //$e->errorInfo[2]
            echo '</pre>';
        } else {
            if (DEBUG_PHP) {
                echo $e->getMessage();
            }
        }

        die();
    }
}
