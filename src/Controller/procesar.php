<?php
namespace App\Controller;
include_once('conexion.php');

class Procesar extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function build_report($year)
    {
        $total = array();
        for ($i = 0; $i < 12; $i++) {
            $month = $i + 1;
            $sql = $this->db->query("SELECT SUM(id) AS total FROM User WHERE MONTH(registeredAt) = '$month' AND YEAR(registeredAt) = '$year' LIMIT 1");
            $total[$i] = 0;
            foreach ($sql as $key) {
                $total[$i] = ($key['total'] == null) ? 0 : $key['total'];
            }
        }
        return $total;
    }

}

if ($_POST['year']) {
    $class = new Procesar;
    $run = $class->build_report($_POST['year']);
    exit(json_encode($run));
}
?>