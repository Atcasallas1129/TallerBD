<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Tutorial validación de formulario</title>
</head>
 
<body>
 
  <div class="formwr">
      <form id="register" method="post" action="javascript:void(0)">
        <input type="text" id="name" name="name[]" value="" placeholder="Nombre"><br>
         <input type="text" id="name" name="name[]" value="" placeholder="Nombre"><br>
          <input type="text" id="name" name="name[]" value="" placeholder="Nombre"><br>
           <input type="text" id="name" name="name[]" value="" placeholder="Nombre"><br>
            <input type="text" id="name" name="name[]" value="" placeholder="Nombre"><br>
        <div class="field-small left"><input type="text" id="surname" name="surname" value="" placeholder="1er apellido"><br></div>
        <div class="field-small right"><input type="text" id="surname2" name="surname2" value="" placeholder="2º apellido"><br></div>
        <input type="text" id="email" name="email" value="" placeholder="Email"/><span id="mail-result"></span><br>
        <input type="text" id="cpostal" name="cpostal" value="" maxlength="5" placeholder="Código Postal"><br> 
 
         <div class="confirm-policy">
          <div class="cbox">
            <input type="checkbox" value="acepto BBLL" id="cb-bbll" class="bbll" name="check">
            <label for="cb-bbll"></label>
          </div>
          <span class="text cbtext">He leído y acepto las <a href="http://selection.clarel.es/bbll.html" target="_blank" id="legal"><u>Bases Legales</u></a></span>
          </div>
         <input id="regsub" class="right" type="submit" name="submit" value="Participar">
         <div class="warning hide">Debes leer y aceptar las bases legales.</div>
      </form>
  </div>
    <!-- jQuery -->
     <script type="text/javascript" src="javascript/jquery-2.2.1.min.js"></script>
 
    <!-- jQuery Validation -->
    <script src="javascript/jquery-validate.min.js" type="text/javascript"></script>
    <script src="js/main.js"></script>

    <script>
      $( document ).ready(function() {
        $('#register').submit(function(e) {
            e.preventDefault();
        }).validate({
            debug: false,
            rules: {
                "name[]": {
                    required: true
                },
                "surname": {
                    required: true
                },
                "surname2": {
                    required: true
                },
                "email": {
                    required: true,
                    email: true
                },
                "cpostal": {
                    required: true,
                    number:true,
                    minlength: 5,
                    maxlength: 5
                }
            },
            messages: {
                "name[]": {
                    required: "Introduce tu nombre."
                },
                "surname": {
                    required: "Apellido obligatorio."
                },
                "surname2": {
                    required: "Apellido obligatorio."
                },
                "email": {
                    required: "Introduce tu correo.",
                    email: ""
                },
                "cpostal": {
                    required: "Introduce tu código postal.",
                    number: "Introduce un código postal válido.",
                    maxlength: "Debe contener 5 dígitos.",
                    minlength: "Debe contener 5 dígitos."
                }
            }
 
        });
});
    </script>
</body>
</html>

