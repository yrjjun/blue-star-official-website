function pimodal(){
    document.getElementById("modal").style.display='none';
}
function returnPickduckModalInput(a,b,c){
    if(b=='sign'){
    sign.setDisplay(false)
    location.reload();
    }else if(b=="login"){
        login.setDisplay(false)
        location.reload();
    }
}
class PickduckModal{
  //构造静态框基本内容
  constructor(title, text,textleft,textright,textright2="确定") {
      //初始化
      this.setTitle(title,'title');
      this.setTitle(text,'text');
      this.setButton(textleft,'text-left');
      this.setButton(textright,'text-right');
      this.setButton(textright2,'text-right2');
      this.setButton(null,'textplay');
      this.setButton(null,'text-right-form-s');
  }
  //设置整体是否显示
  setDisplay(v){
      document.getElementById("modal").style.display=v ? 'inline' :'none';
  }
  //设置标题或内容
  setTitle(v,id){

      if(v.includes('<img')){ 
          document.getElementById('mainmodal').style.maxWidth='100%';
          document.getElementById('mainmodal').style.maxHeight=(document.documentElement.clientHeight-100)+'px';
             
      }
      document.getElementById(id).innerHTML=v;
  }
  //设置按钮内容
  setButton(v,id){
      document.getElementById(id).style.display=v ? 'inline' :'none';
      if(v)  document.getElementById(id).innerHTML=v;
  }
  //设置跳转
  setUrl(v,id,target='_self'){
      document.getElementById(id).href=v;
      document.getElementById(id).target=target;
  }
  
  //设置回调函数，调用returnPickduckModalInput()的js函数。传入null，标识符，预参数
  setReturnPickduckModalInput(v,e,id){
      document.getElementById(id).onclick = Function ("returnPickduckModalInput(null,'"+v+"','"+e+"');");
  }
  
  //设置提交表单
  setForm(id){
      document.getElementById('text-right-form-s').style.display='inline';
      document.getElementById('text-right').style.display='none';
      document.getElementById('text-right-form-s').onclick = Function ("document.getElementById('"+id+"').submit();");
  }
  
  //设置输入框，并且点击积极按钮，调用returnPickduckModalInput()的js函数。传入输入框值，标识符，预参数
  setInputText(v,id,e=null,v1=null){
      document.getElementById('text-right-form-s').style.display='inline';
      document.getElementById('text-right').style.display='none';
      document.getElementById('textplay').style.display=v ? 'inline' :'none';
      document.getElementById('textplay').placeholder=v;
      document.getElementById('textplay').value=v1;
      document.getElementById('text-right-form-s').onclick = Function ("returnPickduckModalInput(document.getElementById('textplay').value,'"+id+"','"+e+"');");
  }
}