$(function() {

    var j=-1;
    for (var i in images) {
        // generate fotorama DOM
        if (images[i].type != 'album') {
            if (images[i].type == 'video') {
                $('<a href="'+images[i].url+'" data-video="true"><img data-thumb="'+images[i].thumburl+'"></a>').appendTo('.fotorama');
            } else {
                $('<a href="'+images[i].url+'" data-caption="' + images[i].caption + '" ><img data-thumb="'+images[i].thumburl+'"></a>').appendTo('.fotorama');
            }
        }

        // generate thumbnail DOM
        thumbhtml = '<div class="thumbnail">';
        if (images[i].type == 'album') {
            thumbhtml = thumbhtml + '<a data-ajax="false" href="' + images[i].url + '">'
            thumbhtml = thumbhtml + '<img style="' + images[i].style + '" src="' + img_url + 'loader.gif" title="' + images[i].caption + '" data-src="' + images[i].thumburl +'">';
            thumbhtml = thumbhtml + '<div class="thumb-title">' + images[i].caption + '</div></a>';
        } else {
            j++;
            thumbhtml = thumbhtml + '<img onclick="startFotorama(' + j + ');" src="' + img_url + 'loader.gif" style="' + images[i].style + '" title="' + images[i].caption + '" data-src="' + images[i].thumburl +'">';
        }
        if (images[i].type == 'video') {
            thumbhtml = thumbhtml + '<div class="thumb-video" onclick="startFotorama(' + j + ');"></div>';
        }
        thumbhtml = thumbhtml + '</div>';
        $(thumbhtml).appendTo('.thumbs');
    }

    $(".thumbnail img").unveil(100, function() {
        $(this).load(function() {
            this.style.opacity = 1;
        });
    });


    // fotorama init
    fr = $('.fotorama');
    th = $('.thumbs');
    // you can customize the slideshow settings according to http://fotorama.io/customize
    fr.fotorama({
        width: '100%',
        height: '100%',
        allowfullscreen: true,
        nav: 'thumbs',
        fit: 'contain',
        captions: false
    });

    fr.addClass("hide");
    fr.on('fotorama:fullscreenexit ',
        function (e, fotorama, extra) {
            fr.addClass("hide");
            th.removeClass("hide");
        }
    )

    // album add form autofill
    $("#g-add-album-form input[name=title]").change(
        function() {
            $("#g-add-album-form input[name=name]").val(
                $("#g-add-album-form input[name=title]").val()
                    .replace(/[\s\/]+/g, "-").replace(/\.+$/, ""));
            $("#g-add-album-form input[name=slug]").val(
                $("#g-add-album-form input[name=title]").val()
                    .replace(/[^A-Za-z0-9-_]+/g, "-")
                    .replace(/^-+/, "")
                    .replace(/-+$/, ""));
        });

    $("#g-add-album-form input[name=title]").keyup(
        function() {
            $("#g-add-album-form input[name=name]").val(
                $("#g-add-album-form input[name=title]").val()
                    .replace(/[\s\/]+/g, "-")
                    .replace(/\.+$/, ""));
            $("#g-add-album-form input[name=slug]").val(
                $("#g-add-album-form input[name=title]").val()
                    .replace(/[^A-Za-z0-9-_]+/g, "-")
                    .replace(/^-+/, "")
                    .replace(/-+$/, ""));
        });

    // item add form autofill
    $("#item_file").change(
        function() {
        //strip the extention
        input = $(this).val();
        //title = "C:\\fakepath\\" + input.substr(0, input.lastIndexOf('.')) || input;
        title = input.substr(0, input.lastIndexOf('.')) || input;
        //set the default name of the title
        $('#item_title').val(title);
    });
});

function startFotorama(index) {
    fr.removeClass("hide");
    th.addClass("hide");

    var $fotoramaDiv = fr.fotorama();
    var fotorama = $fotoramaDiv.data('fotorama');

    fotorama.show(index);
    fotorama.requestFullScreen();
}
