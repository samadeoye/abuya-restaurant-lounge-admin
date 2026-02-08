<?php
$baseUrl = DEF_ROOT_PATH.'/login';
if (isset($_SESSION[SESSION_NAME]))
{
  header("Location: {$baseUrl}");
}
$pageTitle = 'Reset Password';

$token = trim($_GET['token']);
$email = trim($_GET['email']);

$pathImg = DEF_PATH_ASSETS_IMG;
$pageContent = <<<EOQ

<div class="content-wrapper d-flex align-items-center auth">
  <div class="row flex-grow">
    <div class="col-lg-4 mx-auto">
      <div class="auth-form-light text-center p-5">
        <div class="brand-logo">
          <img src="{$pathImg}/logo.png">
        </div>
        <h4>Reset your password</h4>
        <h6 class="fw-light">Fill this to continue.</h6>
        <form method="post" onsubmit="return false;" class="pt-3" id="resetPasswordForm">
          <input type="hidden" name="action" id="action" value="resetpassword">
          <input type="hidden" name="token" id="token" value="{$token}">
          <input type="hidden" name="email" id="email" value="{$email}">
          <div class="form-group">
            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="New Password">
          </div>
          <div class="form-group">
            <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
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

$('#resetPasswordForm #btnSubmit').on('click', function ()
{
  var formId = '#resetPasswordForm';
  var password = $(formId+' #password').val();
  var password_confirmation = $(formId+' #password_confirmation').val();

  if (password.length < 8)
  {
    throwError('Password is invalid');
  }
  else if (password_confirmation.length < 8)
  {
    throwError('Password is invalid');
  }
  else if (password != password_confirmation)
  {
    throwError('Passwords do not match');
  }
  else
  {
    var resetPasswordForm = $("#resetPasswordForm");
    $.ajax({
      url: 'actions',
      type: 'POST',
      dataType: 'json',
      data: resetPasswordForm.serialize(),
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
          resetPasswordForm[0].reset();
          //redirect to login
          window.location.href = '{$baseUrl}';
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
