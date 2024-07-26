<script src="https://code.jquery.com/jquery-3.6.3.js"></script>            

<div id="live_data"></div>

<script>
$(document).ready(function(){  
    
    //Подгружаем BACK шаблон отрисовки
    function fetch_data(){  
        let date = ['2024-07-01'];
        
        $.ajax({  
            url:"/courier/table",  
            method:"POST", 
            data:{
                date
            },
            dataType:"text", 
            success:function(data){  
                $('#live_data').html(data);  
            }   
        });  
    } 
    fetch_data(); 
    
    //Выполняем действие (применяем фильтр) при нажатии на кнопку
    $(document).on('click', '#apply', function(){
        let info = $('#filters').serializeArray();
        let date = [];

        //Распределяем нужные значения из формы   
        for (const item of info) {
            const value = item.value;
            if (item.name === 'date') {
                date.push(value);
            } 
        }   

        $.ajax({
            url:"/courier/table",  
            method:"POST",
            data:{
                date
            },
            dataType:"text",  
            success:function(data){ 
                $('#live_data').html(data); 
            } 
        })            
    })
});
</script>
