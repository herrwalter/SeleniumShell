<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.js" ></script>
<script type="text/javascript">
    getProgress = function(){
        console.log('getting progress');
        $.ajax( { url: 'progress.txt', type: 'get', success: function(a){ $('body').html(a)} });
    }
    var getTests = setInterval(getProgress, 400);
    $.ajax( { url: 'index.php', complete: function(){ clearInterval(getTests); console.log( 'process finished' ); } } );
    
    
    
</script>