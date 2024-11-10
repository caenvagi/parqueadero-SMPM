
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animación de Automóvil en CSS</title>    
    <?php require '../logs/head.php'; ?>
</head>
<body>
    <style>
        .loader {
	height: 100vh;
	display: flex;
	align-items: center;
}

.car {   
    background-image: url(../assets/css/car1.png);
    background-repeat: no-repeat;
    background-size: cover;
	position: absolute;   
    
    height: 50px;
    width: 85px;
    

	animation: moverAutomovil 5s  linear infinite ,	moverAutomovil-2 10s  linear infinite;
}

.caseta{
    color: black;
    background-color: black;
    border-radius: 5px;
    height: 80px;
    width: 50px;
    margin-left: 47%;
    margin-top:-40px;
    position: absolute;
    z-index: -1;
}

.bar{
    margin-left: 49%;
	width: 10px;
	height: 40px;	
	background: lightgray;    
    border-radius: 5px;
    animation: moverBarra-1 5s infinite;
    animation-delay: 3.5s;
}

@keyframes moverAutomovil {
	0% {
		left: -200px;}
	50% {
		left: 40%;}	
	/*100% {
		left: calc(100% + 100px);
	}*/
}
@keyframes moverAutomovil-2 {
	50% {
		left: 40%;}
100% {
		left: calc(100% + 100px);
	}
}
@keyframes moverBarra-1 {
	50% { 
        margin-left: 49%;        	
        background: lightgray;
        z-index: -1;
        border-radius: 5px;
        translate: 0px -50px ;

    }
    
}
    </style>
    <div class="loader" id="loader">
        <!-- <svg class="car" width="102" height="40" xmlns="http://www.w3.org/2000/svg">
            <g transform="translate(2 1)" stroke="#002742" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                <path class="car__body" d="M47.293 2.375C52.927.792 54.017.805 54.017.805c2.613-.445 6.838-.337 9.42.237l8.381 1.863c2.59.576 6.164 2.606 7.98 4.531l6.348 6.732 6.245 1.877c3.098.508 5.609 3.431 5.609 6.507v4.206c0 .29-2.536 4.189-5.687 4.189H36.808c-2.655 0-4.34-2.1-3.688-4.67 0 0 3.71-19.944 14.173-23.902zM36.5 15.5h54.01" stroke-width="3"/>
                <ellipse class="car__wheel--left" stroke-width="3.2" fill="#FFF" cx="83.493" cy="30.25" rx="6.922" ry="6.808"/>
                <ellipse class="car__wheel--right" stroke-width="3.2" fill="#FFF" cx="46.511" cy="30.25" rx="6.922" ry="6.808"/>		
            </g>
        </svg> -->
        <div class="car"></div>
        <div class="caseta"></div>
        <div class="bar"></div>
        
            
        </div>
</body>
<script>
     //Efecto Pre-Carga
     $(document).ready(function() {
        $(window).on("load", function() {
                    $(".loader").fadeOut(10000);
                });
    });
</script>
</html>

    