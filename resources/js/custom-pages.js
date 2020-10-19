$(document).ready(function () {

    //Form submit
    $("#response-form").submit(function(event){
        //If form is empty
        if (!$('#contentMD').val())
        {
            //Error
            Toastify({
                text: "Please write your response",
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
            }).showToast()

            event.preventDefault();

            return
        }

        //Make ajax request
        $.ajax({
            type: 'POST',
            url: window.location.href + '/response-submit',
            data: {
                content: $('#contentMD').val(),
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                console.log(data)

                //Show saved toast
                Toastify({
                    text: `Response submitted!`,
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

                location.reload();

            },
            error: function(data) {
                console.log('Error')
                console.log(data)

                //Error
                Toastify({
                    text: `Error (${data.responseJSON.message})`,
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

            }
        })


        event.preventDefault();
    });

});
