<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class CourierModel extends BaseModel
{   
    /**
     * Получаем список курьеров
     *
     * @param 
     * @return array
     */
    public function SelectAll()
    {          
        $sql = "SELECT * FROM couriers";   
        $stmt = $this->db->prepare($sql);
        $stmt->execute();      
        $res = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[$row['id']] = $row;
        }
        return $res;
    }  
}


