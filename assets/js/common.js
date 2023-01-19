$(document).ready(function () {

    $('.MainNavbar__searchBtn').click(function () {
        $(this).next().toggle()
    })

    $(document).mouseup(function (e) {
        if ($(e.target).closest(".searchInput.MainNavbar__search").length
            === 0) {
            $(".searchInput.MainNavbar__search input").hide();
        }
    });

    $('.tab-menu li a').on('click', function () {
        var target = $(this).attr('data-rel');
        $('.tab-menu li a').removeClass('active');
        $(this).addClass('active');
        $("#" + target).fadeIn('slow').siblings(".tab-box").hide();
        return false;
    });


    var b = $('.tab-menu li:first-child a').text();
    $('.tab-menu').prepend('<li class="tableEl-pre-heading__title d-lg-none d-block"><span class="toggleTabDropdown active">' + b + '</span></li>');

    function tabsDropdown() {
        if ($(window).width() < 992) {
            $(document).on('click', '.tab-menu-wrapper .toggleTabDropdown', function () {
                $(this).parent().parent().parent().toggleClass('tab-menu-wrapper__open');
            });

            $('.tab-menu li a').click(function () {
                var t = $(this).text();
                $(this).parent().parent().parent().removeClass('tab-menu-wrapper__open');
                $('.tab-menu li .toggleTabDropdown').text(t);
            });
        }
    }

    $(window).resize(function () {
        tabsDropdown();
    }); tabsDropdown();


    $(document).mouseup(function (e) {
        if ($(e.target).closest(".tab-menu-wrapper").length === 0) {
            $('.tab-menu-wrapper').removeClass('tab-menu-wrapper__open');
        }
    });


});

function showTokensPane() {
    $("#tokens").addClass('active');
    $("#common").removeClass('active');
    $('.asideToolbar__item').removeClass('asideToolbar__item-active');
    $(event.target).closest('.asideToolbar__item').addClass('asideToolbar__item-active');
    return false;
}

function showWalletPane() {
    $("#tokens").removeClass('active');
    $("#common").addClass('active');
    $('.asideToolbar__item').removeClass('asideToolbar__item-active');
    $(event.target).closest('.asideToolbar__item').addClass('asideToolbar__item-active');
    return false;
}

function copyAddress(el) {
    let addr = $(el).closest('.address-container').find('.transAddress .addr-full').html();
    copyTextToClipboard(addr)
}

function showFullAddress(el) {

    let parent = $(el).closest('.d-flex').find('.transAddress');
    if (parent.find('.addr-cut').hasClass('hide')) {
        parent.find('.addr-cut').removeClass('hide');
        parent.find('.addr-full').addClass('hide');
    } else {
        parent.find('.addr-cut').addClass('hide');
        parent.find('.addr-full').removeClass('hide');
    }

}

function copyTextToClipboard(text) {
    var textArea = document.createElement("textarea");

    //
    // *** This styling is an extra step which is likely not required. ***
    //
    // Why is it here? To ensure:
    // 1. the element is able to have focus and selection.
    // 2. if the element was to flash render it has minimal visual impact.
    // 3. less flakyness with selection and copying which **might** occur if
    //    the textarea element is not visible.
    //
    // The likelihood is the element won't even render, not even a
    // flash, so some of these are just precautions. However in
    // Internet Explorer the element is visible whilst the popup
    // box asking the user for permission for the web page to
    // copy to the clipboard.
    //

    // Place in the top-left corner of screen regardless of scroll position.
    textArea.style.position = 'fixed';
    textArea.style.top = 0;
    textArea.style.left = 0;

    // Ensure it has a small width and height. Setting to 1px / 1em
    // doesn't work as this gives a negative w/h on some browsers.
    textArea.style.width = '2em';
    textArea.style.height = '2em';

    // We don't need padding, reducing the size if it does flash render.
    textArea.style.padding = 0;

    // Clean up any borders.
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';

    // Avoid flash of the white box if rendered for any reason.
    textArea.style.background = 'transparent';


    textArea.value = text;

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        var successful = document.execCommand('copy');
        successful = document.execCommand('copy');
        var msg = successful ? 'successful' : 'unsuccessful';
        console.log('Copying text command was ' + msg);
        //navigator.clipboard.writeText(selectedAccount);
    } catch (err) {
        console.log('Oops, unable to copy');
    }

    document.body.removeChild(textArea);
}