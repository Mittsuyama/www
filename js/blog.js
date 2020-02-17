var lastExpand = 0;
var dis = 50;
var dif = $("#contentsBox").offset().top - $("#blogRightContainer").offset().top;
var leftDis = $("#blogRightContainer").offset().left;
var tTop = $("#contentsBox").offset().top;

$(function () {
    var rewardClick = false;
    $("#rewardButton").click(function () {
        if (!rewardClick) {
            $("#reward").css("display", "block");
            rewardClick = true;
        }
        else {
            $("#reward").css("display", "none");
            rewardClick = false;
        }
    })

    // $("code").each(function(){
    //     $(this).html("<ol><li>" + $(this).html().replace(/\n/g,"\n</li><li>") +"\n</li></ol>");
    // });

    $("#comText").on('input propertychange', function () {
        var userDesc = $(this).val();
        var len;
        if (userDesc) {
            len = userDesc.length;
        }
        else {
            len = 0
        }
        $("#numLimit").html(len + '/500');
    });

    boxFixedJudge();
    headListHightlight();
    $(window).scroll(function () {
        boxFixedJudge();
        headListHightlight()
    });
});

function boxFixedJudge() {
    if ($(window).scrollTop() + dis >= tTop) {
        $("#blogRightContainer").css("width", $("#blogRightContainer").outerWidth() + "px");
        $("#blogRightContainer").css("position", "fixed");
        $("#blogRightContainer").css("top", dis - dif + "px");
        $("#blogRightContainer").css("left", leftDis + "px");
        $("#blogRightContainer").css("float", "none");
        // $("#rightBox").css("border-left", "1px solid transparent");
        $(".right-down-fixed").css("opacity", "1");
    }
    else {
        $("#blogRightContainer").css("position", "relative");
        $("#blogRightContainer").css("width", "33%");
        $("#blogRightContainer").css("top", "0px");
        $("#blogRightContainer").css("left", "0px");
        $("#blogRightContainer").css("float", "right");
        // $("#rightBox").css("border-left", "1px solid #ccc");
        $(".right-down-fixed").css("opacity", "0");
    }
}

function headListHightlight() {
    var list = $(".head2");
    var pos = 0;
    var count = list.length;
    // alert($(list[0]).offset());
    for (var i = 0; i < count; i++) {
        // alert($(window).scrollTop() + " " + $(list[i]).offset().top);
        if ($(window).scrollTop() + 80 < $(list[i]).offset().top) break;
        pos = i;
    }
    if (lastExpand != pos) {
        if ($("#h2text" + lastExpand).hasClass("h2listHover")) {
            $("#h2text" + lastExpand).removeClass("h2listHover");
        }
        $("#h2Box" + lastExpand).slideUp();
    }
    $("#h2text" + pos).addClass("h2listHover");
    $("#h2Box" + pos).slideDown();
    lastExpand = pos;
}