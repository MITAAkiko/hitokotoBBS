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
                'width':'70px',
                'height':'70px',
                'marginBottom':'0px'
            },'fast');
        }
    );
});
