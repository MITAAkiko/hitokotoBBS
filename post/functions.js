$(function() {
    $('#expantion img')
    .hover(
        function(){
            $(this).stop().animate({
                'width':'130px',
                'height':'130px',
                'marginBottom':'0px'
            },'fast');
        },
        function () {
            $(this).stop().animate({
                'width':'80px',
                'height':'80px',
                'marginBottom':'0px'
            },'fast');
        }
    );
});
function ShowLength( str ) {
    document.getElementById("inputlength").innerHTML = str.length + "/102文字";
 }