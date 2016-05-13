function removeAppeal(appeal_id){
document.getElementById('an_'+appeal_id).style.display = "none";
document.getElementById('notifications').innerHTML -= 1;

  jQuery.ajax({
       url: '../appeal-handler.php',
       type: 'POST',
       data: { appealId: appeal_id },
       complete: function(data, status)
       {
           if(status=='success')
           {
              console.log("appeal removed");
           }
       }
   });
};
