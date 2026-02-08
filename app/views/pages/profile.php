<?php
$pageTitle = 'Profile';
$baseUrl = DEF_ROOT_PATH;

$additionalCss[] = <<<EOQ
EOQ;

$pageContent = <<<EOQ

<div class="page-header">
    <h3 class="page-title"> {$pageTitle} </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{$baseUrl}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">{$pageTitle}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title pb-3">Update Profile</h4>
                <form id="updateProfileForm" method="post" action="inc/actions" onsubmit="return false;">
                    <input type="hidden" name="action" id="action" value="updateprofile">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" value="{$arUser['name']}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{$arUser['email']}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg me-2" id="btnSubmit">Update Profile</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title pb-3">Change Password</h4>
                <form id="changePasswordForm" method="post" action="inc/actions" onsubmit="return false;">
                    <input type="hidden" name="action" id="action" value="changepassword">
                    <div class="form-group">
                        <label for="name">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <label for="name">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg me-2" id="btnSubmit">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

EOQ;

$additionalJs[] = <<<EOQ

EOQ;

$additionalJsOnLoad[] = <<<EOQ

$('#updateProfileForm #btnSubmit').click(function ()
{
    var formId = '#updateProfileForm';
    var name = $(formId+' #name').val();
    var email = $(formId+' #email').val();

    if (name.length < 5 || name.length > 100)
    {
        throwError('Name is invalid');
    }
    else if (email.length < 13 || email.length > 100)
    {
        throwError('Email is invalid');
    }
    else
    {
        var updateProfileForm = $("#updateProfileForm");
        $.ajax({
            url: 'actions',
            type: 'POST',
            dataType: 'json',
            data: updateProfileForm.serialize(),
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
                    //updateProfileForm[0].reset();
                    $(formId+' #name').val(data.data['name']);
                    $(formId+' #email').val(data.data['email']);
                }
                else
                {
                    throwError(data.msg);
                }
            }
        });
    }
});

$('#changePasswordForm #btnSubmit').click(function ()
{
    var formId = '#changePasswordForm';
    var password = $(formId+' #password').val();
    var password_confirmation = $(formId+' #password_confirmation').val();

    if (password.length < 8)
    {
        throwError('New password must contain at least 8 characters');
    }
    else if (password_confirmation.length < 8)
    {
        throwError('Password confirmation must contain at least 8 characters');
    }
    else
    {
        var changePasswordForm = $("#changePasswordForm");
        $.ajax({
            url: 'actions',
            type: 'POST',
            dataType: 'json',
            data: changePasswordForm.serialize(),
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
                    changePasswordForm[0].reset();
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

$pathAssetsVendors = DEF_PATH_ASSETS_VENDORS;
$additionalJsScripts[] = <<<EOQ
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4/dist/autoNumeric.min.js"></script>
EOQ;

require_once DEF_PATH_PAGES . '/layout.php';
