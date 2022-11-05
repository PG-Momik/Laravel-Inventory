
radio1= document.getElementById('btnradio1');
radio2= document.getElementById('btnradio2');
btn1 = document.getElementById('ratioBtnWrapper1')
btn1.addEventListener('click', function(){
    radio1.checked = true;
});
btn2 = document.getElementById('ratioBtnWrapper2')
btn2.addEventListener('click', function(){
    radio2.checked = true;
});
