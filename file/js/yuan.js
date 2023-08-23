function change(money){
    if((money * Number($("#money").val())) == 0){
        $("#now_money").text("0");
    }else if((money * Number($("#money").val())) <= 0.01){
        $("#now_money").text("0.01");
    }else{
        $("#now_money").text(money * Number($("#money").val()));
    }
    
}
function buy(id, product_id){
    $.get("./?path=product&page=index&product_id=" + product_id + "&mode=continue&id=" + id + "&number=" + $("#money").val(), function (data) {
        if(JSON.parse(data).code==200){
            alert("续费成功");
            location.reload();
        }else if(JSON.parse(data).code==400){
            alert("余额不足");
        }
    });
}