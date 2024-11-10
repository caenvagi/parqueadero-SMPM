$(document).ready(function(){ 

    
        
    obtenerCards();  
    obtenerCardsdash()  
    $('#park-result').hide();    
   
    //ajax search parqueo
        $('#search').keyup(function(e){
            if($('#search').val()){ 
                let search = $('#search').val();
                $.ajax({
                    url: '../config/ajax/park-search.php',
                    type: 'POST',
                    data: { search }, 
                    success: function(response){                
                        let tasks = JSON.parse(response);
                        let template = '';

                        tasks.forEach(park => {
                            template += `
                            
                            <button id="btn_parqueo" name="btn_parqueo">

                                <span><img class="logo_parqueo" id="logo_parqueo" src="${park.cat_imagen}"></img></span>                    
                                <h7 class="placa_parqueo">${park.placa_cli} </h7> <br>
                                <h7 class="avisos_parqueo">Ingreso:</h7>
                                <h6>${park.fecha_ini}</h6>

                                <h7>tiempo:</h7>
                                <h6>${park.tiempo}</h6>                       

                                <h7>Valor por ${park.tar_tiempo}:</h7>
                                <h6>$ ${park.tarifas}</h6>
                                
                            </button>`
                        });
                        $('#container').html(template);
                        $('#park-result').show();
                        
                    }
                })
            }
        });
    // fin ajax search parqueo

    // ajax add parqueo 
        $('#parqueo').submit(function(e){ 
            cerrar();       
            const postData = {
                placa: $('#placa').val(),
                nombre: $('#nombre').val(),
                celular: $('#celular').val(),
                vehiculo: $('#vehiculo').val(),
                categoria: $('#categoria').val(),
                tarifas: $('#tarifas').val(),
                user: $('#user').val(),
            };
            $.post('../config/ajax/park-add.php',postData, function(response){ 
                console.log(response) ;    
                if(response == 'existe en parqueo'){                   
                    alertify.error('¡Error! - Vehiculo se encuentra en el parqueadero.');
                } 
                if(response == 'guardado parqueo'){
                    alertify.success('¡Ok! - Vehiculo ingresado.');
                }  
                if(response == 'guardado cliente y parqueo'){
                    alertify.warning('¡Ok! - vehiculo y cliente registrado.');               
                }                         
                obtenerCards(); 
                obtenerCardsdash()   
                $('#parqueo').trigger('reset');
            });    
            e.preventDefault();
        });    

        function cerrar(){ 
        setTimeout(function() {
            $('#contenedor-toasts').fadeIn(1000).delay(1000).fadeOut(5000);
        },); 
        };
    // fin ajax add parqueo 

    // ajax list parqueo 
        // function obtenerPark(){
        //     $.ajax({
        //         url: '../config/ajax/park-list.php',
        //         type: 'GET',
        //         success: function(response){
        //             let parks = JSON.parse(response);
        //             let template = '';
        //             parks.forEach(park => {
        //                 template += `
        //                     <tr>
        //                         <td>${park.parqueo_id}</td>
        //                         <td>${park.placa_cli}</td>
        //                         <td>${park.fecha_ini}</td>
        //                         <td>$ ${park.tarifas}</td>
        //                         <td>${park.nombre}</td>
        //                         <td>${park.estado}</td>
        //                     </tr>
        //                 `
        //             });
        //             $('#parks').html(template);
        //             // console.log(response);
        //         } 
        //     })
        // }
    // fin ajax list parqueo 

    //ajax list parqueo cards 
        function obtenerCards(){
            $.ajax({
                url: '../config/ajax/park-list.php',
                type: 'POST',
                success: function(response){
                    let parks = JSON.parse(response);
                    let template = '';
                    parks.forEach(park => {
                        template += `       
                                <div parkId="${park.placa_cli}" 
                                    ticketId="${park.parqueo_id}"
                                    fechaIni="${park.fecha_ini}"
                                    fechaFin="${park.fecha_fin}"
                                    tiempoId="${park.tiempo}"
                                    valorId="${park.valor}"
                                    usuarioId="${park.usuario}"

                                    caja_movimientoId="4"
                                    caja_desc_movimientoId="Parqueo por ${park.tar_tiempo} - ${park.placa_cli}"
                                    caja_egresosId="0"
                                    liquidadoId="NO"
                                    caja_tipoId="ingreso"

                                    class="col col-lg-3" 
                                    id="btn_parqueo" name="btn_parqueo">
                                    <form id="pagar" class="">
                                        
                                        <span><img class="logo_parqueo" id="logo_parqueo" src="${park.cat_imagen}"></img></span>                    
                                        <h7 class="placa_parqueo" id="placa_cli">${park.placa_cli} </h7> <br>                        
                                            
                                        <h6 class="tiempo_parqueo" id="tiempo_parqueo">${park.tiempo}</h6>             

                                        <h7 class="avisos_parqueo">Valor a pagar:</h7>
                                        <h6 class="pago_parqueo" id="pago_parqueo">$ ${park.valor}</h6>

                                        
                                        <button type="submit"
                                                id="btnParqueo_pagar" 
                                                onclick=""
                                                class="btnParqueo_pagar" name="btnParqueo_pagar"  href="">
                                                    
                                                <div class="spinner-grow spinner-grow-sm text-light" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <i class="bi bi-plus-lg text-white">&nbsp;PAGAR</i>
                                        </button>
                                        
                                    </form>
                                </div> `                    
                    });
                    $('#cards').html(template);
                    // console.log(response);
                } 
            })
            //cargar div automaticamente 
            setInterval(function() {obtenerCards()}, 30000);
        }   
    //ajax list parqueo cards

    //ajax list dashboard cards 
        function obtenerCardsdash(){
            $.ajax({
                url: '../config/ajax/park-list.php',
                type: 'GET',
                success: function(response){
                    let parks = JSON.parse(response);                
                    let template = '';
                    parks.forEach(park => {
                        template += `

                        
                        <button class="btn_parqueo" id="btn_parqueo" name="btn_parqueo">
        
                            <span><img class="logo_parqueo" id="logo_parqueo" src="${park.cat_imagen}"></img></span>                    
                            <h7 class="placa_parqueo">${park.placa_cli} </h7> <br>
                            <h7 class="avisos_parqueo">Ingreso:</h7>
                            <h6 class="tiempo_parqueo">${park.fecha_ini}</h6>
                            
        
                            <h7 class="avisos_parqueo">tiempo:</h7>
                            <h6 class="tiempo_parqueo">${park.tiempo}</h6>                       
        
                            <h7 class="avisos_parqueo">${park.tar_tiempo} a:</h7>
                            <h6 class="pago_parqueo">$ ${park.tarifas}</h6>

                            <h7 class="ciclo_parqueo">Valor a pagar:</h7>
                            <h6 class="pago_parqueo">$ ${park.valor}</h6>
                            
                        </button>
                            `                    
                    });
                    $('#cardsDash').html(template);
                    // console.log(response);
                } 
            })
            //cargar div automaticamente 
            setInterval(function() {obtenerCardsdash()}, 30000);
        } 
    // fin ajax list dashboard cards
    
    // ajax add pago
        $(document).on('click','.btnParqueo_pagar',function(e){ 
            let element = $(this)[0].parentElement.parentElement;                        
            let placa_cli = $(element).attr('parkId');             
            let ticketId = $(element).attr('ticketId'); 
            let fechaIni = $(element).attr('fechaIni');
            let fechaFin = $(element).attr('fechaFin');
            let tiempoId = $(element).attr('tiempoId');
            let valorId = $(element).attr('valorId');
            let usuarioId = $(element).attr('usuarioId');
            
            let caja_movimientoId=$(element).attr('caja_movimientoId');;
            let caja_desc_movimientoId=$(element).attr('caja_desc_movimientoId');            
            let caja_egresosId="0"
            let liquidadoId="NO"
            let caja_tipoId="ingreso"                 

            const postData = {
                placa_cli: placa_cli, 
                ticket: ticketId, 
                fechaini: fechaIni,
                fechafin: fechaFin,
                tiempo: tiempoId,
                valor: valorId,
                usuario: usuarioId,

                caja_movimiento: caja_movimientoId,
                caja_desc_movimiento: caja_desc_movimientoId,
                caja_egresos: caja_egresosId,
                liquidado: liquidadoId,
                caja_tipo: caja_tipoId, 
            };
            $.post('../config/ajax/park-pagar.php',postData, function(response){ 
                console.log(response) ; 
            })
            alertify.success('OK recibo generado');
            obtenerCards();
            e.preventDefault(); 
        });
    // fin ajax pago

    //borrar el div respuestas 
        $(function(){
            $("#register").click(function(){
                $("#respuestas").hide();
            });
        });
        $(function(){
            $("#placa").blur(function(){
                $("#respuestas").show();
            });
        });
        $(function(){
            $("#placa").focus(function(){
                $("#respuestas").show();
            });
        });
    // fin borrar el div respuestas 


});