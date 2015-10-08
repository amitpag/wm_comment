 <?php
 // echo('gff');
 error_reporting(0);
class pdoconn
{
    static function Conn()
    {
        $hostname = 'localhost';
        $username = 'root';
        $password = "stu8103810";
        try 
        {
            $dbh = new PDO("mysql:host=$hostname;dbname=wm_comment", $username, $password);
            return $dbh;
            //$row = $query->fetchAll(PDO::FETCH_ASSOC);
            //  return $row;
           // $dbh = null;
        }
        catch(PDOException $e)
            {
                var_dump($e);
                return false;
            }
    }
}
?> 