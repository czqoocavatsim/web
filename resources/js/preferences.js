$(document).ready(function () {

    //Dropdown change
    $(".pref-dropdown").change(function(){

        //Check whether the field name is present in the data
        let preferenceName = this.name

        if (!preferenceName || preferenceName == '') {
            //Error
            Toastify({
                text: "Error changing preference (data 'name' not found)",
                duration: 5000,
                close: true,
                gravity: "bottom", // `top` or `bottom`
                position: 'right', // `left`, `center` or `right`
                backgroundColor: '#ff4444',
                offset: {
                    x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                    y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                },
                stopOnFocus: true, // Prevents dismissing of toast on hover
            }).showToast();

            //Enable and hide loading icon
            $(select).toggleClass('d-none');
            $(`#${preferenceName}_loading`).toggleClass('d-none');

            return
        }

        //Disable and show loading icon
        let select = this
        $(select).toggleClass('d-none');
        $(`#${preferenceName}_loading`).toggleClass('d-none');

        //Make ajax request
        $.ajax({
            type: 'POST',
            url: '/my/preferences',
            data: {
                preference_name: select.name,
                value: select.value,
                table: $(select).data('table')
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                console.log(data)

                //Enable and hide loading icon
                $(select).toggleClass('d-none');
                $(`#${preferenceName}_loading`).toggleClass('d-none');

                //Show saved toast
                Toastify({
                    text: `${$(select).data('pretty-name')} saved!`,
                    duration: 5000,
                    close: true,
                    gravity: "bottom", // `top` or `bottom`
                    position: 'right', // `left`, `center` or `right`
                    backgroundColor: '#00C851',
                    offset: {
                        x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                        y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                    },
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                }).showToast();

                //If it's UI mode or accent colour...
                if (select.name == 'ui_mode') {
                    $('body').attr('data-theme', select.value)
                } else if (select.name == 'accent_colour') {
                    $('body').attr('data-accent', select.value)
                }
            },
            error: function(data) {
                console.log('Error')
                console.log(data)

                //Error
                Toastify({
                    text: `Error changing '${$(select).data('pretty-name')}' preference (Request failed)`,
                    duration: 5000,
                    close: true,
                    gravity: "bottom", // `top` or `bottom`
                    position: 'right', // `left`, `center` or `right`
                    backgroundColor: '#ff4444',
                    offset: {
                        x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                        y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                    },
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                }).showToast();

                //Enable and hide loading icon
                $(select).toggleClass('d-none');
                $(`#${preferenceName}_loading`).toggleClass('d-none');
            }
        })
    });

});
