var receiveMessage = function(event){
  var frame = document.getElementById('vrach');
  var obj = {};
  if (event.origin !== "http://frame.vrachrb.ru.atmadev.ru"){
    return;
  }
  obj = JSON.parse(event.data);
  frame.style.width = obj.w;
  frame.style.height = obj.h;

  // console.log('высота: ' + obj.h);
}

var targetFrame = null;
var load = function(event){
  var frame = window.frames.vrach;
  if(targetFrame == null){
    targetFrame = event.target;
  }
  targetFrame.style.height = 0;
  frame.postMessage(
    '1',
    "http://frame.vrachrb.ru.atmadev.ru"
  );
  window.addEventListener("message", receiveMessage, false);
}

setInterval(function(){
  var frame = window.frames.vrach;
  frame.postMessage(
    '1',
    "http://frame.vrachrb.ru.atmadev.ru"
  );
}, 500);
