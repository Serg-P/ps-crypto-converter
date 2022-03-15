document.addEventListener('DOMContentLoaded', function () {
  let btnForm = $('[data-convert-form]');
  let logMain = $('[data-convert-log]');
  let logList = $('[data-convert-list]');

  if (btnForm.length) {
    btnForm.submit(function (e) {
      e.preventDefault();
      let _this = $(this);
      let _thisName = _this.data('convert-form');
      let _thisRes = _this.find('[data-convert-res]');
      let _thisVal = _this.find('[data-convert-cur]').val();

      let data = {
        'action'   : 'psCryptoConvert',
        'currency' : _thisName,
        'value'    : _thisVal,
      };

      $.ajax({
        url        : ps_crypto_ajax_url.url,
        data       : data,
        type       : 'POST',
        dataType   : 'html',
        beforeSend : function () {
          _this.addClass('ps-cc__form_loading');
        },
        success    : function (data) {
          if (data) {
            _thisRes.val(data);
            get_log();
          }
          else {
            alert('Error! Please try again. If the error is repeated Contact the administrator')
          }
        },
        error: function (){
          alert('Error! Please try again. If the error is repeated Contact the administrator')
        },
        complete   : function () {
          setTimeout(function () {
            _this.removeClass('ps-cc__form_loading');
          },400);
        }
      });

      function get_log() {
        $.ajax({
          url        : ps_crypto_ajax_url.url,
          data       : {
            'action'   : 'psCryptoConvertLog',
          },
          type       : 'POST',
          dataType   : 'html',
          beforeSend : function () {
            logMain.addClass('ps-cc__log_loading');
          },
          success    : function (data) {
            if (data) {
              logList.html(data);
            }
          },
          complete   : function () {
            setTimeout(function () {
              logMain.removeClass('ps-cc__log_loading');
            },400);
          }
        });
      }


    });
  }
});