jQuery(document).ready(function ($) {

    function pesChangeURL(url, param, value) {
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var aditionalURL = tempArray[1];
        var temp = "";
        if (aditionalURL) {
            var tempArray = aditionalURL.split("&");
            for (var i in tempArray) {
                if (tempArray[i].indexOf(param) == -1) {
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }
        var element = temp + param + "=" + value;
        var finalURL = baseURL + "?" + newAdditionalURL + element;
        return finalURL;
    }

    if (document.getElementById("allow-arrows")) {
        function checkKey(e) {
            e = e || window.event;
            $left_url = $("#previous-url").attr("href");
            $right_url = $("#next-url").attr("href");
            // left arrow
            if (e.keyCode == '37' && $left_url) {
                window.location.replace($left_url);
            }
            // right arrow
            else if (e.keyCode == '39' && $right_url) {
                window.location.replace($right_url);
            }
        }

        document.onkeydown = checkKey;
    }

    $(".info-gallery, .info-gallery-close").click(function (event) {
        $(".info-gallery-help").toggleClass("help-hide");
    });

    $("a#hideShowDetails, img.large-attachment").click(function (event) {
        $(".pes-image-data").toggle(500, "easeOutSine", function () {
            if ($(this).is(":visible")) {
                $("a#hideShowDetails").text("Less info");
            }
            else {
                $("a#hideShowDetails").text("More info");
            }
        });
        $(".pes-image").toggleClass("col-xs-12 col-sm-12 col-md-9", 1000, "easeOutSine");
    });

    $("div#img-box").click(function (event) {
        $("div#img-box").removeClass("pressed");
        $(this).addClass("pressed", 200, "easeOutSine");
        $("div#img-attr").html($(this).data("dimension") + ' / ' + $(this).data("size"));
        $("button#img-download-btn").attr("onClick", "window.location.href='" + $(this).data("id") + "'");
        var url = $("input[name='download_url']").val();
        var new_url = pesChangeURL(url, 'size', $(this).data("size"));
        $("form#gallery-download").attr("action", new_url);
    });

    $("div#video-box").click(function (event) {
        $("div#video-box").removeClass("pressed-video");
        $(this).addClass("pressed-video", 200, "easeOutSine");
        $("div#video-attr").html($(this).data("dimension") + ' / ' + $(this).data("size"));
        $("button#video-download-btn").attr("onClick", "window.location.href='" + $(this).data("id") + "'");
    });

    if (document.getElementById("allow-downloads")) {
        function pesUpdateDownloadButton(totalSelected) {
            if (totalSelected > 0) {
                $("#pes-download").removeClass("btn-disabled").prop("disabled", false).removeAttr("title");
                var photos = [],
                    i = 0;
                $("div.item-capsule.selected").each(function (index) {
                    photos[i++] = $(this).attr('id');
                });
                $("input[name='photos']").val(photos);
            }
            else {
                $("#pes-download").addClass("btn-disabled").prop("disabled", true).attr("title", "Select photos to download first");
                $("input[name='photos']").val('');
            }
        }


        $("a#selectAllPhotos").click(function (event) {
            $("div.item-capsule").addClass("selected");
            $("i.fa.fa-check-square").show();
            totalSelected = $("div.item-capsule.selected").length;
            $("span#totalSelected").text(totalSelected);
            pesUpdateDownloadButton(totalSelected);
        });
        $("a#clearAllPhotos").click(function (event) {
            $("div.item-capsule").removeClass("selected");
            $("i.fa.fa-check-square").hide();
            $("span#totalSelected").text("0");
            pesUpdateDownloadButton(0);
        });
        $("div.item-capsule.download").click(function (event) {
            $(this).toggleClass("selected");
            $(this).children("i.fa.fa-check-square").toggle();
            totalSelected = $("div.item-capsule.selected").length;
            $("span#totalSelected").text(totalSelected);
            pesUpdateDownloadButton(totalSelected);
        });

        $("div.item-capsule.download").hover(
            function () {
                $(this).children(".item-title.download").show();
            }, function () {
                $(this).children(".item-title.download").hide();
            }
        );
    }

    // Abruptly remove the comment button on the share links.
    if (document.getElementsByClassName("post-share")) {
        $(".post-share:last-child").children().last().hide();
    }

    if (document.getElementById("confirm_user_password-15989")) {
        $("#confirm_user_password-15989").attr("placeholder", "Confirm Password *");
    }
    // Abruptly change the label for the ultimate member plugin.
    // TODO: Move this to a plugin and template.
    if (document.getElementsByClassName("um-field-password_reset_text")) {
        $("[data-key='password_reset_text']").children().html("<div style=\"text-align: center\">To reset your password, please enter your E-mail address below</div>");
        $("#username_b").attr("placeholder", "E-mail *");
    }

});