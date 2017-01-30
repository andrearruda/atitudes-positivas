$(document).ready(function() {
    $('[name="active"]').bootstrapSwitch();
    $('[name="active"]').on('switchChange.bootstrapSwitch init.bootstrapSwitch', function(event,  state) {
        var my_checkbox = $(this);
        var active = this.checked ? '1' : '0';
        var url = $(this).attr('data-url');

        $.ajax({
            type: 'GET',
            data: ({active: active}),
            url: url,
            dataType: 'text',
            success: function(data){
            },
            beforeSend: function(){
                my_checkbox.attr('disabled', true);
            },
            complete: function(){
                my_checkbox.removeAttr('disabled');
            }
        });
    })

    $('.pg_message .bt_modal_action').click(function(){
        var bt = $(this);
        var bt_dropdown = bt.parent().parent().parent().find('button');
        var url = $(this).attr('data-url');

        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'text',
            success: function(data){
                $('#load_modal').html(data);
                $('#load_modal #myModal').modal();
            },
            beforeSend: function(){
                bt_dropdown.find('i').removeClass();
                bt_dropdown.find('i').addClass('fa fa-cog fa-spin');
            },
            complete: function(){
                bt_dropdown.find('i').removeClass();
                bt_dropdown.find('i').addClass('caret');
            }
        });
    });
});