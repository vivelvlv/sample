yii.allowAction = function ($e) {
    var message = $e.data('confirm');
    alert(message);
    return message === undefined || yii.confirm(message, $e);
};
// --- Delete action (bootbox) ---
yii.confirm = function (message, ok, cancel) {
    bootbox.confirm(
        {
            message: message,
            buttons: {
                confirm: {
                    label: "OK"
                },
                cancel: {
                    label: "Cancel"
                }
            },
            callback: function (confirmed) {
                if (confirmed) {
                    !ok || ok();
                } else {
                    !cancel || cancel();
                }
            }
        }
    );
    // confirm will always return false on the first call
    // to cancel click handler!
    return false;
}

//  function changeLanguage(lang){
//     $.cookie('language',lang,{expires:365});
//     window.location.reload();
// }

 $.widget.bridge('uibutton', $.ui.button);



