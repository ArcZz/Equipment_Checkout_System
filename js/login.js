
//for website interface.
$(function(){
  $('select').change(function() {

         switch($(this).val()){

           case "0":
             $("div.sign").addClass("hidden");

             break;

           case "1":

             $("div.sign").removeClass("hidden");
             break;
           default:
         }
       });

//for login function.
$("#submit").click(function(){

  if($("#user").val().length < 1){
     response("No username");
    }else if($("#pass").val().length < 4){
     response("Needs to be at least 4 letters long");
           }else if($("select").val() === "1" && $("#pass1").val() !== $("#pass").val()){
           response("Passwords don't match");
           }else{

          $.post("loginConnection.php", {"user": $("#user").val(), "fname": $("#fname").val(), "lname": $("#lname").val(), "email": $("#email").val(), "pass": $("#pass").val(), "new": $("select").val()},

          function(data){
          console.log(data);
          if(data=="admin"){
          
           $(location).attr('href', "index.php")
          return false;
          }
          else if(data=="employee"){
            $(location).attr('href', "index.php")
           return false;
          }
          else if (data=="fail") {
            response("login fail, check your username and passworld again please");
          }
           else if (data=="existed") {
                 response("sry, this user already existed");
             }
               else if(data=="signin"){
                 alert(" success! Pls remember ur username and password.");
                 location.reload(true);
               }
      });
   }
 });

});

function response(errorMessage){

     $("#hint").html("<p id = 'hintText'>" + errorMessage + "</p>");

 }
