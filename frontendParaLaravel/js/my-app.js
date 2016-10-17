/**
 * Declaración e inicialización de elementos necesarios para el manejo de Framework7
 */
var myApp = new Framework7();
var $$ = Dom7;
var user;
var email;
var circuit;
var backend='http://localhost:8000/api'
//var backend='http://localhost/CarreradeObservacionUAM/backendSlim'
var mainView = myApp.addView('.view-main', {
    dynamicNavbar: true
});

/**
 * Al inicio de la página index2 realizar lo que se indica
 */
myApp.onPageInit('index2', function (page) {
  var pageContainer = $$(page.container);
  /*
   * Se captura el evento de click del boton especificado
   */
  pageContainer.find('.botonSiguiente').on('click', function () {
    //se construyen los parámetros y se valida antes de hacer la consulta al backend
    email = pageContainer.find('input[name="email"]').val();
    if(email== ""){
      myApp.alert('Debe ingresar un correo válido');
    }
    else{
      var params= '{"email":"' + email + '"}';
      $$.ajax({
         url: backend + '/users/email',
         type: "POST",
         contentType: "application/json",
         data: params,
         success: function(data, textStatus ){
           mainView.router.loadPage("login.html");
         },
         error: function(xhr, textStatus, errorThrown){
           // excepción en caso que exista algún fallo al actualizar #debugMode
           myApp.alert('El correo ingresado no se encuentra registrado, por favor realice el registro');
           //cargar la página del formulario de registro de usuario
           mainView.router.loadPage("registroUsuario.html");
         }
      });
    }
  });
});

/**
 * Al inicio de la página login realizar lo que se indica
 */
myApp.onPageInit('login', function (page) {
  var pageContainer = $$(page.container);
  /*
   * Se captura el evento de click del boton especificado
   */
  pageContainer.find('.botonLogin').on('click', function () {
    //se realiza validación que si haya ingresado una contraseña, luego se arman parámetros para consultar el backend
    password = pageContainer.find('input[name="password"]').val();
    if(password == ""){
      myApp.alert('Debe ingresar la contraseña para continuar');
    }
    else{
      var params= '{"email":"' + email + '",'+ '"password":"' + password + '"}';
      $$.ajax({
         url: backend + '/users/passwd',
         type: "POST",
         contentType: "application/json",
         data: params,
         success: function(data, textStatus ){
           //devuelve el identificador del usuario logeado, se guarda la variable local user y carga selección carrera
           var arreglo=JSON.parse(data);
           user = arreglo.data[0].id;
           mainView.router.loadPage("seleccionCarrera.html");
         },
         error: function(xhr, textStatus, errorThrown){
           myApp.alert('Contraseña incorrecta, intente de nuevo por favor');
           //carga de nuevo la ventana de ingreso de contraseña
           mainView.router.loadPage("login.html");
         }
      });
    }
  });
});


/**
 * Al inicio de la página registroUsuario realizar lo que se indica
 */
myApp.onPageInit('registroUsuario', function (page) {
  var pageContainer = $$(page.container);
  pageContainer.find('input[name="email"]').val(email);
  /*
   * Se captura el evento de click del boton especificado
   */
  pageContainer.find('.botonAddUser').on('click', function () {
    //se carga el valor de correo especificado en la ventana anterior
    var nombres = pageContainer.find('input[name="nombres"]').val();
    var apellidos = pageContainer.find('input[name="apellidos"]').val();
    var fechaNacimiento = pageContainer.find('input[name="fechaNacimiento"]').val();
    var password = pageContainer.find('input[name="password"]').val();
    var color = pageContainer.find('input[name="color"]').val();
    var genero = pageContainer.find('select[name="genero"]').val();
    //se valida que los datos ingresados estén completos
    if(nombres == "" || apellidos =="" || fechaNacimiento == "" || password== "" || color == "" || genero ==""){
      myApp.alert('Debe ingresar todos los campos del formulario');
    }
    else{
      //se genera el JSON  con los parámetros necesarios para la creación del usuario
      var params= '{"name":"' + nombres + '",' + '"lastname":"' + apellidos + '",'+
      '"birthdate":"' + fechaNacimiento + '",' + '"email":"' + email + '", "password":"' + password  + '",'+ '"color":"' + color + '",'+
      '"gender":"' + genero + '",'+ '"type":"usuario"}';
      $$.ajax({
           url: backend + '/users',
           type: "POST",
           contentType: "application/json",
           data: params,
           success: function(data, textStatus ){
             var arreglo=JSON.parse(data);
             user = arreglo.data[0].id;
             //cargar la pagina de seleccionCarrera para validar a que carrera quiere ingresar
             mainView.router.loadPage("seleccionCarrera.html");
           },
           error: function(xhr, textStatus, errorThrown){
             //se alerta si no se pudo crear
             myApp.alert('usuario no pudo crearse por: ' + data);
             //cargar la página del formulario de inscripcion de nuevo
             mainView.router.loadPage("inscripcion.html");
           }
        });
    }
    });
  });

  /**
   * Al inicio de la página seleccionCarrera realizar lo que se indica
   */
  myApp.onPageInit('seleccionCarrera', function (page) {
    //se consultan y muestran las carreras que el usuario tiene inscritas
    var pageContainer = $$(page.container);
    var params = '{"user_id":'+ user + '}';
    var selectObject= pageContainer.find('select[name="carrerasInscritas"]');
    $$.ajax({
         url: backend + '/inscriptions/user',
         type: "POST",
         contentType: "application/json",
         data: params,
         success: function(data, textStatus ){
           var arreglo=JSON.parse(data);
           //cargar valores en el select carrerasInscritas
                        user = arreglo.data[0].id;
           for(i=0;i < Object.keys(arreglo.data).length; i++){
             var opcion = document.createElement("option");
             opcion.text = arreglo.data[i].name;
             opcion.value = arreglo.data[i].id;
             selectObject.append(opcion);
           }
         },
         error: function(xhr, textStatus, errorThrown){
           //en caso que no hayan carreras inscritas para el usuario
           myApp.alert('No tiene carreras inscritas, solicite su inscripción al administrador');
         }
      });
    /*
     * Se captura el evento de click del boton ingresar
     */
    pageContainer.find('.botonIngresar').on('click', function () {
        circuit= pageContainer.find('select[name="carrerasInscritas"]').val();
        //se valida que haya seleccionado una carrera
        if(circuit==""){
          myApp.alert('No tiene carreras inscritas, solicite su inscripción al administrador');
        }
        else{
          //se carga la interfaz de juego de la carrera
          mainView.router.loadPage("principal.html");
        }
    });
});

/**
 * Al inicio de la página principal2 realizar lo que se indica
 */
myApp.onPageInit('principal2', function (page) {
  var pageContainer = $$(page.container);
  //se evalua si el usuario tiene por lo menos una pista, si no la tiene se le genera
  //var params = '{"user_id":'+ user + ', "circuit_id":' + circuit + '}';
  $$.ajax({
       url: backend + '/discoverednodes/visited/'+user+'/'+circuit,
       type: "GET",
       contentType: "application/json",
       //data: params,
       success: function(data, textStatus ){

       },
       error: function(xhr, textStatus, errorThrown){
         genDiscoveredNode();
       }
    });
});


/**
 * Esta funciòn permite generarle una nueva pista al usuario de forma aleatoria
 */
function genDiscoveredNode(){
  //se crea un JSON con los datos del usuario y la carrera y luego se obtienen los nodos que le faltan por visitar
  var params = '{"user_id":'+ user + ', "circuit_id":'+circuit+'}';
  $$.ajax({
       url: backend + '/discoverednodes/tovisit',
       type: "POST",
       contentType: "application/json",
       data: params,
       success: function(data, textStatus ){
         var arreglo=JSON.parse(data);
         var longitud= Object.keys(arreglo.data).length;
         //se selecciona uno de los nodos que falta por visitar aleatoriamente
         var nodo=Math.floor(Math.random() * longitud);
         //se consultan las preguntas disponibles del nodo seleccionado y se elige una al azar
         $$.ajax({
              url: backend + '/questions/node/' + arreglo.data[nodo].id,
              type: "GET",
              contentType: "application/json",
              //data: params,
              success: function(data2, textStatus ){
                var arreglo2=JSON.parse(data2);
                var quests= Object.keys(arreglo2.data).length;
                var pregunta=Math.floor(Math.random() * quests);
                //se crea el nuevo nodo descubierto para el usuario con la pregunta aleatoriamente seleccionada
                params='{"node_id":'+ arreglo.data[nodo].id + ', "user_id":'+ user +',"question_id":' + arreglo2.data[pregunta].id +
                        ', "status": 0, "statusDate1" : "'+ getActualDateTime()
                        +'", "statusDate2" : null, "statusDate3": null}';
                $$.ajax({
                     url: backend + '/discoverednodes',
                     type: "POST",
                     contentType: "application/json",
                     data: params,
                     success: function(data, textStatus ){
                       myApp.alert('Tienes una nueva pista!!');
                     },
                     error: function(xhr, textStatus, errorThrown){
                       console.log('no se logró la creación del nodo descubierto');
                     }
                  });
              },
              error: function(xhr, textStatus, errorThrown){
                console.log('el nodo no tiene preguntas');
              }
           });
       },
       error: function(xhr, textStatus, errorThrown){
         myApp.alert('FELICIDADES!! haz terminado la Carrera de Observación UAM');
       }
    });
}

/**
 * Esta función permite generar la hora y fecha actual en el formato requerido por el backend
  * @return fechaHora
 */
function getActualDateTime(){
  var d = new Date();
  var fechaHora=(
      d.getFullYear() + "-" +
      ("00" + (d.getMonth() + 1)).slice(-2) + "-" +
      ("00" + d.getDate()).slice(-2) + " " +
      ("00" + d.getHours()).slice(-2) + ":" +
      ("00" + d.getMinutes()).slice(-2) + ":" +
      ("00" + d.getSeconds()).slice(-2)
  );
  return fechaHora;
}

/**
 * Al inicio de la página verPista realizar lo que se indica
 */
myApp.onPageInit('verPista', function (page) {
  //genera el JSON y realiza la consulta de las pistas que el usuario tiene activas
  var pageContainer = $$(page.container);
  var params = '{"user_id":'+ user + ', "circuit_id":'+circuit+'}';
  $$.ajax({
       url: backend + '/nodes/showhint',
       type: "POST",
       contentType: "application/json",
       data: params,
       success: function(data, textStatus ){
         var arreglo=JSON.parse(data);
           var test="";
           for(i=0;i < Object.keys(arreglo.data).length; i++){
             test+= arreglo.data[i].hint + '<br><br>';
           }
           document.getElementById("listaPistas").innerHTML = test;
       },
       error: function(xhr, textStatus, errorThrown){
         console.log('no hay pistas disponibles');
       }
    });
});

/**
 * Al inicio de la página escanear realizar lo que se indica
 */
myApp.onPageInit('escanear', function (page) {
  //carga las pistas que el usuario tiene pendientes por encontrar
  var pageContainer = $$(page.container);
  var selectObject= pageContainer.find('select[name="pistasQR"]');
  var params = '{"user_id":'+ user + ', "circuit_id":'+circuit+'}';
  $$.ajax({
   url: backend + '/nodes/showhint',
   type: "POST",
   contentType: "application/json",
   data: params,
   success: function(data, textStatus ){
     var arreglo=JSON.parse(data);
       //cargar valores en el select pistasQR
       for(i=0;i < Object.keys(arreglo.data).length; i++){
         var opcion = document.createElement("option");
         opcion.text = arreglo.data[i].hint;
         opcion.value = arreglo.data[i].id;
         selectObject.append(opcion);
       }
   },
   error: function(xhr, textStatus, errorThrown){
     console.log('No se lograron obtener pistas');
   }
  });

   //si el usuario elige el botón pasarCódigo, valida que haya escrito algo en el cuadro de texto y lo pasa al backend para validarlo
    pageContainer.find('.botonPasarCodigo').on('click', function () {
      var nodo_id= pageContainer.find('select[name="pistasQR"]').val();
      if(nodo_id==""){
        myApp.alert('No tiene pistar que escanear, debe encontrar una pista antes');
      }
      else{
        var codigo = pageContainer.find('input[name="codeText"]').val();
        if(nodo_id=="" || codigo==""){
          myApp.alert('Debe ingresar un código válido');
        }
        else{
          var params= '{"node_id":' + nodo_id + ', "code":"' + codigo + '"}';
          $$.ajax({
           url: backend + '/nodes/validate',
           type: "POST",
           contentType: "application/json",
           data: params,
           success: function(data, textStatus ){
             //en caso que el código sea el correcto se consulta el id de nodoDescubierto a actualizar
             $$.ajax({
               url: backend + '/discoverednodes/' + user + '/' + nodo_id,
               type: "GET",
               contentType: "application/json",
               //data: params,
               success: function(data, textStatus ){
                 var nodoDescubierto=JSON.parse(data);
                 var nd_id= nodoDescubierto.data[0].id;
                 var nd_question_id= nodoDescubierto.data[0].question_id;
                 var nd_statusDate1= nodoDescubierto.data[0].statusDate1;
                 //Se realiza la actualización del nodoDescubierto a estado1 (pendiente por responder)
                 var params = '{"node_id":'+ nodo_id + ', "user_id":'+user+', "question_id":' + nd_question_id +', "status":1'
                               +', "statusDate1":"'+nd_statusDate1+'","statusDate2":"'+getActualDateTime()+
                               '","statusDate3":null }';
                 //se realiza con el método AJAX ya que Framework7 sólo tiene GET y POST abreviados. Los demás verbos se usan con el método AJAX
                 $$.ajax({
                    url: backend + '/discoverednodes/'+nd_id,
                    type: "PUT",
                    contentType: "application/json",
                    data: params,
                    success: function(data, textStatus ){
                      data = JSON.parse(data);
                      myApp.alert('Tienes una nueva pregunta!!');
                      mainView.router.loadPage("principal.html");
                    },
                    error: function(xhr, textStatus, errorThrown){
                      // excepción en caso que exista algún fallo al actualizar #debugMode
                      console.log('fallo al actualizar nodo descubierto');
                    }
                 });
               },
               error: function(xhr, textStatus, errorThrown){
                 console.log('Falló la consulta');
               }
              });
           },
           error: function(xhr, textStatus, errorThrown){
             myApp.alert('El código no corresponde a la pista actual ');
           }
          });
        }
      }
      });
  });


  /**
   * Esta función se encarga de cargar y manejar el lector de código QR a través de la libreria barcodeScanner
   */
  function startScan() {
      cordova.plugins.barcodeScanner.scan(
          function (result) {
            //se carga el id del nodo a econtrar
            var nodo_id= pageContainer.find('select[name="pistasQR"]').val();
            //se carga el código QR leído, se realizan validaciones
            var codigo = result.text;
            if(nodo_id=="" || codigo==""){
              myApp.alert('Debe ingresar un código válido');
            }
            else{
              //se consulta si el código leído corresponde al que se debe encontrar
              var params= '{"node_id":' + nodo_id + ', "code":"' + codigo + '"}';
              $$.ajax({
               url: backend + '/nodes/validate',
               type: "POST",
               contentType: "application/json",
               data: params,
               success: function(data, textStatus ){
                 var arreglo=JSON.parse(data);
                 //obtener el id del nodo descubierto a actualizar a estado1
                 $$.ajax({
                   url: backend + '/discoverednodes/' + user + '/' + nodo_id,
                   type: "GET",
                   contentType: "application/json",
                   data: params,
                   success: function(data, textStatus ){
                     var nodoDescubierto=JSON.parse(data);
                     var nd_id= nodoDescubierto.data[0].id;
                     var nd_question_id= nodoDescubierto.data[0].question_id;
                     var nd_statusDate1= nodoDescubierto.data[0].statusDate1;
                     //se realiza la actualización del nodo descubierto
                     var params = '{"node_id":'+ nodo_id + ', "user_id":'+user+', "question_id":' + nd_question_id +', "status":1'
                                   +', "statusDate1":"'+nd_statusDate1+'","statusDate2":"'+getActualDateTime()+
                                   '","statusDate3":null }';
                     $$.ajax({
                        url: backend + '/discoverednodes/'+nd_id,
                        type: "PUT",
                        contentType: "application/json",
                        data: params,
                        success: function(data, textStatus ){
                          data = JSON.parse(data);
                          myApp.alert('Tienes una nueva pregunta!!');
                          //lo envìa a la página de ver pista a ver la nueva pista generada
                          mainView.router.loadPage("principal.html");
                        },
                        error: function(xhr, textStatus, errorThrown){
                          // We have received response and can hide activity indicator
                          console.log('fallo al actualizar nodo descubierto');
                        }
                     });
                   },
                   error: function(xhr, textStatus, errorThrown){
                     console.log('Falló la consulta');
                   }
                  });
               },
               error: function(xhr, textStatus, errorThrown){
                 myApp.alert('El código no corresponde a la pista actual ');
               }
             });
            }
          },
          function (error) {
              alert("Scanning failed: " + error);
          }
      );
  }




  /**
   * Al inicio de la página verPregunta realizar lo que se indica
   */
  myApp.onPageInit('verPregunta', function (page) {
    //cargar las preguntas pendientes por contestar
    var pageContainer = $$(page.container);
    var params = '{"user_id":'+ user + ', "circuit_id":'+circuit+'}';
    var selectObject= pageContainer.find('select[name="preguntas"]');
    $$.ajax({
       url: backend + '/discoverednodes/showquestion',
       type: "POST",
       contentType: "application/json",
       data: params,
       success: function(data, textStatus ){
         var arregloB=JSON.parse(data);
         var test="";
         for(i=0;i < Object.keys(arregloB.data).length; i++){
           test+= arregloB.data[i].question + '<br><br>';
         }
         document.getElementById("listaPreguntas").innerHTML = test;
       },
       error: function(xhr, textStatus, errorThrown){
    	    console.log('No hay preguntas disponibles');
       }
    });
  });

  /**
   * Al inicio de la página response realizar lo que se indica
   */
myApp.onPageInit('response', function (page) {
  //cargar las preguntas pendientes por contestar
  var pageContainer = $$(page.container);
  var params = '{"user_id":'+ user + ', "circuit_id":'+circuit+'}';
  var selectObject= pageContainer.find('select[name="preguntas"]');
  $$.ajax({
     url: backend + '/discoverednodes/showquestion',
     type: "POST",
     contentType: "application/json",
     data: params,
     success: function(data, textStatus ){
       var arregloB=JSON.parse(data);
         for(i=0;i < Object.keys(arregloB.data).length; i++){
           var opcion = document.createElement("option");
           opcion.text = arregloB.data[i].question;
           opcion.value = arregloB.data[i].id;
           selectObject.append(opcion);
         }
         //si el usuario elige el botón responder, se toma la pregunta seleccionada y se construye el JSON para validar si la respuesta es correcta
         pageContainer.find('.botonResponder').on('click', function () {
             var question= pageContainer.find('select[name="preguntas"]').val();
             if(question==""){
               myApp.alert('No tiene preguntas que contestar, debe encontrar un nodo antes');
             }
             else{
               var respuesta = pageContainer.find('input[name="response"]').val();
               var respTemp=replaceall(respuesta,' ','');
               //se valida que se haya realizado un ingreso al cuadro de texto
               if(respTemp==""){
                 myApp.alert('Debe ingresar un respuesta válida');
               }
               else{
                 //normalizar respuesta, debe estar en mayúsculas y sin tildes
                 respuesta=respuesta.toUpperCase();
                 respuesta=replaceall(respuesta,'Á','A');
                 respuesta=replaceall(respuesta,'É','E');
                 respuesta=replaceall(respuesta,'Í','I');
                 respuesta=replaceall(respuesta,'Ó','O');
                 respuesta=replaceall(respuesta,'Ú','U');
                 //se contruye el JSON con la información a validar
                 var params= '{"question_id":' + question +', "answer":"' + respuesta + '"}';
                 $$.ajax({
                   url: backend + '/questions/validate',
                   type: "POST",
                   contentType: "application/json",
                   data: params,
                   success: function(data, textStatus ){
                     var arregloB=JSON.parse(data);
                     //si la respuesta es correcta, se consulta el id del nodoDescubierto a actualizar
                     var params = '{"user_id":'+ user + ', "circuit_id":'+circuit+', "question_id":' + question + '}';
                     $$.ajax({
                       url: backend + '/discoverednodes/getid',
                       type: "POST",
                       contentType: "application/json",
                       data: params,
                       success: function(data, textStatus ){
                         var nodoDescubierto=JSON.parse(data);
                         var nd_id= nodoDescubierto.data[0].id;
                         var nd_node_id= nodoDescubierto.data[0].node_id;
                         var nd_statusDate1= nodoDescubierto.data[0].statusDate1;
                         var nd_statusDate2= nodoDescubierto.data[0].statusDate2;
                         //se actualiza el nodoDescubierto a estado 2
                         var params = '{"node_id":'+ nd_node_id + ', "user_id":'+user+', "question_id":' + question +', "status":2'
                                       +', "statusDate1":"'+nd_statusDate1+'","statusDate2":"'+nd_statusDate2+
                                       '","statusDate3":"'+ getActualDateTime() + '"}';
                         $$.ajax({
                            url: backend + '/discoverednodes/'+nd_id,
                            type: "PUT",
                            contentType: "application/json",
                            data: params,
                            success: function(data, textStatus ){
                              data = JSON.parse(data);
                              myApp.alert('Respuesta correcta!! ');
                              //se genera una nueva pista para el usuario
                              genDiscoveredNode();
                              mainView.router.loadPage("principal.html");
                            },
                            error: function(xhr, textStatus, errorThrown){
                              // Excepción en caso de fallo del actualizador
                              console.log('fallo al actualizar nodo descubierto');
                            }
                         });
                       },
                       error: function(xhr, textStatus, errorThrown){
                         console.log('No existe el nodo consultado');
                       }
                      });
                   },
                   error: function(xhr, textStatus, errorThrown){
                     myApp.alert('Respuesta incorrecta, trate nuevamente ');
                   }
                  });
               }
             }
         });
     },
     error: function(xhr, textStatus, errorThrown){
       console.log('falló la consulta');
     }
    });
});

/*
 * Esta función permite reemplazar todos los valores que corersponden a un parámetro al interior de una cadena, por otro
 */
function replaceall(str,replace,with_this)
{
    var str_hasil ="";
    var temp;
    for(var i=0;i<str.length;i++)
    {
        if (str[i] == replace)
        {
            temp = with_this;
        }
        else
        {
                temp = str[i];
        }
        str_hasil += temp;
    }
    return str_hasil;
}

/**
 * Al inicio de la página puntuacion realizar lo que se indica
 */
myApp.onPageInit('puntuacion', function (page) {
  var pageContainer = $$(page.container);
  var texto;
  //consulta la cantidad de nodos totales de la carrera
  $$.ajax({
   url: backend + '/circuits/score/'+circuit,
   type: "GET",
   contentType: "application/json",
   //data: params,
   success: function(data, textStatus ){
     var arreglo=JSON.parse(data);
     texto="De un total de "+ arreglo.data[0].total + " nodos a visitar has completado ";
   },
   error: function(xhr, textStatus, errorThrown){
	    console.log('No se logró la consulta de puntuación general');
   }
  });


  //consulta la cantidad de nodos en estado 2 para un usuario específico
  $$.ajax({
   url: backend + '/discoverednodes/score/'+user+'/'+circuit,
   type: "GET",
   contentType: "application/json",
   //data: params,
   success: function(data, textStatus ){
     var arreglo=JSON.parse(data);
     texto+= Object.keys(arreglo.data).length + ".</br></br> A continuación los nombres de los nodos que has completado: </br></br>";
     for(i=0;i < Object.keys(arreglo.data).length; i++){
       texto+= arreglo.data[i].name + '<br>';
     }
     document.getElementById("listaPuntuacion").innerHTML = texto;
   },
   error: function(xhr, textStatus, errorThrown){
     console.log('Fallo al consultar nodos del usuario');
   }
  });

});

/**
 * Al inicio de la página score realizar lo que se indica
 */
myApp.onPageInit('score', function (page) {
  var pageContainer = $$(page.container);
  var texto="";
  //se consulta la tabla de puntuación general de la carrera, retorna usuario (email) y cantidad de nodos visitados ordenados de mayor a menor
  $$.ajax({
     url: backend + '/circuits/totalscore/'+circuit,
     type: "GET",
     contentType: "application/json",
     //data: params,
     success: function(data, textStatus ){
       var arreglo=JSON.parse(data);
       var longitud= Object.keys(arreglo.data).length;
       var top=5;
       if(longitud == 0){
         myApp.alert('Carrera de Observación UAM','Ningún participante tiene nodos visitados aún!! ');
       }
       else{
         if(longitud < 4){
           top=Object.keys(arreglo.data).length;
         }

         //generar la tabla del ranking
         texto='<table>	<tr>		<td>Pos.</td>		<td>Usuario</td>		<td>Nodos</td>	</tr>';
         for(i=0;i < top; i++){
           texto+= '<tr><td>'+ (i+1) + '</td><td>' + arreglo.data[i].email + '</td><td>'+ arreglo.data[i].cantidad+ '</td></tr>';
         }
         document.getElementById("totalScore").innerHTML = texto;
       }
     },
     error: function(xhr, textStatus, errorThrown){
       console.log('Falló la consulta de score general');
     }
  });
});

/**
 * Permite resetear las variables usadas para el funcionamiento de la aplicación a su estado inicial y vuelve a la página inicial
 */
function signOut() {
  user='';
  email='';
  circuit='';
  mainView.router.loadPage("index.html");
};

/**
 * Al inicio de la página verMapa realizar lo que se indica
 * Usando la libreria leaflet nos permite cargar un mapa prediseñado de la UAM
 */
myApp.onPageInit('verMapa', function (page) {
  var map;
  	map = L.map('mapid').setView([5.0672036513457535, -75.5031082034111], 16);
  	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFsaWNpbzM3IiwiYSI6ImNpcGlzNmlpdDAxc3F0ZW00NXNnMGI0MTQifQ.EUkOOib26_TXpRN39uVvDQ', {
  		maxZoom: 18,
  		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
  			'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
  			'Imagery © <a href="http://mapbox.com">Mapbox</a>',
  		id: 'malicio37.0dp70o3a'
  	}).addTo(map);
    //se consulta la información de los nodos visitados para crear marcadores con los nodos completados por el usuario
    $$.ajax({
       url: backend + '/users/locationvisited/'+user +'/'+circuit,
       type: "GET",
       contentType: "application/json",
       //data: params,
       success: function(data, textStatus ){
         var locations=JSON.parse(data);
         for(i=0;i < Object.keys(locations.data).length; i++){
           var marker = L.marker([ locations.data[i].latitude , locations.data[i].longitude ]).addTo(map);
           var nombre='"'+[locations.data[i].name]+'"';
           marker.bindPopup(nombre).openPopup();
         }
       },
       error: function(xhr, textStatus, errorThrown){
    	    console.log('falló al obtener ubicación de nodos visitados');
       }
    });
});
