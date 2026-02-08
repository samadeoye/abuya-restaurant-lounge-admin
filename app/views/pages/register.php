<?php
$baseUrl = DEF_ROOT_PATH;
if (isset($_SESSION[SESSION_NAME]))
{
  header("Location: {$baseUrl}");
}
$pageTitle = 'Register';

$pathImg = DEF_PATH_ASSETS_IMG;
$pageContent = <<<EOQ

<div class="content-wrapper d-flex align-items-center auth">
  <div class="row flex-grow">
    <div class="col-lg-4 mx-auto">
      <div class="auth-form-light text-center p-5">
        <div class="brand-logo">
          <img src="{$pathImg}/logo.png">
        </div>
        <h4>New here?</h4>
        <h6 class="fw-light">Signing up is easy. It only takes a few steps</h6>
        <form method="post" onsubmit="return false;" class="pt-3" id="registerForm">
          <input type="hidden" name="action" id="action" value="register">
          <div class="form-group">
            <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Full Name">
          </div>
          <div class="form-group">
            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email">
          </div>
          <div class="form-group">
            <select class="form-select form-select-lg" id="role" name="role">
              <option value="admin">Admin</option>
              <option value="staff">Staff</option>
            </select>
          </div>
          <div class="form-group">
            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password">
          </div>
          <div class="form-group">
            <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
          </div>
          <div class="mt-3 d-grid gap-2">
            <a class="btn btn-block btn-primary btn-lg fw-semibold auth-form-btn" id="btnSubmit">SIGN UP</a>
          </div>
          <div class="text-center mt-4 fw-light"> Already have an account? <a href="login" class="text-primary">Login</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

EOQ;

$additionalJsOnLoad[] = <<<EOQ

$('#registerForm #btnSubmit').click(function()
{
  var formId = '#registerForm';
  var name = $(formId+' #name').val();
  var email = $(formId+' #email').val();
  var password = $(formId+' #password').val();
  var password_confirmation = $(formId+' #password_confirmation').val();

  if (name.length < 7 || name.length > 200)
  {
    throwError('Name is invalid');
  }
  else if (email.length < 13 || email.length > 100)
  {
    throwError('Email is incorrect');
  }
  else if (password.length < 8)
  {
    throwError('Password must contain at least 8 characters');
  }
  else if (password != password_confirmation)
  {
    throwError('Passwords do not match');
  }
  else
  {
    var registerForm = $("#registerForm");
    $.ajax({
      url: 'actions',
      type: 'POST',
      dataType: 'json',
      data: registerForm.serialize(),
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
          throwSuccess('Registration successful! Logging you in...');
          registerForm[0].reset();
          //redirect to dashboard
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
