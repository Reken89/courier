<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class IndexController extends BaseController
{
    //шаблоны представления
    private $main = "/views/main.php";
    private $show = "/views/show.php";
    private $table = "/views/table.php";
    private $add_form = "/views/add.php";
    private $form = "/views/form.php";
    private $data;
    
    /**
     * Наполнение таблицы schedule
     *
     * @param 
     * @return 
     */
    public function FillingSchedule()
    {
        //Стартовая дата
        $date = "2024-05-01";
        
        //Цикл для заполнения 3 месяцев
        for($a = 1 ; $a < 92 ; $a++){
            $date = date("Y-m-d", strtotime("+1 days", strtotime($date)));
            
            //Отправляем трех курьеров в день
            for($b = 1 ; $b < 4 ; $b++){
                $region_id = rand(1, 10);
                $region_info = $this->region->TravelTime($region_id);
                
                //Определяем дату доставки и дату возвращения курьера
                $delivery_date = date("Y-m-d", strtotime("+$region_info[travel_days] days", strtotime($date)));
                $return_date = date("Y-m-d", strtotime("+$region_info[return_days] days", strtotime($delivery_date)));
                
                //Проверка свободен ли курьер
                //Если курьер занят, выбираем другого
                for($i = 1 ; $i < 11 ; $i++){
                    $courier = rand(1, 10);
                    $result_one = $this->schedule->CheckOneRecord($courier, $date);
                    $result_two = $this->schedule->CheckTwoRecord($courier, $date, $return_date);
                    if ($result_one == true || $result_two == true){
                        //Курьер занят!
                    }
                    if ($result_one == false && $result_two == false){
                        $status = $this->schedule->InsertLine($courier, $region_id, $date, $delivery_date, $return_date);
                        break;
                    }
                }                
            }            
        } 
        return $status;               
    }
    
     /**
     * Выбор страницы
     *
     * @param 
     * @return 
     */
    public function SelectPage()
    {
        $this->data = [];
        $this->view->render($this->main, $this->data);               
    }
    
     /**
     * Показать расписание по выбранной дате
     *
     * @param 
     * @return 
     */
    public function ShowSchedule()
    {
        $this->data = [];
        $this->view->render($this->show, $this->data);           
    }
    
    /**
     * Получаем из БД нужные строки
     *
     * @param 
     * @return 
     */
    public function SelectSchedule()
    {
        if(isset($_POST['date'])){
            $this->data['schedule'] = $this->schedule->SelectLine($_POST['date'][0]); 
            $this->data['date'] = $_POST['date'][0];
        }else{
            $this->data = [];
        }
        $this->view->render($this->table, $this->data);               
    }
    
     /**
     * Показать представление с формой ввода
     *
     * @param 
     * @return 
     */
    public function AddSchedule()
    {
        $this->data = [];
        $this->view->render($this->add_form, $this->data);                
    }
    
    /**
     * Подгружаем форму ввода
     *
     * @param 
     * @return 
     */
    public function FormSchedule()
    {
        //Значения для формы ввода
        $this->data['regions'] = $this->region->SelectAll();
        $this->data['couriers'] = $this->courier->SelectAll();
        $this->data['date'] = $_POST['date'];
        
        if(isset($_POST['region'])){
            $region_info = $this->region->TravelTime($_POST['region']);
            
            //Значения для формы ввода
            $this->data['delivery_date'] = date("Y-m-d", strtotime("+$region_info[travel_days] days", strtotime($_POST['date'])));
            $this->data['city'] = $region_info['city'];
            $this->data['region'] = $_POST['region'];
        }else{
            //Значения для формы ввода
            $this->data['delivery_date'] = $_POST['delivery_date'];
            $this->data['city'] = $_POST['city'];
            $this->data['region'] = 1;
        }
        $this->view->render($this->form, $this->data);                
    }
    
    /**
     * Добавляем запись в таблицу schedule
     *
     * @param 
     * @return 
     */
    public function InsertSchedule()
    {
        $region_info = $this->region->TravelTime($_POST['region']);                
        //Определяем дату доставки и дату возвращения курьера
        $delivery_date = date("Y-m-d", strtotime("+$region_info[travel_days] days", strtotime($_POST['date'])));
        $return_date = date("Y-m-d", strtotime("+$region_info[return_days] days", strtotime($delivery_date)));
        
        //Проверка свободен ли курьер
        $result_one = $this->schedule->CheckOneRecord($_POST['courier'], $_POST['date']);
        $result_two = $this->schedule->CheckTwoRecord($_POST['courier'], $_POST['date'], $return_date);
        if ($result_one == true || $result_two == true){
            //Курьер занят!
            $this->data['message'] = "Выбранный курьер занят! Измените дату отправки или выберите другого курьера!";
        }
        if ($result_one == false && $result_two == false){
            $this->schedule->InsertLine($_POST['courier'], $_POST['region'], $_POST['date'], $delivery_date, $return_date);
            $this->data['message'] = "Заказ принят, курьер доставит заказ в $region_info[city] $delivery_date!";
        }
        //Значения для формы ввода
        $this->data['regions'] = $this->region->SelectAll();
        $this->data['date'] = $_POST['date'];
        $this->data['city'] = $region_info['city'];
        $this->data['region'] = $_POST['region'];
        $this->data['couriers'] = $this->courier->SelectAll();
        
        $this->view->render($this->form, $this->data);              
    }
}