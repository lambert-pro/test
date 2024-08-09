onmessage = function(e){
    //通过e.data获取前台发送来的数据
    var d = e.data;
    var str = d.split("").reverse().join("");
    postMessage(str);
};
