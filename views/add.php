<script src="https://code.jquery.com/jquery-3.6.3.js"></script> 

<div id="live_data"></div>

<script>
$(document).ready(function(){
    
    //Подгружаем BACK шаблон отрисовки
    function fetch_data(){ 
        let date = "2024-07-25";
        let delivery_date = "2024-07-26";
        let city = "Санкт-Петербург";
        
        $.ajax({  
            url:"/courier/form", 
            method:"POST",
            data:{
                date, delivery_date, city
            },
            dataType:"text", 
            success:function(data){  
                $('#live_data').html(data); 
            }   
        });  
    } 
    fetch_data();
    
    //Выполняем действие (применяем фильтр) при нажатии на кнопку
    $(document).on('click', '#calculate', function(){
        let tr = this.closest('tr');
        let region = $('.region', tr).val();
        let date = $('.date', tr).val();

        $.ajax({
            url:"/courier/form",  
            method:"POST",
            data:{
                region, date
            },
            dataType:"text",  
            success:function(data){ 
                $('#live_data').html(data); 
            } 
        })                  
    })
    
    //Выполняем действие (добавляем запись) при нажатии на кнопку
    $(document).on('click', '#add', function(){
        let tr = this.closest('tr');
        let region = $('.region', tr).val();
        let date = $('.date', tr).val();
        let courier = $('.courier', tr).val();

        $.ajax({
            url:"/courier/insert",  
            method:"POST",
            data:{
                region, date, courier
            },
            dataType:"text",  
            success:function(data){ 
                $('#live_data').html(data); 
            } 
        })                  
    })
});
    
</script> 
  