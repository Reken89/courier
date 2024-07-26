<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class RegionModel extends BaseModel
{
    
    /**
     * Получаем информацию о длительности поездки
     *
     * @param int $id
     * @return 
     */
    public function TravelTime(int $id)
    {   
        $sql = "SELECT * FROM regions WHERE id = :id";             
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);
        $stmt->execute();      
        return $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : false;  
    }  
    
    /**
     * Получаем список регионов
     *
     * @param 
     * @return array
     */
    public function SelectAll()
    {          
        $sql = "SELECT * FROM regions";   
        $stmt = $this->db->prepare($sql);
        $stmt->execute();      
        $res = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[$row['id']] = $row;
        }
        return $res;
    }  

}
