<?php
$baseUrl = DEF_ROOT_PATH;
if (isset($_SESSION[SESSION_NAME]))
{
  header("Location: {$baseUrl}");
}
$pageTitle = 'Login';

$pathImg = DEF_PATH_ASSETS_IMG;
$pageContent = <<<EOQ

<div class="content-wrapper d-flex align-items-center auth">
  <div class="row flex-grow">
    <div class="col-lg-4 mx-auto">
      <div class="auth-form-light text-center p-5">
        <div class="brand-logo">
          <img src="{$pathImg}/logo.png">
        </div>
        <h4>Hello! let's get started</h4>
        <h6 class="fw-light">Sign in to continue.</h6>
        <form method="post" onsubmit="return false;" class="pt-3" id="loginForm">
          <input type="hidden" name="action" id="action" value="login">
          <div class="form-group">
            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email">
          </div>
          <div class="form-group">
            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password">
          </div>
          <div class="mt-3 d-grid gap-2">
            <a class="btn btn-block btn-primary btn-lg fw-semibold auth-form-btn" id="btnSubmit">SIGN IN</a>
          </div>
          <div class="my-2 d-flex justify-content-between align-items-center">
            <a href="#" class="auth-link text-black">Forgot password?</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

EOQ;

$additionalJsOnLoad[] = <<<EOQ

$('#loginForm #btnSubmit').click(function ()
{
  var formId = '#loginForm';
  var email = $(formId+' #email').val();
  var password = $(formId+' #password').val();

  if (email.length < 13 || email.length > 100)
  {
    throwError('Email is invalid');
  }
  else if (password.length < 6)
  {
    throwError('Password is invalid');
  }
  else
  {
    var loginForm = $("#loginForm");
    $.ajax({
      url: 'actions',
      type: 'POST',
      dataType: 'json',
      data: loginForm.serialize(),
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
          throwSuccess('Login successful! Logging you in...');
          loginForm[0].reset();
          //redirect to dashboard
          window.location.href = '{$baseUrl}';
        }
        else
        {
          if (data.info !== undefined)
          {
            throwError(data.msg);
          }
          else
          {
            throwError(data.msg);
          }
        }
      }
    });
  }
});

EOQ;

require_once DEF_PATH_PAGES . '/layout-guest.php';
