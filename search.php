<?php
include_once("includes/header.php");
?>
<div class="textboxContainer">
    <input type="text" class="searchInput" placeholder="Search...">
</div>

<div class="result">
</div>

<script>

$(function() {
    var timer;


    $('.searchInput').keyup(function(){
        clearTimeout(timer);

        timer = setTimeout(function(){
            var value =  $('.searchInput').val();
            

            if (value != ""){
                $.post("ajax/getSearchResults.php", {value: value}, function(data){
                    $(".result").html(data);
                });
            }else{
                $(".result").html("");
            }

        }, 500)
    })
})

</script>