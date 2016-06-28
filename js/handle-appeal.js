function removeAppeal(appeal_id){


  jQuery.ajax({
       url: '../appeal-handler.php',
       type: 'POST',
       data: { appealId: appeal_id },
       complete: function(data, status)
       {
           if(status=='success')
           {
              console.log("appeal removed");
              document.getElementById('an_'+appeal_id).style.display = "none";
              document.getElementById('notifications').innerHTML -= 1;
           }
       }
   });
};
