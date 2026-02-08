<?php
$baseUrl = DEF_ROOT_PATH;
if (isset($_SESSION[SESSION_NAME]))
{
  header("Location: {$baseUrl}");
}
$pageTitle = 'Forgot Password';

$pathImg = DEF_PATH_ASSETS_IMG;
$pageContent = <<<EOQ

<div class="content-wrapper d-flex align-items-center auth">
  <div class="row flex-grow">
    <div class="col-lg-4 mx-auto">
      <div class="auth-form-light text-center p-5">
        <div class="brand-logo">
          <img src="{$pathImg}/logo.png">
        </div>
        <h4>Forgot your password?</h4>
        <h6 class="fw-light">Fill this to continue.</h6>
        <form method="post" onsubmit="return false;" class="pt-3" id="forgotPasswordForm">
          <input type="hidden" name="action" id="action" value="forgotpassword">
          <div class="form-group">
            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email">
          </div>
          <div class="mt-3 d-grid gap-2">
            <a class="btn btn-block btn-primary btn-lg fw-semibold auth-form-btn" id="btnSubmit">SUBMIT</a>
          </div>
          <div class="my-2 d-flex justify-content-between align-items-center">
            <a href="login" class="auth-link text-black">Back to Login</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

EOQ;

$additionalJsOnLoad[] = <<<EOQ

$('#forgotPasswordForm #btnSubmit').on('click', function ()
{
  var formId = '#forgotPasswordForm';
  var email = $(formId+' #email').val();

  if (email.length < 13 || email.length > 100)
  {
    throwError('Email is invalid');
  }
  else
  {
    var forgotPasswordForm = $("#forgotPasswordForm");
    $.ajax({
      url: 'actions',
      type: 'POST',
      dataType: 'json',
      data: forgotPasswordForm.serialize(),
      beforeSend: function() {
        enableDisableBtn(formId+' #btnSubmit', 0);
      },
      complete: function() {
        enableDisableBtn(formId+' #btnSubmit', 1);
      },
      success: function(data)
      {
        if (data.status == true)
        {
          throwSuccess(data.msg);
          forgotPasswordForm[0].reset();
        }
        else
        {
          throwError(data.msg);
        }
      }
    });
  }
});

EOQ;

require_once DEF_PATH_PAGES . '/layout-guest.php';
