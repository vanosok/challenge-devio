<?php

class Db
{
    public static function Connect($db = 'projetodevio')
    {
        switch ($db) {
            case 'production':
                $conn = new PDO(
                    DB1_DRIVE .
                        ':host=' . DB1_HOSTNAME .
                        ';port=' . DB1_PORT .
                        ';dbname=' . DB1_DATABASE .
                        ';user=' . DB1_USERNAME .
                        ';password=' . DB1_PASSWORD
                );
                break;
            case 'devel':
                $conn = new PDO(
                    DB2_DRIVE .
                        ':host=' . DB2_HOSTNAME .
                        ';port=' . DB2_PORT .
                        ';dbname=' . DB2_DATABASE .
                        ';user=' . DB2_USERNAME .
                        ';password=' . DB2_PASSWORD
                );
                break;
            default:
                $conn = new PDO(
                    DB1_DRIVE .
                        ':host=' . DB1_HOSTNAME .
                        ';port=' . DB1_PORT .
                        ';dbname=' . DB1_DATABASE .
                        ';user=' . DB1_USERNAME .
                        ';password=' . DB1_PASSWORD
                );
                break;
        }
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    public function Connections()
    {

        $data = array(
            'connections' => array(

                array(
                    'name' => 'Produção',
                    'value' => 'production'
                ),
                array(
                    'name' => 'Demo',
                    'value' => 'demo'
                )
            )
        );

        return $data;
    }
}
