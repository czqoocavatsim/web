$(document).ready(function () {
    if ($("body").data("theme") == "system") {
        if (window.matchMedia) {
            if(window.matchMedia('(prefers-color-scheme: dark)').matches){
                $("body").attr("data-theme", "dark")
            } else {
                $("body").attr("data-theme", "light")
            }
        } else {
            $("body").attr("data-theme", "light")
        }
    }
})
