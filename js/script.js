(function($){
  $(function(){

    $('.button-collapse').sideNav();
      $('.modal').modal({
      dismissible: true, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      inDuration: 150, // Transition in duration
      outDuration: 100, // Transition out duration
      startingTop: '4%', // Starting top style attribute
      endingTop: '10%', // Ending top style attribute
      complete: function() { 
        resetLogin();
        } // Callback for Modal close
    }
  );
    $('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 15, // Creates a dropdown of 15 years to control year
    format: 'yyyy-mm-dd'
  });

  }); // end of document ready
})(jQuery);

/*
*  On page load functions
*/
function onStatoLoad(){
    getLiveData();
    window.setInterval(getLiveData, 60000);
    moment.locale("it");
}

function onControlloLoad(){
    setCurrentState();
}

function onGraficoLoad(){
    moment.locale("it");
    document.getElementById("from_date").value = moment().format("YYYY-MM-DD");
    document.getElementById("to_date").value = moment().add(1, 'day').format("YYYY-MM-DD");
    window.onresize = function(event) {
        updateGraph();
    }
    updateGraph();
}

function onElencoLoad(){
    document.getElementById("from_date").value = moment().format("YYYY-MM-DD");
    document.getElementById("to_date").value = moment().add(1, 'day').format("YYYY-MM-DD");

    updateTable();
    moment.locale("it");
}

/*
*  Page: index.php
*/

//Function that updates the stats in the index page
function updateStats(response){
    //Parse response
    obj = JSON.parse(response)[0];
    //Set temperature value
    var temp = document.getElementById("temperature");
    temp.innerHTML = obj.temperature + "°C";

    var img = document.getElementById("heating_image");
    var tooltip = $('#heating_tooltip');
    if(obj.isOn == 1){
        img.src="images/heating_on.png";
        tooltip.attr("data-tooltip", "Riscaldamento acceso");
        tooltip.tooltip();
    }else{
        img.src="images/heating_off.png";
        tooltip.attr("data-tooltip", "Riscaldamento spento");
        tooltip.tooltip();
    }
    

    var last_update = document.getElementById("last_update");
    last_update.innerHTML = "Ultimo aggiornamento: " + moment(obj.timestamp, "YYYY-MM-DD hh:mm:ss").fromNow();
}

//Function that gets the data from the api for the main page stats
function getLiveData(){
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
        updateStats(xmlHttp.responseText);
    }
    xmlHttp.open("GET", "http://tesinaiot.altervista.org/api/live", true); // true for asynchronous
    xmlHttp.send(null);
}


/*
*  Page: controllo.html
*/
var tempStep = 0.5;
var timerStep = 10;

var timerTarget = 0;

//Function that changes the temperature target in the control page on user input
function changeTemp(how){
    var element = document.getElementById("temperature");
    if(how == "-"){
        var temp = Number(element.innerHTML) - tempStep;
    }
    else if (how == "+"){
        var temp = Number(element.innerHTML) + tempStep;
    }

    if(temp%1 == 0){
        temp += ".0";
    }

    element.innerHTML = temp;
}

//Function that changes the timer target in the control page on user input
function changeTimer(how){

    if(how == "+"){
        timerTarget += timerStep;
    }else if (how == "-" && timerTarget > 0){
        timerTarget -= timerStep;
    }

    var timerString = Math.floor(timerTarget/60) + "h " + timerTarget%60 + "m";
    document.getElementById("timer").innerHTML = timerString;

    console.log(timerTarget);
}

//Function that sends the target temperature to the api page
function setTemperature(){
    var temperature = document.getElementById("temperature").innerHTML;
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "http://tesinaiot.altervista.org/api/controls", true);
    xmlHttp.onreadystatechange = function (){
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            console.log(xmlHttp.responseText);
            var response = JSON.parse(xmlHttp.responseText);
            if(response.error == "none"){
                Materialize.toast('Temperatura impostata!', 4000);
            }else if(response.error == "auth"){
                Materialize.toast('Errore di autenticazione', 4000);
            }
        }
    };
    var data = JSON.stringify({"type": "temp", "value": temperature});
    xmlHttp.send(data);
}

//Function that sends the toggle state to the api page
function setToggle(){
    //Save the toggle target
    var val;
    if(document.getElementById("thermostat_state").checked){
        val = 1;
    }else{
        val = 0;
    }


    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "http://tesinaiot.altervista.org/api/controls", true);
    xmlHttp.onreadystatechange = function (){
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            console.log(xmlHttp.responseText);
            var response = JSON.parse(xmlHttp.responseText);
            if(response.error == "none"){
                Materialize.toast('Comando inviato', 4000);
            }else if(response.error == "auth"){
                Materialize.toast('Errore di autenticazione', 4000);
            }
        }
    };
    var data = JSON.stringify({"type": "toggle", "value": val.toString()});
    console.log(data);
    xmlHttp.send(data);
}
//Function that sends the target timer to the api page
function setTimer(){
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "http://tesinaiot.altervista.org/api/controls", true);
    xmlHttp.onreadystatechange = function (){
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            console.log(xmlHttp.responseText);
            var response = JSON.parse(xmlHttp.responseText);
            if(response.error == "none"){
                Materialize.toast('Timer impostato!', 4000);
            }else if(response.error == "auth"){
                Materialize.toast('Errore di autenticazione', 4000);
            }
        }
    };
    var data = JSON.stringify({"type": "timer", "value": timerTarget});
    xmlHttp.send(data);
}

//Function that reads from the api page the current state of the thermostat (ON-OFF)
//and sets the toggle accordingly
function setCurrentState(){
    console.log("setting curr state");
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            var val = JSON.parse(xmlHttp.responseText)[0].isOn;
            if(val == 1){
                document.getElementById("thermostat_state").checked = false;
            }
            else{
                document.getElementById("thermostat_state").checked = true;
            }

        }
    }
    xmlHttp.open("GET", "http://tesinaiot.altervista.org/api/live", true); // true for asynchronous
    xmlHttp.send(null);
}

//Function that performs the login request
function login()
{
    //Stores the password in a variable
    var password = document.getElementById("password").value;
	$.post("login.php", { pass: password },
		function(data) {
            console.log(data);
            //Get the "wrong password" div
            var error = document.getElementById("login-error");
            //Check if there was an error
            if(JSON.parse(data).error == "auth"){
                console.log("auth error");
                //Show the div
                error.style.display= "block";
            }else if(JSON.parse(data).error == "none"){
                //Reload the page
                location.reload();
            }
        });
}

function logout(){
    console.log("logout");
    $.post("logout.php", {},
        function(data) {
            location.reload();
        });
}

function resetLogin(){
    //Remove error div
    var error = document.getElementById("login-error");
    error.style.display = "none";
    //reset password field
    var pass = document.getElementById("password");
    pass.value = "";
}

/*
*  Page: grafico.html
*/

//Function that starts the graph update
function updateGraph(){
    var from = document.getElementById("from_date").value;
    var to = document.getElementById("to_date").value;

    getGraphMeasurements(from, to);
}

//Function that draws the graph
function drawGraph(response){
    var graphData = generateData(response);

    var plot_options = {
        axisLabels: {
            show: true
        },
        series: {
            downsample: {
                threshold: document.getElementById("points-slider").value
            },
            lines: { show: true },
            points: { show: true, radius: 5 },
            shadowSize: 0
        },
        grid:{
            borderColor: 'transparent',
            borderSize: 20,
            hoverable: true
        },
        xaxis: {
            mode: "time",
            //timeformat: "%d/%m",
            minTickSize: [1, "hour"]
        },
        yaxis: {
            tickDecimals: 1,
            axisLabel: "Temperatura(°C)",
            axisLabelPadding: 10
        }
    };

    $.plot($('#graph-lines'), graphData, plot_options);

    $("<div id='tooltip'></div>").css({
        position: "absolute",
        display: "none",
        border: "1px solid #fdd",
        padding: "2px",
        "background-color": "#ffc107",
        opacity: 0.80
    }).appendTo("body");

    $("#graph-lines").bind("plothover", function (event, pos, item) {
        $("#hoverdata").text("ciao");
        if (item) {

            var data = moment(item.datapoint[0]).format("DD/MM");
            var ora = moment(item.datapoint[0]).utcOffset(0).format("HH:mm");
            var temp = item.datapoint[1].toFixed(2);

            
            if($(window).width() - item.pageX < 180){
                item.pageX -= 150;
            }

            $("#tooltip").html("Data: " + data + "<br>" + "Ora: " + ora + "<br>" + "Temperatura: " + temp + "°C")
            .css({top: item.pageY+10, left: item.pageX+10})
            .fadeIn(200);
        } else {
            $("#tooltip").hide();
        }

    });

}

//Function that generates the data in the correct format by first parsign the json response
function generateData(response){
    var obj = JSON.parse(response);

    var data = new Array();

    for(i = 0; i < obj.length; i++){
        var time = moment(obj[i].timestamp, "YYYY-MM-DD hh:mm:ss").add(2, "h").valueOf();
        data[i] = [time, obj[i].temperature];
    }

    graphData = [{
        data: data,
        color: '#FFD740'
    }
];
return graphData

}

//Function that gets the data from the api for the graph
function getGraphMeasurements(from, to){
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            drawGraph(xmlHttp.responseText);
        }
    }
    var requestUrl = "http://tesinaiot.altervista.org/api/measurements?";
    if(from != ""){
        requestUrl = requestUrl.concat("start=" + from +"&");
    }
    if(to != ""){
        requestUrl = requestUrl.concat("end=" + to);
    }
    xmlHttp.open("GET", requestUrl, true); // true for asynchronous
    xmlHttp.send(null);
}

/*
*  Page: elenco.php
*/
function updateTable(){
    var from = document.getElementById("from_date").value;
    var to = document.getElementById("to_date").value;

    getTableMeasurements(from, to);

}

function getTableMeasurements(from, to){
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            drawTable(xmlHttp.responseText);
        }
    }
    var requestUrl = "http://tesinaiot.altervista.org/api/measurements?";
    if(from != ""){
        requestUrl = requestUrl.concat("start=" + from +"&");
    }
    if(to != ""){
        requestUrl = requestUrl.concat("end=" + to);
    }
    xmlHttp.open("GET", requestUrl, true); // true for asynchronous
    xmlHttp.send(null);
}

function drawTable(response){
    var obj = JSON.parse(response);

    var tbody = document.createElement('tbody');
    tbody.setAttribute('id', 'table-body');

    var old_tbody = document.getElementById("table-body");
    for(i = 0; i < obj.length; i++){
        var data = new Array();
        data[0] = i;
        data[1] = obj[i].timestamp;
        data[2] = obj[i].temperature;
        data[3] = (obj[i].isOn == 1) ? "Acceso" : "Spento";

        var tr = document.createElement('tr');

        for(j = 0; j < data.length; j++){
            var td = document.createElement('td');
            td.append(data[j]);
            tr.appendChild(td);
        }


        tbody.appendChild(tr);
    }
    old_tbody.parentNode.replaceChild(tbody, old_tbody);
}
