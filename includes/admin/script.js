jQuery(document).ready(function($) {

    $("#provider input:radio").click( function() {
        var provider = $("#provider input:radio:checked").val();

        if( provider == 'vimeo') {
            $("#vimeo-field").addClass("show-wrapper");
            $("#vimeo-field").removeClass("hide");
        } else {
            $("#vimeo-field").removeClass("show-wrapper");
            $("#vimeo-field").addClass("hide");
        }
        if( provider == 'wistia') {
            $("#wistia-field").addClass("show-wrapper");
            $("#wistia-field").removeClass("hide");
        } else {
            $("#wistia-field").removeClass("show-wrapper");
            $("#wistia-field").addClass("hide");
        }
        if( provider == 'youtube') {
            $("#youtube-field").addClass("show");
            $("#youtube-field").removeClass("hide");
            $('#youtube_option').val(this.checked);
        } else {
            $("#youtube-field").removeClass("show");
            $("#youtube-field").addClass("hide");
            $('#youtube_option').val(this.checked);
        }
    })

    // $("#youtube_option").change(function() {
    //     if(this.checked) {
    //         $("#youtube-field").addClass("show");
    //         $("#youtube-field").removeClass("hide");
    //         $('#youtube_option').val(this.checked);
    //     } else {
    //         $("#youtube-field").removeClass("show");
    //         $("#youtube-field").addClass("hide");
    //         $('#youtube_option').val(this.checked);
    //     }
    // });
});