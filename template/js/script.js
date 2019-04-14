function initLightGallery() {
    $(".demo-gallery li a").click(function (e) {
        e.preventDefault();
        var $lg = $(this).parents('#lightgallery');
        $lg.lightGallery();
        $lg.on('onBeforeClose.lg',function(event, index, fromTouch, fromThumb){
            try{$lg.data('lightGallery').destroy(true);}catch(ex){}
        });
    });
}

function initRateit() {
    $('.rateit').rateit();
}

function initRateitHandler() {
    $(".rateit").bind('rated', function(event) {
        var ri = $(this);
        var value = ri.rateit('value');
        var productID = ri.data('productid');
        ri.rateit('readonly', true);
        $.ajax({
            type: 'POST',
            url: '/rateit_save/',
            data: {
                image_id: productID,
                value: value
            },
            success: function(data) {
                var sort = $('#link-sort-rateit');
                if (sort.hasClass('rateit-display-none')) {
                    sort.removeClass('rateit-display-none').addClass('rateit-display');
                }
            }
        });
    });
}

function addYaDiskFileContent(data){
    var content = '';
    if(data.length > 0 && $.isArray(data)) {
        content += '<ul id="lightgallery" class="list-unstyled row">';
        $.each(data, function (key, value) {
            content += '<li class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2 col-xxxl-2 col-xxxxl-2" data-id="' + value.md5 + '" data-src="' + value.file + '">'
            content += '<span class="flex-image-card"><span class="flex-image-conteiner"><a href="">';
            content += '<img class="img-responsive" src="' + value.preview + '" alt="' + value.name + '" />';
            content += '</a></span></span>';
            content += '<div class="rateit-container">';
            content += '<div class="rateit" data-productid="' + value.md5 + '" data-rateit-readonly="' + (value.rateit > 0 ? 'true' : 'false') + '" data-rateit-min="0" data-rateit-max="5" data-rateit-value="' + value.rateit + '" data-rateit-step="1"></div>';
            content += '</div>';
            content += '</li>';
        });
        content += '</ul>';
        $('.demo-gallery').html(content);
        initLightGallery();
        initRateit();
        initRateitHandler();
    }
}

$(document).ready(function(){

    initLightGallery();

    initRateitHandler();

    $('.pagination a').click(function (e) {
        e.preventDefault();
        var page = $(this).attr('data-page');
        var sortname = $('#form-sort-name').val();
        var rateit = $('#form-sort-rateit').val();
        $.ajax({
            type: 'POST',
            url: '/gallery_ajax/',
            dataType: 'json',
            data: {
                page: page,
                sortname: sortname,
                rateit: rateit
            },
            success: function(data) {
                $('#form-sort-page').val(page);
                addYaDiskFileContent(data);
            }
        });
    });

    $('#link-sort-name').click(function (e) {
        e.preventDefault();
        var sortname = $(this).attr('data-sort-name');
        var icon = $(this).find('i');
        var page = $('#form-sort-page').val();
        if(sortname === 'name'){
            $(this).attr('data-sort-name', '-name');
        }else{
            $(this).attr('data-sort-name', 'name');
        }

        if (icon.hasClass('fa-arrow-up')) {
            icon.removeClass('fa-arrow-up').addClass('fa-arrow-down');
        } else if(icon.hasClass('fa-arrow-down')) {
            icon.removeClass('fa-arrow-down').addClass('fa-arrow-up');
        }else{
            icon.addClass('fa-arrow-up');
        }

        $.ajax({
            type: 'POST',
            url: '/gallery_ajax/',
            dataType: 'json',
            data: {
                page: page,
                sortname: sortname
            },
            success: function(data) {
                $('#form-sort-name').val(sortname);
                $('#form-sort-rateit').attr('value', '');
                addYaDiskFileContent(data);
            }
        });
    });

    $('#link-sort-rateit').click(function (e) {
        e.preventDefault();
        var rateit = $(this).attr('data-sort-rateit');
        var icon = $(this).find('i');
        var page = $('#form-sort-page').val();
        if(rateit === 'desc'){
            $(this).attr('data-sort-rateit', 'ask');
        }else{
            $(this).attr('data-sort-rateit', 'desc');
        }

        if (icon.hasClass('fa-arrow-up')) {
            icon.removeClass('fa-arrow-up').addClass('fa-arrow-down');
        } else if(icon.hasClass('fa-arrow-down')) {
            icon.removeClass('fa-arrow-down').addClass('fa-arrow-up');
        }else{
            icon.addClass('fa-arrow-up');
        }

        $.ajax({
            type: 'POST',
            url: '/gallery_ajax/',
            dataType: 'json',
            data: {
                page: page,
                rateit: rateit
            },
            success: function(data) {
                var cont = '';
                $('#form-sort-rateit').val(rateit);
                $('#form-sort-name').attr('value', '');
                addYaDiskFileContent(data);
            }
        });
    });
});
