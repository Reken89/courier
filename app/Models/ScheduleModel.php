<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class ScheduleModel extends BaseModel
{
    /**
     * Выполняем запись в таблицу schedule
     *
     * @param int $courier, int $region, string $departure, string $delivery, string $return
     * @return bool
     */
    public function InsertLine(int $courier, int $region, string $departure, string $delivery, string $return)
    {   
        $sql = "INSERT INTO schedule (courier_id, region_id, departure_date, delivery_date, return_date) VALUES "
                . "(:courier_id, :region_id, :departure, :delivery, :return)";               
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":courier_id", $courier, PDO::PARAM_STR);
        $stmt->bindValue(":region_id", $region, PDO::PARAM_STR);
        $stmt->bindValue(":departure", $departure, PDO::PARAM_STR);
        $stmt->bindValue(":delivery", $delivery, PDO::PARAM_STR);
        $stmt->bindValue(":return", $return, PDO::PARAM_STR);
        $stmt->execute();      
        return $stmt->rowCount() > 0 ? true : false;  
    }  
    
    /**
     * Выполняем первую проверку
     * Свободен ли курьер в нужную дату
     *
     * @param int $courier, string $departure
     * @return bool
     */
    public function CheckOneRecord(int $courier, string $departure)
    {   
        $sql = "SELECT * FROM schedule WHERE courier_id = :courier_id AND departure_date <= :departure AND return_date >= :departure";             
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":courier_id", $courier, PDO::PARAM_STR);
        $stmt->bindValue(":departure", $departure, PDO::PARAM_STR);
        $stmt->execute();      
        return $stmt->rowCount() > 0 ? true : false;  
    }  
    
    /**
     * Выполняем вторую проверку
     * Свободен ли курьер в нужную дату
     *
     * @param int $courier, string $departure, string $return
     * @return bool
     */
    public function CheckTwoRecord(int $courier, string $departure, string $return)
    {   
        $sql = "SELECT * FROM schedule WHERE courier_id = :courier_id AND return_date >= :departure AND departure_date <= :return";             
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":courier_id", $courier, PDO::PARAM_STR);
        $stmt->bindValue(":departure", $departure, PDO::PARAM_STR);
        $stmt->bindValue(":return", $return, PDO::PARAM_STR);
        $stmt->execute();      
        return $stmt->rowCount() > 0 ? true : false;  
    }  
    
    /**
     * Получаем расписание
     * На нужную нам дату
     *
     * @param string $date
     * @return array
     */
    public function SelectLine(string $date)
    {          
        $sql = "SELECT schedule.id, departure_date, delivery_date, city, courier FROM schedule "
                . "inner join regions on schedule.region_id = regions.id "
                . "inner join couriers on schedule.courier_id = couriers.id "
                . "WHERE departure_date = :date";   
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":date", $date, PDO::PARAM_STR);
        $stmt->execute();      
        $res = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[$row['id']] = $row;
        }
        return $res;
    }  
}
