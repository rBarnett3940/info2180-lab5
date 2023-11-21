window.onload = function () {

    var lookupButton = document.getElementById("lookup");
    var citiesButton = document.getElementById("cities");
    var input = document.getElementById("country");
    var result = document.getElementById("result");
    
    lookupButton.addEventListener("click", function (e) {
        e.preventDefault();
        makeRequest('countries');
    });

    citiesButton.addEventListener("click", function (e) {
        e.preventDefault();
        makeRequest('cities');
    });

    function makeRequest(context) {
        var httpRequest = new XMLHttpRequest();
        var url = "http://localhost:8888/world.php";
        var info = input.value;
        var exec = `?country=${info}&context=${context}`;

        httpRequest.onreadystatechange = function () {
            if (httpRequest.readyState === XMLHttpRequest.DONE) {
                if (httpRequest.status === 200) {
                    result.innerHTML = httpRequest.responseText;
                } else {
                    alert("Unable to process request");
                }
            }
        };

        httpRequest.open('GET', url + exec, true);
        httpRequest.send();
    }

};
