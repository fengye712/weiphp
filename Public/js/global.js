//图片滚动列表 mengjia 070816
    var Speed = 15; //速度(毫秒)
    var Space = 5; //每次移动(px)
    var PageWidth = 136; //翻页宽度
    var fill = 5; //整体移位
    var MoveLock = false;
    var MoveTimeObj;
    var Comp = 0;
    var AutoPlayObj = null;
    GetObj("List2").innerHTML = GetObj("List1").innerHTML;
    GetObj('ISL_Cont').scrollLeft = fill;
    GetObj("ISL_Cont").onmouseover = function(){clearInterval(AutoPlayObj);}
    GetObj("ISL_Cont").onmouseout = function(){AutoPlay();}
    AutoPlay();
        function GetObj(objName){if(document.getElementById){return eval('document.getElementById("'+objName+'")')}else{return eval
        ('document.all.'+objName)}}
        function AutoPlay(){ //自动滚动
        clearInterval(AutoPlayObj);
        AutoPlayObj = setInterval('ISL_GoDown();ISL_StopDown();',5000); //间隔时间
        }
        function ISL_GoUp(){ //上翻开始
        //alert("上翻开始");
        clearInterval(MoveTimeObj);
        if(MoveLock) return;
        clearInterval(AutoPlayObj);
        MoveLock = true;
        ISL_ScrUp();
        MoveTimeObj = setInterval('ISL_ScrUp();',Speed);
        }
        function anniu(){
 
        document.getElementById('huadongzuo').className = "img2";
 
        }
        function anniu1(){
 
        document.getElementById('huadongzuo').className = "img1";
 
        }
        function anniu2(){
 
        document.getElementById('huadongyou').className = "img2_a";
 
        }
        function anniu3(){
 
        document.getElementById('huadongyou').className = "img1_a";
 
        }
 
        function ISL_StopUp(){ //上翻停止
        //alert("上翻停止");
        clearInterval(MoveTimeObj);
        if(GetObj('ISL_Cont').scrollLeft % PageWidth - fill != 0){
        Comp = fill - (GetObj('ISL_Cont').scrollLeft % PageWidth);
        CompScr();
        }else{
        MoveLock = false;
        }
        AutoPlay();
        }
        function ISL_ScrUp(){ //上翻动作
        if(GetObj('ISL_Cont').scrollLeft <= 0){GetObj('ISL_Cont').scrollLeft = GetObj
        ('ISL_Cont').scrollLeft + GetObj('List1').offsetWidth}
        GetObj('ISL_Cont').scrollLeft -= Space ;
        }
 
        function ISL_GoDown(){ //下翻开始
        //alert("下翻开始");
        clearInterval(MoveTimeObj);
        if(MoveLock) return;
        clearInterval(AutoPlayObj);
        MoveLock = true;
        ISL_ScrDown();
        MoveTimeObj = setInterval('ISL_ScrDown()',Speed);
        }
        function ISL_StopDown(){ //下翻停止
        //alert("下翻停止");
        clearInterval(MoveTimeObj);
        if(GetObj('ISL_Cont').scrollLeft % PageWidth - fill != 0 ){
        Comp = PageWidth - GetObj('ISL_Cont').scrollLeft % PageWidth + fill;
        CompScr();
        }else{
        MoveLock = false;
        }
        AutoPlay();
        }
        function ISL_ScrDown(){ //下翻动作
        if(GetObj('ISL_Cont').scrollLeft >= GetObj('List1').scrollWidth){GetObj('ISL_Cont').scrollLeft =
        GetObj('ISL_Cont').scrollLeft - GetObj('List1').scrollWidth;}
        GetObj('ISL_Cont').scrollLeft += Space ;
        }
        function CompScr(){
        var num;
        if(Comp == 0){MoveLock = false;return;}
        if(Comp < 0){ //上翻
        //alert("上翻");
        if(Comp < -Space){
        Comp += Space;
        num = Space;
        }else{
        num = -Comp;
        Comp = 0;
        }
        GetObj('ISL_Cont').scrollLeft -= num;
        setTimeout('CompScr()',Speed);
        }else{ //下翻
        //alert("下翻");
        if(Comp > Space){
        Comp -= Space;
        num = Space;
        }else{
        num = Comp;
        Comp = 0;
        }
        GetObj('ISL_Cont').scrollLeft += num;
        setTimeout('CompScr()',Speed);
        }
        }
//选项卡
function setTab(m,n){
 var tli=document.getElementById("menu"+m).getElementsByTagName("li");
 var mli=document.getElementById("main"+m).getElementsByTagName("ul");
 for(i=0;i<tli.length;i++){
  tli[i].className=i==n?"hover":"";
  mli[i].style.display=i==n?"block":"none";
 }
}
