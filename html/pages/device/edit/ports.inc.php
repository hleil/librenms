<span id="message"></span>

<form id='ignoreport' name='ignoreport' method='post' action='' role='form' class='form-inline'>
    <input type='hidden' name='ignoreport' value='yes'>
    <input type='hidden' name='type' value='update-ports'>
    <input type='hidden' name='device' value='<?php echo $device['device_id'];?>'>
    <table id='edit-ports' class='table table-condensed table-responsive table-striped'>
        <thead>
            <tr>
                <th data-column-id='ifIndex'>Index</th>
                <th data-column-id='label'>Name</th>
                <th data-column-id='ifAdminStatus'>Admin</th>
                <th data-column-id='ifOperStatus'>Oper</th>
                <th data-column-id='disabled'>Disable</th>
                <th data-column-id='ignore'>Ignore</th>
                <th data-column-id='ifAlias'>Description</th>
            </tr>
        </thead>
    </table>
</form>
<script>

    $(document).ready(function() {
        $('form#ignoreport').submit(function (event) {
            $('#disable-toggle').click(function (event) {
                // invert selection on all disable buttons
                event.preventDefault();
                $('input[name^="disabled_"]').trigger('click');
            });
            $('#ignore-toggle').click(function (event) {
                // invert selection on all ignore buttons
                event.preventDefault();
                $('input[name^="ignore_"]').trigger('click');
            });
            $('#disable-select').click(function (event) {
                // select all disable buttons
                event.preventDefault();
                $('.disable-check').prop('checked', true);
            });
            $('#ignore-select').click(function (event) {
                // select all ignore buttons
                event.preventDefault();
                $('.ignore-check').prop('checked', true);
            });
            $('#down-select').click(function (event) {
                // select ignore buttons for all ports which are down
                event.preventDefault();
                $('[name^="operstatus_"]').each(function () {
                    var name = $(this).attr('name');
                    var text = $(this).text();
                    if (name && text == 'down') {
                        // get the interface number from the object name
                        var port_id = name.split('_')[1];
                        // find its corresponding checkbox and toggle it
                        $('input[name="ignore_' + port_id + '"]').trigger('click');
                    }
                });
            });
            $('#alerted-toggle').click(function (event) {
                // toggle ignore buttons for all ports which are in class red
                event.preventDefault();
                $('.red').each(function () {
                    var name = $(this).attr('name');
                    if (name) {
                        // get the interface number from the object name
                        var port_id = name.split('_')[1];
                        // find its corresponding checkbox and toggle it
                        $('input[name="ignore_' + port_id + '"]').trigger('click');
                    }
                });
            });
            $('#form-reset').click(function (event) {
                // reset objects in the form to their previous values
                event.preventDefault();
                $('#ignoreport')[0].reset();
            });
            $('#save-form').click(function (event) {
                // reset objects in the form to their previous values
                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "/ajax_form.php",
                    data: $('form#ignoreport').serialize(),
                    dataType: "json",
                    success: function(data){
                        if (data.status == 'ok') {
                            $("#message").html('<div class="alert alert-info">' + data.message + '</div>')
                        } else {
                            $("#message").html('<div class="alert alert-danger">' + data.message + '</div>');
                        }
                    },
                    error: function(){
                        $("#message").html('<div class="alert alert-danger">Error creating config item</div>');
                    }
                });
            });
            event.preventDefault();
        });
    });

    var grid = $("#edit-ports").bootgrid({
        ajax: true,
        rowCount: [50,100,250,-1],
        post: function ()
        {
            return {
                id: 'edit-ports',
                device_id: "<?php echo $device['device_id']; ?>"
            };
        },
        url: "/ajax_table.php"
    });
</script>
