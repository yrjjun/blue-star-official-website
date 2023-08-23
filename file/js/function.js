function button(id,status){
    $.get("./function.php?id="+id+"&status="+status, function (data) {
        window.location.href = "./function.php";
    });
}